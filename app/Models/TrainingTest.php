<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingTest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "title",
        "description",
        "start_time",
        "end_time",
        "duration",
        "attempt_limit",
        "training_id",
        "passing_grade",
        "is_pretest",
        "order",
        "prerequisite_test_id",
    ];

    public function trainingItem()
    {
        return $this->belongsTo(TrainingEvent::class, "training_id", "id");
    }

    public function prerequisiteTest()
    {
        return $this->belongsTo(TrainingTest::class, "prerequisite_test_id", "id");
    }

    public function items()
    {
        return $this->hasMany(TrainingTestItem::class, "test_id", "id");
    }
}
