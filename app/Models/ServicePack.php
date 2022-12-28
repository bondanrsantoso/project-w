<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServicePack extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "name",
        "description"
    ];

    public function workgroups(): HasMany
    {
        return $this->hasMany(ServicePackWorkGroup::class, "service_pack_id", "id");
    }
}
