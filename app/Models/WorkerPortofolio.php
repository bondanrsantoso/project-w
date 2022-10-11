<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerPortofolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'worker_id',
        'title',
        'description',
        'link_url',
    ];

    public function worker()
    {
        # code...
        return $this->belongsTo(Worker::class);
    }
}
