<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "payment_id",
        "payment_type",
        "icon_url",
        "transaction_fee_amount",
        "transaction_fee_percent",
    ];
}
