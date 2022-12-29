<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "owner_name",
        "province",
        "city",
        "address",
        "district",
        "revenue",
        "type",
        "scale",
        "data_year",
        "assets",
        "assets_scale"
    ];
}
