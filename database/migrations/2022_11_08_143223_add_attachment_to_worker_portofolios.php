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
        Schema::table('worker_portofolios', function (Blueprint $table) {
            $table->text("attachment_url")->nullable();
            $table->string("title")->nullable()->change();
            $table->text("description")->nullable()->change();
            $table->text("link_url")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('worker_portofolios', function (Blueprint $table) {
            $table->dropColumn("attachment_url");
        });
    }
};
