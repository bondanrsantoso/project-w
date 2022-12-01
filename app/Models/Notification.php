<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        "title",
        "description",
        "is_read",
        "link",
        "user_id",
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }
}
