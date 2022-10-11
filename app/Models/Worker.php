<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'address',
        'place_of_birth',
        'date_of_birth',
        'gender',
        'account_number',
        'balance'
    ];

    public function user()
    {
        # code...
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function experiences()
    {
        # code...
        return $this->hasMany(WorkerExperience::class, 'worker_id', 'id');
    }
    public function category()
    {
        # code...
        return $this->belongsTo(JobCategory::class, 'category_id', 'id');
    }
    public function portofolios()
    {
        # code...
        return $this->hasMany(WorkerPortofolio::class, 'worker_id', 'id');
    }
}
