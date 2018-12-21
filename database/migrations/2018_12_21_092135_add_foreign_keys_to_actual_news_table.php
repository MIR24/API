<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToActualNewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('actual_news', function(Blueprint $table)
		{
			$table->foreign('news_id', 'actual_news_ibfk_1')->references('id')->on('news')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('actual_news', function(Blueprint $table)
		{
			$table->dropForeign('actual_news_ibfk_1');
		});
	}

}
