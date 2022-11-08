<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "issuer",
        "year",
        "description",
        "attachment_url",
        "worker_id",
    ];

    public function worker()
    {
        return $this->belongsTo(Worker::class, "worker_id", "id");
    }
}
