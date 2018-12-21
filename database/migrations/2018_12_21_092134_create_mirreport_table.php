<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMirreportTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mirreport', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->dateTime('date')->nullable();
			$table->string('name');
			$table->string('profile');
			$table->text('title', 65535);
			$table->text('desc', 65535)->nullable();
			$table->string('email')->nullable();
			$table->string('filename')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('mirreport');
	}

}
