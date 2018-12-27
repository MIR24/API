<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNewsCountryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('news_country', function(Blueprint $table)
		{
			$table->integer('news_id')->index('news_id');
			$table->integer('country_id')->index('country_id');
			$table->unique(['news_id','country_id'], 'news_country');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('news_country');
	}

}
