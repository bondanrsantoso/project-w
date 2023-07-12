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
        'image_url',
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
        'verification_token',
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

    public function events()
    {
        return $this
            ->belongsToMany(
                TrainingEvent::class,
                "training_event_participants",
                "user_id",
                "event_id",
                "id",
                "id"
            )
            ->as("attendance")
            ->withPivot(["is_confirmed"]);
    }

    protected $appends = ["is_company", "is_worker"];

    protected function isCompany(): Attribute
    {
        // $isCompany = $this->company()->first() != null;
        return Attribute::make(get: function ($value, $attributes) {
            $isCompany = Company::where("user_id", $attributes["id"])->first() != null;
            return $isCompany;
        });
    }

    protected function isWorker(): Attribute
    {
        // $isWorker = $this->worker()->first() != null;
        return Attribute::make(get: function ($value, $attributes) {
            $isWorker = Worker::where("user_id", $attributes["id"])->first() != null;
            return $isWorker;
        });
    }

    protected function imageUrl(): Attribute
    {
        $firstLetter = urlencode(substr($this->name, 0, 1));
        return Attribute::make(
            get: fn ($value) => $value ? url($value) : "https://placehold.jp/150/3d4070/ffffff/300x300.jpg?text={$firstLetter}"
        );
    }
}
