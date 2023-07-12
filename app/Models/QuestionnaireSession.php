<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionnaireSession extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "last_question_id",
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    public function question()
    {
        return $this->belongsTo(Question::class, "last_question_id", "id");
    }

    public function suggestions()
    {
        return $this->belongsToMany(
            ServicePack::class,
            "questionnaire_suggestions",
            "session_id",
            "service_pack_id",
            "id",
            "id"
        );
    }

    public function questions()
    {
        return $this->belongsToMany(
            Question::class,
            "questionnaire_answers",
            "session_id",
            "question_id",
            "id",
            "id"
        )
            ->as("answered")
            ->withPivot("answer")
            ->withTrashed();
    }

    protected $appends = ["last_suggestion"];

    public function lastSuggestion(): Attribute
    {
        if (!($this->id ?? false)) {
            return Attribute::make(get: fn ($value) => null);
        }

        $lastSuggestion = $this->suggestions()->orderByPivot("created_at", "desc")->first();
        return Attribute::make(get: function ($value) use ($lastSuggestion) {
            return $lastSuggestion ?? null;
        });
    }
}
