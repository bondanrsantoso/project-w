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
        Schema::create('service_pack_work_groups', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->unsignedBigInteger("service_pack_id");
            $table->timestamps();
        });

        Schema::table('service_pack_work_groups', function (Blueprint $table) {
            $table->foreign("service_pack_id")->references("id")->on("service_packs")->onDelete("cascade")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_pack_work_groups');
    }
};
