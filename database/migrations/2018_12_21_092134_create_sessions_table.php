<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSessionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sessions', function(Blueprint $table)
		{
			$table->string('token', 32)->index('index_token');
			$table->timestamp('created_time')->default(DB::raw('CURRENT_TIMESTAMP'))->index('time_index');
			$table->boolean('provider')->nullable()->index('provider_id');
			$table->string('social_token')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sessions');
	}

}
