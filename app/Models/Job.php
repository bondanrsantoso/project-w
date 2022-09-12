<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'worker_id',
        'name',
        'project_id',
        'status',
        'job_category_id',
        'amount',
        'start_date',
        'end_date'
    ];

    public function projects()
    {
        return $this->belongsTo(Project::class, "project_id", "id");
    }

    public function projectsAdmin()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id')->withoutGlobalScopes();
    }

    public function workers()
    {
        return $this->belongsTo(User::class, 'worker_id', 'id');
    }

    public function categories()
    {
        return $this->belongsTo(JobCategory::class, 'job_category_id', 'id');
    }
}
