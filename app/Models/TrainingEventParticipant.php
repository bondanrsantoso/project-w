<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingEventParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "event_id",
        "is_confirmed",
        "is_approved",
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    public function event()
    {
        return $this->belongsTo(TrainingEvent::class, "event_id", "id");
    }
}
