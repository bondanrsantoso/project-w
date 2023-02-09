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
        Schema::create('training_test_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("training_test_id");
            $table->smallInteger("raw_grade")->nullable()->comment("on a scale from 0 to 100");
            $table->smallInteger("grade_override")
                ->nullable()
                ->comment("if the grade ever needs to be overridden by user/admin");
            $table->timestamps();

            $table->foreign("user_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade")
                ->onUpdate("cascade");

            $table->foreign("training_test_id")
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
        Schema::dropIfExists('training_test_sessions');
    }
};
