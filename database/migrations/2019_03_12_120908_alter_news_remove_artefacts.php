<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNewsRemoveArtefacts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropIndex('actual');
            $table->dropColumn('serieID');
            $table->dropColumn('episodeID');
            $table->dropColumn('rushHourNews');
            $table->dropColumn('onMainPagePosition');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->integer('serieID')->nullable();
            $table->integer('episodeID')->nullable();
            $table->boolean('rushHourNews')->default(0);
            $table->smallInteger('onMainPagePosition')->default(-1)->index('actual');
        });
    }
}
