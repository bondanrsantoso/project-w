<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'username',
        'password',
        'phone_number',
        // 'type',
        // 'rating',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function worker()
    {
        return $this->hasOne(Worker::class, "user_id", "id");
    }

    public function company()
    {
        return $this->hasOne(Company::class, "user_id", "id");
    }

    protected $appends = ["is_company", "is_worker"];

    protected function isCompany()
    {
        $isCompany = $this->company()->isNot(null);
        return Attribute::make(get: function ($value, $attributes) use ($isCompany) {
            return $isCompany;
        });
    }

    protected function isWorker()
    {
        $isWorker = $this->worker()->isNot(null);
        return Attribute::make(get: function ($value, $attributes) use ($isWorker) {
            return $isWorker;
        });
    }
}
