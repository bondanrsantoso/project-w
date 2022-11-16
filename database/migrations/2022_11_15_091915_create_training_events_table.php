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
        Schema::create('training_events', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->text("description");
            $table->dateTime("start_date");
            $table->dateTime("end_date");
            $table->string("location");
            $table->integer("sessions")->default(1);
            $table->integer("seat")->nullable();
            $table->unsignedBigInteger("category_id");
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("category_id")->references("id")->on("job_categories")
                ->onDelete("cascade")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('training_events');
    }
};
