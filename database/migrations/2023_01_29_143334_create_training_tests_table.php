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
        Schema::create('training_tests', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->string("description");
            $table->string("start_time")->nullable();
            $table->string("end_time")->nullable();
            $table->unsignedInteger("duration")->nullable()->comment("in minutes");
            $table->unsignedInteger("attempt_limit")->nullable();
            $table->unsignedBigInteger("training_id");
            $table->unsignedDecimal("passing_grade");
            $table->boolean("is_pretest")->default(false);
            $table->unsignedInteger("order")->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger("prerequisite_test_id")->nullable();

            $table->foreign("training_id")
                ->references("id")
                ->on("training_events")
                ->onDelete("cascade")
                ->onUpdate("cascade");

            $table->foreign("prerequisite_test_id")
                ->references("id")
                ->on("training_tests")
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
        Schema::dropIfExists('training_tests');
    }
};
