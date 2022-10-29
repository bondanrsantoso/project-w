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
        Schema::create('questionnaire_suggestions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("session_id");
            $table->unsignedBigInteger("service_pack_id");
            $table->timestamps();

            $table->foreign("session_id")->references("id")->on("questionnaire_sessions");
            $table->foreign("service_pack_id")->references("id")->on("service_packs");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questionnaire_suggestions');
    }
};
