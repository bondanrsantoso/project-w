<?php

namespace App\Models;

use Illuminate\Contracts\Mail\Attachable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Symfony\Component\VarDumper\Caster\DateCaster;

use function PHPUnit\Framework\at;

class Worker extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'address',
        'place_of_birth',
        'date_of_birth',
        'gender',
        'account_number',
        'account_bank',
        "category_id",
        'balance',
        'description',
        'experience',
        'is_eligible_for_work',
        'is_student',
        'is_freelancer',
    ];

    // protected $with = ["user"];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function experiences()
    {
        return $this->hasMany(WorkerExperience::class, 'worker_id', 'id');
    }
    public function category()
    {
        return $this->belongsTo(JobCategory::class, 'category_id', 'id');
    }
    public function portofolios()
    {
        return $this->hasMany(WorkerPortofolio::class, 'worker_id', 'id');
    }

    public function appliedJobs()
    {
        return $this->belongsToMany(Job::class, "job_applications", "worker_id", "job_id", "id", "id")->withPivot("is_hired");
    }

    public function jobs()
    {
        // return $this->hasMany(Job::class, "worker_id", "id");
        return $this->belongsToMany(Job::class, "job_applications", "worker_id", "job_id", "id", "id")->withPivot("is_hired")->wherePivot("is_hired", true);
    }

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class, "worker_id", "id");
    }

    public function achievements()
    {
        return $this->hasMany(Achievement::class, "worker_id", "id");
    }

    public function isEligibleForWork(): Attribute
    {
        if (!($this->id ?? false)) {
            return Attribute::make(get: fn ($value) => null);
        }

        $achievements = $this->achievements()->first();

        return Attribute::make(get: function ($value) use ($achievements) {
            return $achievements && $value;
        });
    }

    // public function experience(): Attribute
    // {
    //     if (!($this->id ?? false)) {
    //         return Attribute::make(get: fn ($value) => 0);
    //     }
    //     $earliestExperience = $this->experiences()->orderBy("date_start", "asc")->first();
    //     $latestExperience = $this->experiences()->orderBy("date_end", "desc")->first();
    //     $age = 0;
    //     if ($earliestExperience && $latestExperience) {
    //         $start = date_create($earliestExperience->date_start);
    //         $end = date_create($latestExperience->date_end);

    //         $age = $start->diff($end, true)->y;
    //     }

    //     return Attribute::make(get: function ($value) use ($age) {
    //         return $age;
    //     });
    // }

    // protected $appends = ["experience"];
}
