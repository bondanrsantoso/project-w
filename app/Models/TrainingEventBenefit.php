<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingEventBenefit extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "description",
        "icon_url",
        "event_id",
    ];

    public function event()
    {
        return $this->belongsTo(TrainingEvent::class, "event_id", "id");
    }
}
