<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use League\CommonMark\Parser\Block\BlockContinueParserInterface;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->text("description")->nullable();
            $table->integer("order")->default(0);
            $table->integer("budget");
            $table->string("status");
            $table->date("date_start");
            $table->date("date_end");
            $table->unsignedBigInteger("workgroup_id");
            $table->unsignedBigInteger("job_category_id");
            $table->softDeletes();
            $table->timestamps();

            $table->foreign("workgroup_id")->references("id")->on("workgroups")->onDelete("cascade");
            $table->foreign("job_category_id")->references("id")->on("job_categories")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
};
