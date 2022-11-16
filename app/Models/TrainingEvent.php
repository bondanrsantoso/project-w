<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function PHPSTORM_META\map;

class TrainingEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "description",
        "start_date",
        "end_date",
        "location",
        "sessions",
        "seat",
        "category_id",
    ];

    public function category()
    {
        return $this->belongsTo(JobCategory::class, "category_id", "id");
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, "training_event_participants", "event_id", "user_id", "id", "id")->withPivot(["is_confirmed"]);
    }

    public function benefits()
    {
        return $this->hasMany(TrainingEventBenefit::class, "event_id", "id");
    }

    protected $appends = ["attendance_count"];

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
}
