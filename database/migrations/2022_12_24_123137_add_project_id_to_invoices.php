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
        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedBigInteger("project_id")->nullable();
            $table->unsignedBigInteger("job_id")->nullable()->change();

            $table->foreign("project_id")->references("id")->on("projects")->onDelete("cascade")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn("project_id");
            $table->unsignedBigInteger("job_id")->nullable(false)->change();
        });
    }
};
