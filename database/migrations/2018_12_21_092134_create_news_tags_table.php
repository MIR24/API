<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNewsTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('news_tags', function(Blueprint $table)
		{
			$table->integer('news_id')->index('news_id');
			$table->integer('tag_id')->index('tag_id');
			$table->unique(['news_id','tag_id'], 'primary_key');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('news_tags');
	}

}
