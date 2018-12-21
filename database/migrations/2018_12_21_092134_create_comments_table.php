<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('comments', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('entity_id');
			$table->string('name');
			$table->string('profile');
			$table->string('email')->nullable();
			$table->string('text');
			$table->dateTime('time')->default('0000-00-00 00:00:00');
			$table->boolean('type_id')->index('type');
			$table->primary(['id','entity_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('comments');
	}

}
