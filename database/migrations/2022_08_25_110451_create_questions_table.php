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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string("statement");
            $table->unsignedBigInteger("next_on_yes")->nullable();
            $table->unsignedBigInteger("next_on_no")->nullable();
            $table->unsignedBigInteger("answer_no")->nullable();
            $table->unsignedBigInteger("answer_yes")->nullable();
            $table->timestamps();
        });

        Schema::table("questions", function (Blueprint $table) {
            $table->foreign("next_on_yes")->references("id")->on("questions");
            $table->foreign("next_on_no")->references("id")->on("questions");
            $table->foreign("answer_yes")->references("id")->on("service_packs");
            $table->foreign("answer_no")->references("id")->on("service_packs");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
};
