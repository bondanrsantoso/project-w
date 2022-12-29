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
        Schema::table('public_companies', function (Blueprint $table) {
            $table->bigInteger("revenue")->nullable()->default(null)->change();
            $table->bigInteger("assets")->nullable();
            $table->string("assets_scale", 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('public_companies', function (Blueprint $table) {
            $table->bigInteger("revenue")->nullable(false)->default(0)->change();
            $table->dropColumn("assets");
            $table->dropColumn("assets_scale", 50);
        });
    }
};
