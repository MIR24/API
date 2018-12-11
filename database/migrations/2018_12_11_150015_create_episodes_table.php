<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEpisodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('episodes', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title');
            $table->text('poster');
            $table->integer('season');
            $table->year('year');
            $table->dateTime('time_begin');
            $table->dateTime('time_end');
            $table->text('url');
            $table->integer('archive_id')->unsigned()->nullable();
            $table->foreign('archive_id')->references('id')->on('archives');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('episodes');
    }
}
