<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid("invoice_id");
            $table->string("transaction_status");
            $table->string("fraud_status")->nullable();
            $table->string("status_code");
            $table->string("signature_key");
            $table->string("payment_type");
            $table->date("transaction_time");
            $table->decimal("gross_amount", 14, 2)->default(0);
            $table->string("currency")->default("IDR");
            $table->string("acquirer")->nullable();
            $table->timestamps();

            $table->foreign("invoice_id")->references("id")->on("invoices");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
