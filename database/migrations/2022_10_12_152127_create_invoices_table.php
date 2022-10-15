<?php

use App\Interface\TransactionStatus;
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
        Schema::create('invoices', function (Blueprint $table) {
            // $table->id();
            $table->uuid("id")->primary();
            $table->string("va_number");
            $table->decimal("transaction_fee", 14, 2);
            $table->decimal("service_fee", 14, 2);
            $table->decimal("subtotal", 14, 2);
            $table->decimal("grand_total", 14, 2)->virtualAs("transaction_fee + subtotal + service_fee");
            $table->string("transaction_status")->default(TransactionStatus::Pending->value);
            $table->json("actions")->nullable();
            $table->unsignedBigInteger("job_id");
            $table->unsignedBigInteger("company_id");
            $table->unsignedBigInteger("payment_method_id");
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("job_id")->references("id")->on("jobs");
            $table->foreign("company_id")->references("id")->on("companies");
            $table->foreign("payment_method_id")->references("id")->on("payment_methods");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
};
