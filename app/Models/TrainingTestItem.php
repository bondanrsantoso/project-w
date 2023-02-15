<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingTestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        "statement",
        "weight",
        "order",
        "answer_literal",
        "test_id",
    ];

    public function test()
    {
        return $this->belongsTo(TrainingTest::class, "test_id", "id");
    }

    public function options()
    {
        return $this->hasMany(TrainingTestItemOption::class, "test_item_id", "id");
    }
}
