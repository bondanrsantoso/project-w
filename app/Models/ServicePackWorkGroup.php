<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServicePackWorkGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "service_pack_id"
    ];

    public function servicePack(): BelongsTo
    {
        return $this->belongsTo(ServicePack::class, "service_pack_id", "id");
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(ServicePackJob::class, "workgroup_id", "id");
    }
}
