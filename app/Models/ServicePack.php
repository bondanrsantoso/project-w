<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServicePack extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "description"
    ];

    public function workgroups(): HasMany
    {
        return $this->hasMany(ServicePackWorkGroup::class, "service_pack_id", "id");
    }
}
