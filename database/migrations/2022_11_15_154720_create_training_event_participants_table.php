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
        Schema::create('training_event_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("event_id");
            $table->boolean("is_confirmed")->default(false);
            $table->timestamps();

            $table->foreign("user_id")->references("id")->on("users")
                ->onDelete("cascade")->onUpdate("cascade");

            $table->foreign("event_id")->references("id")->on("training_events")
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
        Schema::dropIfExists('training_event_participants');
    }
};
