<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    protected $fillable = [
        "name",
        "description",
        "order",
        "budget",
        "status",
        "date_start",
        "date_end",
        "workgroup_id",
        "job_category_id",
    ];

    protected $casts = [
        "date_start" => "datetime",
        "date_end" => "datetime",
    ];

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
}
