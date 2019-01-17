<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

# TODO Use this table? If use, then move data in cache and remove table?
class CreateNewsCountsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('news_counts', function(Blueprint $table)
		{
			$table->integer('rubric_id')->unique('rubric');
			$table->integer('count');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('news_counts');
	}

}
