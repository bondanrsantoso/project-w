<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        "va_number",
        "transaction_fee",
        "service_fee",
        "subtotal",
        "grand_total",
        "transaction_status",
        "actions",
        "job_id",
        "company_id",
        "payment_method_id",
    ];

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, "payment_method_id", "id");
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, "invoice_id", "id");
    }
}
