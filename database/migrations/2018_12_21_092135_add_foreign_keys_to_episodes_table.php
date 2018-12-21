<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToEpisodesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('episodes', function (Blueprint $table) {
            $table->foreign('archive_id', 'fk_episodes_archive_id')->references('id')->on('archives');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('episodes', function (Blueprint $table) {
            $table->dropForeign('fk_episodes_archive_id');
        });
    }

}
