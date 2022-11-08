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
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("issuer")->nullable();
            $table->string("year", 5);
            $table->text("description");
            $table->text("attachment_url")->nullable();
            $table->unsignedBigInteger("worker_id");
            $table->timestamps();

            $table->foreign("worker_id")->references("id")->on("workers")->onDelete("cascade")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('achievements');
    }
};
