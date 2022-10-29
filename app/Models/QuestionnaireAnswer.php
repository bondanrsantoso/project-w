<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionnaireAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        "question_id",
        "session_id",
        "answer",
    ];

    protected $casts = [
        "answer" => "boolean",
    ];

    public function question()
    {
        return $this->belongsTo(Question::class, "question_id", "id");
    }

    public function session()
    {
        return $this->belongsTo(QuestionnaireSession::class, "session_id", "id");
    }
}
