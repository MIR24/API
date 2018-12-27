<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToNewsCountryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('news_country', function(Blueprint $table)
		{
			$table->foreign('country_id', 'news_country_ibfk_1')->references('id')->on('country')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('news_id', 'news_country_ibfk_2')->references('id')->on('news')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('news_country', function(Blueprint $table)
		{
			$table->dropForeign('news_country_ibfk_1');
			$table->dropForeign('news_country_ibfk_2');
		});
	}

}
