<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workgroup extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "project_id",
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, "project_id", "id");
    }

    public function jobs()
    {
        return $this->hasMany(Job::class, "workgroup_id", "id");
    }
}
