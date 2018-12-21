<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToNewsTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('news_tags', function(Blueprint $table)
		{
			$table->foreign('news_id', 'news_tags_ibfk_1')->references('id')->on('news')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('tag_id', 'news_tags_ibfk_2')->references('id')->on('tags')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('news_tags', function(Blueprint $table)
		{
			$table->dropForeign('news_tags_ibfk_1');
			$table->dropForeign('news_tags_ibfk_2');
		});
	}

}
