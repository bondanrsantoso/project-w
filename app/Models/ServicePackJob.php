<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServicePackJob extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "description",
        "order",
        "job_category_id",
        "workgroup_id",
    ];

    public function workgroup(): BelongsTo
    {
        return $this->belongsTo(ServicePackWorkGroup::class, "workgroup_id", "id");
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(JobCategory::class, "job_category_id", "id");
    }
}
