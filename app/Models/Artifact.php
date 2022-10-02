<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Artifact extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "file_url",
        "milestone_id",
    ];

    public function milestone()
    {
        return $this->belongsTo(Milestone::class, "milestone_id", "id");
    }
}
