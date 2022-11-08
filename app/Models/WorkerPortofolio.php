<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerPortofolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'link_url',
        'worker_id',
        'attachment_url'
    ];

    public function worker()
    {
        # code...
        return $this->belongsTo(Worker::class);
    }
}
