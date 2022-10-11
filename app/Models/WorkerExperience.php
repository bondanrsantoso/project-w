<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkerExperience extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'worker_id',
        'position',
        'organization',
        'date_start',
        'date_end',
    ];

    public function worker()
    {
        # code...
        return $this->belongsTo(Worker::class);
    }
}
