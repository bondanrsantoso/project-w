<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "name",
        "description",
        "service_pack_id",
        "company_id",
        "budget",
        "approved_by_admin",
        "approved_by_client",
    ];

    public function servicePack()
    {
        return $this->belongsTo(ServicePack::class, "service_pack_id", "id");
    }

    public function workgroups()
    {
        return $this->hasMany(Workgroup::class, "project_id", "id");
    }

    public function jobs()
    {
        return $this->hasManyThrough(Job::class, Workgroup::class, "project_id", "workgroup_id", "id", "id");
    }

    public function company()
    {
        return $this->belongsTo(Company::class, "company_id", "id");
    }
}
