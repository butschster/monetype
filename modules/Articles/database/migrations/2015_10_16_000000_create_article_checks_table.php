<?php

use Modules\Articles\Model\Article;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleChecksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('article_checks', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('article_id')->index();

			$table->float('percent');
			$table->string('error');
			$table->longText('text');

			$table->json('response');

			$table->timestamps();

			$table->foreign('article_id')
				->references('id')
				->on('articles')
				->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('article_checks');
	}

}
