<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingTestSessionAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        "session_id",
        "training_test_item_id",
        "answer_option_id",
        "answer_literal",
    ];

    public function session()
    {
        return $this->belongsTo(TrainingTestSession::class, "session_id", "id");
    }

    public function trainingTestItem()
    {
        return $this->belongsTo(TrainingTestItem::class, "training_test_item_id", "id");
    }

    public function answerOption()
    {
        return $this->belongsTo(TrainingTestItemOption::class, "answer_option_id", "id");
    }
}
