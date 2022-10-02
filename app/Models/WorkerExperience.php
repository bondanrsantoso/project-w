<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerExperience extends Model
{
    use HasFactory;

    protected $fillable = [
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
