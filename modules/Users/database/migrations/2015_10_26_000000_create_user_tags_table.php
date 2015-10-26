<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_tags', function(Blueprint $table)
		{
			$table->unsignedInteger('user_id');
			$table->foreign('user_id')
				->references('id')
				->on('users')
				->onDelete('cascade');

			$table->unsignedInteger('tag_id');
			$table->foreign('tag_id')
				->references('id')
				->on('tags')
				->onDelete('cascade');

			$table->unique(['user_id', 'tag_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_tags');
	}

}
