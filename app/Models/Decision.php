<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Decision extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'answer_yes',
        'answer_no',
        'action_yes',
        'action_no',
    ];
}
