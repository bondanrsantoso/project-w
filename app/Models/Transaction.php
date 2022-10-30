<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        "invoice_id",
        "transaction_status",
        "transaction_time",
        "status_code",
        "signature_key",
        "payment_type",
        "gross_amount",
        "currency",
        "acquirer",
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, "invoice_id", "id");
    }
}
