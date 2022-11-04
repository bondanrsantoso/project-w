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
        Schema::table('companies', function (Blueprint $table) {
            $table->string("website")->nullable();
            $table->string("category")->nullable();
            $table->integer("company_size_min")->nullable();
            $table->integer("company_size_max")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn("website");
            $table->dropColumn("category");
            $table->dropColumn("company_size_min");
            $table->dropColumn("company_size_max");
        });
    }
};
