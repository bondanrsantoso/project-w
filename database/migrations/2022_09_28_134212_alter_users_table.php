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
        try {
            Schema::table("users", function (Blueprint $table) {
                $table->dropColumn("rating");
                $table->dropColumn("type");

                $table->string("username")->after("email")->unique();
            });
        } catch (\Throwable $th) {
            $this->down();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("users", function (Blueprint $table) {
            // $table->enum('type', ['admin', 'worker', 'customer'])->default("customer");
            // $table->float('rating')->default(0);

            // $table->dropColumn("username");
        });
    }
};
