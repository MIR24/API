<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('news', function(Blueprint $table)
		{
			$table->integer('id')->unique('id');
			$table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP'))->index('date');
			$table->string('title');
			$table->text('shortText', 65535)->nullable();
			$table->text('shortTextSrc', 65535)->nullable();
			$table->text('text');
			$table->text('textSrc');
			$table->integer('imageID')->nullable();
			$table->integer('categoryID')->index('category');
			$table->integer('serieID')->nullable();
			$table->integer('videoID')->nullable()->index('video');
			$table->integer('episodeID')->nullable(); // TODO Delete
			$table->text('copyright')->nullable();
			$table->text('copyrightSrc')->nullable();
			$table->boolean('rushHourNews')->default(0);
			$table->boolean('topListNews')->default(0);
			$table->boolean('hasGallery')->default(0)->index('gallery');
			$table->boolean('published')->default(0);
			$table->smallInteger('onMainPagePosition')->default(-1)->index('actual'); // TODO delete
			$table->string('videoDuration', 12)->nullable()->default('00:00:00.00');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('news');
	}

}
