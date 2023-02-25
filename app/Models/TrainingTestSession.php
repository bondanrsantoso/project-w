<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingTestSession extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "training_test_id",
        "raw_grade",
        "grade_override",
        "is_finished",
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    public function test()
    {
        return $this->belongsTo(TrainingTest::class, "training_test_id", "id");
    }

    public function answers()
    {
        return $this->hasMany(TrainingTestSessionAnswer::class, "session_id", "id");
    }
}
