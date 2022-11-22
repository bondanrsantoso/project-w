<?php

namespace App\Models;

use DateTimeImmutable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

use function PHPSTORM_META\map;

class TrainingEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "description",
        "image_url",
        "start_date",
        "end_date",
        "location",
        "sessions",
        "seat",
        "category_id",
        "company_id",
    ];

    public function category()
    {
        return $this->belongsTo(JobCategory::class, "category_id", "id");
    }

    public function company()
    {
        return $this->belongsTo(Company::class, "company_id", "id");
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, "training_event_participants", "event_id", "user_id", "id", "id")->as("attendance")->withPivot(["is_confirmed"]);
    }

    public function participation()
    {
        return $this->hasMany(TrainingEventParticipant::class, "event_id", "id");
    }

    public function benefits()
    {
        return $this->hasMany(TrainingEventBenefit::class, "event_id", "id");
    }

    protected $appends = ["attendance_count", "status", "is_bookmarked"];

    public function attendanceCount(): Attribute
    {
        if (!($this->id ?? false)) {
            return Attribute::make(fn ($value) => 0);
        }
        $count = $this->participants()->count();

        return Attribute::make(get: function ($value) use ($count) {
            return $count;
        });
    }

    public function status(): Attribute
    {
        if (!($this->id ?? false)) {
            return Attribute::make(fn ($value) => null);
        }

        $user = Auth::user();
        $attendance = null;

        if ($user) {
            $attendance = $this->participation()->where("user_id", $user->id)->first();
        }

        return Attribute::make(
            get: function ($value, $attributes) use ($attendance) {
                $dateStart = new DateTimeImmutable($attributes["start_date"]);
                $dateEnd = new DateTimeImmutable($attributes["end_date"]);
                $now = new DateTimeImmutable();

                if ($dateStart > $now) {
                    if ($attendance) {
                        return "bookmarked";
                    }
                    return "upcoming";
                } else if ($now < $dateEnd) {
                    return "ongoing";
                } else {
                    return "ended";
                }
            }
        );
    }

    public function isBookmarked(): Attribute
    {
        if (!($this->id && Auth::check() ?? false)) {
            return Attribute::make(fn ($value) => null);
        }

        $user = Auth::user();
        $participation = $this->participation()->where("user_id", $user->id)->first();

        return Attribute::make(
            get: function ($value) use ($participation) {
                return $participation ? true : false;
            }
        );
    }

    /**
     * Special processing function to handle status query filtering
     */
    public static function filterByStatus(Builder $builder, string $status)
    {
        if ($status == "upcoming") {
            return $builder->where("start_date", ">", date("Y-m-d H:i:s"));
        } else if ($status == "ongoing") {
            return $builder->where(function ($q) {
                $q->where("start_date", "<=", date("Y-m-d H:i:s"))
                    ->where("end_date", ">=", date("Y-m-d H:i:s"));
            });
        } else if ($status == "ended") {
            return $builder->where("end_date", "<=", date("Y-m-d H:i:s"));
        } else if ($status == "bookmarked") {
            $user = Auth::user();
            if (!$user) {
                return $builder->where("created_at", "<=", "1990-01-01");
            }

            return $builder->whereRelation("participation", "user_id", $user->id);
        }
    }
}
