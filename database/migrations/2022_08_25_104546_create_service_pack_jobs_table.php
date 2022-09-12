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
        Schema::create('service_pack_jobs', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("description");
            $table->integer("order")->default(0);
            $table->unsignedBigInteger("job_category_id");
            $table->unsignedBigInteger("workgroup_id");
            $table->timestamps();
        });

        Schema::table("service_pack_jobs", function (Blueprint $table) {
            $table->foreign("job_category_id")->references("id")->on("job_categories");
            $table->foreign("workgroup_id")->references("id")->on("service_pack_work_groups");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_pack_jobs');
    }
};
