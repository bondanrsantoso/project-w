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
        Schema::create('training_test_item_options', function (Blueprint $table) {
            $table->id();
            $table->text("statement");
            $table->boolean("is_answer");
            $table->unsignedBigInteger("test_item_id");
            $table->timestamps();

            $table->foreign("test_item_id")
                ->references("id")
                ->on("training_test_items")
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
        Schema::dropIfExists('training_test_item_options');
    }
};
