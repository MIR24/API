<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePushTokensTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('push_tokens', function(Blueprint $table)
		{
			$table->string('token');
			$table->enum('type', array('APN','GCM'))->default('GCM');
			$table->unique(['token','type'], 'token-type');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('push_tokens');
	}

}
