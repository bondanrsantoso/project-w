<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Job extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_PENDING = "pending";
    const STATUS_DONE = "done";
    const STATUS_CANCELED = "canceled";
    const STATUS_ON_PROGRESS = "on_progress";
    const STATUS_PAID = "paid";
    const STATUS_OVERDUE = "overdue";
    const STATUS_EXPIRED = "expired";

    public $fillable = [
        "name",
        "description",
        "order",
        "budget",
        "status",
        "date_start",
        "date_end",
        "workgroup_id",
        "job_category_id",
        "worker_id",
        "overview",
        "requirements",
        "is_public"
    ];

    protected $casts = [
        "date_start" => "datetime",
        "date_end" => "datetime",
    ];

    public function rawStatus(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes["status"]
        );
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $now = date_create();
                $endDate = date_create_immutable($attributes["date_end"]);
                if ($value == Job::STATUS_CANCELED || $value == Job::STATUS_DONE) {
                    return $value;
                }

                if ($value != Job::STATUS_PENDING) {
                    return $now > $endDate ? Job::STATUS_OVERDUE : $value;
                } else {
                    return $now > $endDate ? Job::STATUS_EXPIRED : $value;
                }
            }
        );
    }

    public function workgroup()
    {
        return $this->belongsTo(Workgroup::class, "workgroup_id", "id");
    }

    public function jobCategory()
    {
        return $this->belongsTo(JobCategory::class, "job_category_id", "id");
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class, "job_id", "id");
    }

    // public function project()
    // {
    //     return $this->hasOneThrough(Project::class, Workgroup::class, "job_id", "project_id", "id", "id");
    // }

    /**
     * @deprecated
     */
    public function worker()
    {
        return $this->belongsTo(Worker::class, "worker_id", "id");
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class, "job_id", "id");
    }

    public function applyingWorkers()
    {
        return $this->belongsToMany(Worker::class, "job_applications", "job_id", "worker_id", "id", "id")->withPivot("is_hired");
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, "job_id", "id");
    }

    public function company(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                // Log::info(json_encode($attributes));
                // Log::info(Company::whereRelation("projects.workgroups.jobs", "id", $attributes["id"])->toSql());
                $company =
                    Company::whereRelation("projects.workgroups.jobs", "id", $attributes["id"])
                    ->first();
                // Log::info(json_encode($company));


                return $company;
            }
        );

        // if (sizeof($this->attributes) == 0 || (!$this->workgroup)) {
        //     return Attribute::make(get: fn ($value) => null);
        // }
        // // $company = $this->workgroup->project->company;
        // if ($company) {
        //     $company->load(["user"]);
        //     return Attribute::make(get: function ($value) use ($company) {
        //         return $company;
        //     })->shouldCache();
        // }

        // return Attribute::make(get: fn ($value) => null);
    }

    public function isApplied(): Attribute
    {

        // if ($user && $user->is_worker) {
        //     $isApplied = $this->applications->where("worker_id", $user->worker->id)->first() != null;
        // }
        return Attribute::make(get: function ($value, $attributes) {
            $user = Auth::user();
            $isApplied = false;

            if ($user && $user->is_worker) {
                $isApplied =
                    JobApplication::where("job_id", $attributes["id"])
                    ->where("worker_id", $user->worker->id)
                    ->first() != null;
            }
            return $isApplied;
        });
    }

    protected $appends = ["company", "is_applied"];
}
