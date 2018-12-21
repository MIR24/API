<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToBroadcastTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('broadcasts', function(Blueprint $table)
		{
            $table->foreign('category_id', 'fk_broadcasts_category_id')->references('id')->on('categories');
            $table->foreign('channel_id', 'fk_broadcasts_channel_id')->references('id')->on('channel');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('broadcasts', function(Blueprint $table)
		{
			$table->dropForeign('fk_broadcasts_category_id');
            $table->dropForeign('fk_broadcasts_channel_id');
		});
	}

}
