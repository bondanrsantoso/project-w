<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Worker extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'job_category_id',
        'address',
        'birth_place',
        'birthday',
        'gender',
        'credit_balance',
    ];

    protected $attributes = [
        'credit_balance' => 0,
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }
}
