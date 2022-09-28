<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Milestone extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "title",
        "body",
        "job_id",
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class, "job_id", "id");
    }

    public function artifacts()
    {
        return $this->hasMany(Artifact::class, "milestone_id", "id");
    }
}
