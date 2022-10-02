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
        Schema::create('workers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id');
            $table->string('address');
            $table->string('place_of_birth');
            $table->date('date_of_birth');
            $table->string('gender');
            $table->string('account_number');
            $table->float('balance');
            $table->timestamps();
            $table->foreign("user_id")->references("id")->on("users");
            $table->foreign("category_id")->references("id")->on("work_categories");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workers');
    }
};
