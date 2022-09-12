<?php

namespace App\Models;

use App\Traits\MultitenantProject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory, MultitenantProject;

    protected $fillable = [
        'customer_id',
        'name',
        'description'
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }
    
    public function workers()
    {
        return $this->belongsTo(User::class, 'worker_id', 'id');
    }

    public function jobs()
    {
        return $this->hasMany(Job::class, 'project_id', 'id');
    }
}
