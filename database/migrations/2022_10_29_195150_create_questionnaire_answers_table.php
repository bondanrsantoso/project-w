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
        Schema::create('questionnaire_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("question_id");
            $table->unsignedBigInteger("session_id");
            $table->boolean("answer");
            $table->timestamps();

            $table->foreign("question_id")->references("id")->on("questions");
            $table->foreign("session_id")->references("id")->on("questionnaire_sessions");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questionnaire_answers');
    }
};
