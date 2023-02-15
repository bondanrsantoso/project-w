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
        Schema::create('training_test_session_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("session_id");
            $table->unsignedBigInteger("training_test_item_id")->nullable();
            $table->unsignedBigInteger("answer_option_id")->nullable();
            $table->text("answer_literal")->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("session_id")
                ->references("id")
                ->on("training_test_sessions")
                ->onDelete("cascade")
                ->onUpdate("cascade");

            $table->foreign("training_test_item_id")
                ->references("id")
                ->on("training_test_items")
                ->onDelete("set null")
                ->onUpdate("cascade");

            $table->foreign("answer_option_id")
                ->references("id")
                ->on("training_test_item_options")
                ->onDelete("set null")
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
        Schema::dropIfExists('training_test_session_answers');
    }
};
