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
        Schema::create('training_event_benefits', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->text("description");
            $table->text("icon_url")->nullable();
            $table->unsignedBigInteger("event_id");
            $table->timestamps();

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
        Schema::dropIfExists('training_event_benefits');
    }
};
