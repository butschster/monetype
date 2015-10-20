<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserFavoritesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_favorites', function(Blueprint $table)
		{
			$table->unsignedInteger('user_id');
			$table->unsignedInteger('article_id');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

			$table->foreign('article_id')
				->references('id')
				->on('articles')
				->onDelete('cascade');

			$table->unique(['user_id', 'article_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_favorites');
	}

}
