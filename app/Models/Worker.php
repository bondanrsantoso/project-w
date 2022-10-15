<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'place_of_birth',
        'date_of_birth',
        'gender',
        'account_number',
        'balance'
    ];

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
        return $this->belongsTo(WorkCategory::class, 'category_id', 'id');
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
}
