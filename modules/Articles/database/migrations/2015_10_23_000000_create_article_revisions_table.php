<?php

use Modules\Articles\Model\Article;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleRevisionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('article_revisions', function(Blueprint $table)
		{
			$table->string('id');
			$table->unsignedInteger('article_id')->index();

			$table->longText('text_source');
			$table->longText('opcodes');
			$table->timestamps();
			$table->softDeletes();

			$table->primary('id');

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
		Schema::drop('article_revisions');
	}
}
