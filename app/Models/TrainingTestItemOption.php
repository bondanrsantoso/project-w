<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingTestItemOption extends Model
{
    use HasFactory;

    protected $fillable = [
        "statement",
        "is_answer",
        "test_item_id",
    ];

    protected $attributes = [
        "is_answer" => false,
    ];

    public function testItem()
    {
        return $this->belongsTo(TrainingTestItem::class, "test_item_id", "id");
    }
}
