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
        Schema::create('public_companies', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->string("owner_name")->nullable();
            $table->string("province")->nullable();
            $table->string("city")->nullable();
            $table->text("address")->nullable();
            $table->string("district")->nullable();
            $table->bigInteger("revenue")->default(0);
            $table->string("type")->nullable();
            $table->string("scale")->nullable();
            $table->integer("data_year");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('public_companies');
    }
};
