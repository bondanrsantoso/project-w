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
        "transaction_status",
        "actions",
        "job_id",
        "company_id",
        "payment_method_id",
        "project_id",
        "worker_id",
    ];

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, "payment_method_id", "id");
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, "invoice_id", "id")->orderBy("updated_at", "desc");
    }

    public function job()
    {
        return $this->belongsTo(Job::class, "job_id", "id")->withTrashed();
    }

    public function company()
    {
        return $this->belongsTo(Company::class, "company_id", "id")->withTrashed();
    }

    public function worker()
    {
        return $this->belongsTo(Worker::class, "worker_id", "id")->withTrashed();
    }

    public function project()
    {
        return $this->belongsTo(Project::class, "project_id", "id")->withTrashed();
    }
}
