<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'phone_number',
        'user_id',
        'image_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    public function projects()
    {
        return $this->hasMany(Project::class, "company_id", "id");
    }

    public function imageUrl(): Attribute
    {
        $user = $this->user()->first();
        return Attribute::make(get: function ($value) use ($user) {
            return $value ?? $user->image_url;
        });
    }
}
