<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeForeignKeysInBroadcastsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('broadcasts')->update(["category_id" => null]);

        Schema::table('broadcasts', function (Blueprint $table) {
            $table->dropForeign('fk_broadcasts_category_id');

            $table->foreign('category_id', 'fk_broadcasts_category_tv_id')->references('id')->on('categories_tv');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('broadcasts')->update(["category_id" => null]);

        Schema::table('broadcasts', function (Blueprint $table) {
            $table->dropForeign('fk_broadcasts_category_tv_id');

            $table->foreign('category_id', 'fk_broadcasts_category_id')->references('id')->on('categories');
        });
    }
}
