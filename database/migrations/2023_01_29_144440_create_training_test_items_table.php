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
        Schema::create('training_test_items', function (Blueprint $table) {
            $table->id();
            $table->text("statement");
            $table->unsignedDecimal("weight")->default(1.0);
            $table->unsignedInteger("order")->default(0);
            $table->text("answer_literal")->nullable();
            $table->unsignedBigInteger("test_id");
            $table->timestamps();

            $table->foreign("test_id")
                ->references("id")
                ->on("training_tests")
                ->onDelete("cascade")
                ->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('training_test_items');
    }
};
