<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('queue')->default('default');
            $table->jsonb('payload')->nullable();
            $table->jsonb('report')->nullable();
            $table->string('state');
            $table->integer('progress')->nullable();
            $table->string('command');
            $table->smallInteger('attempts')->unsigned()->default(0);
            $table->integer('created_by')->unsigned()->nullable();
            $table->dateTime('created_at');
            $table->dateTime('finished_at')->nullable();
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
}
