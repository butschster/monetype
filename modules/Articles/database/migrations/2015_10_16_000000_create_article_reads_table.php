<?php

use Modules\Articles\Model\Article;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleReadsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('article_reads', function(Blueprint $table)
		{
			$table->unsignedInteger('article_id')->index();
			$table->unsignedInteger('user_id')->index();

			$table->timestamps();

			$table->foreign('article_id')
				->references('id')
				->on('articles')
				->onDelete('cascade');

			$table->foreign('user_id')
				->references('id')
				->on('users')
				->onDelete('cascade');

			$table->unique(['article_id', 'user_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('article_reads');
	}

}
