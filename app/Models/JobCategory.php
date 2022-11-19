<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        "parent_id"
    ];

    public function parent()
    {
        return $this->belongsTo(JobCategory::class, "parent_id", "id");
    }

    public function subCategories()
    {
        return $this->hasMany(JobCategory::class, "parent_id", "id");
    }

    public function jobs()
    {
        return $this->hasMany(Job::class, 'job_category_id', 'id');
    }

    public function trainingEvents()
    {
        return $this->hasMany(TrainingEvent::class, "category_id", "id");
    }

    public function jobCount(): Attribute
    {
        if (!($this->id ?? false)) {
            return Attribute::make(get: fn ($value) => null);
        }

        $jobsCount = $this->jobs()->count();
        return Attribute::make(function ($value) use ($jobsCount) {
            return $jobsCount;
        });
    }

    public function trainingEventCount(): Attribute
    {
        if (!($this->id ?? false)) {
            return Attribute::make(get: fn ($value) => null);
        }

        $eventCount = $this->trainingEvents()->count();
        return Attribute::make(
            get: function ($valud) use ($eventCount) {
                return $eventCount;
            }
        );
    }

    protected $appends = ["job_count", "training_event_count"];
}
