<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        "job_id",
        "worker_id",
        "is_hired",
        "status",
    ];

    public function job()
    {
        return $this->belongsTo(Job::class, "job_id", "id");
    }

    public function worker()
    {
        return $this->belongsTo(Worker::class, "worker_id", "id");
    }

    protected $casts = [
        "is_hired" => "boolean"
    ];
}
