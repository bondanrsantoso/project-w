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
        Schema::table('training_event_participants', function (Blueprint $table) {
            $table->boolean("is_passed")->default(false);
            $table->integer("final_grade")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('training_event_participants', function (Blueprint $table) {
            $table->dropColumn("is_passed");
            $table->dropColumn("final_grade");
        });
    }
};
