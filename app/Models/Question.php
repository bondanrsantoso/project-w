<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'statement',
        'next_on_yes',
        'next_on_no',
        'answer_no',
        'answer_yes',
    ];

    public function nextQuestionOnYes()
    {
        return $this->belongsTo(Question::class, "next_on_yes", "id");
    }

    public function nextQuestionOnNo()
    {
        return $this->belongsTo(Question::class, "next_on_no", "id");
    }

    public function servicePackOnYes()
    {
        return $this->belongsTo(ServicePack::class, "answer_yes", "id");
    }

    public function servicePackOnNo()
    {
        return $this->belongsTo(ServicePack::class, "answer_no", "id");
    }
}
