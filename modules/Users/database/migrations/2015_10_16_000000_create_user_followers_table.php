<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserFollowersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_followers', function(Blueprint $table)
		{
			$table->unsignedInteger('user_id');
			$table->foreign('user_id')
				->references('id')
				->on('users')
				->onDelete('cascade');

			$table->unsignedInteger('follower_id');
			$table->foreign('follower_id')
				->references('id')
				->on('users')
				->onDelete('cascade');

			$table->unique(['user_id', 'follower_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_followers');
	}

}
