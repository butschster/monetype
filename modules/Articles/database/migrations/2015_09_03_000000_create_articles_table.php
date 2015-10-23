<?php

use Modules\Articles\Model\Article;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('articles', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('title');

			$table->text('text_intro');
			$table->text('text');
			$table->string('read_more_text')->default('');
			$table->text('text_source');

			$table->boolean('disable_comments')->default(0);
			$table->boolean('disable_stat_views')->default(0);
			$table->boolean('disable_stat_pays')->default(0);

			$table->text('tags_list')->comment('tags separated by a comma');

			$table->unsignedInteger('author_id')->index();
			$table->unsignedInteger('approver_id')->index()->nullable();
			$table->unsignedInteger('bocked_by_id')->index()->nullable();

			$table->decimal('cost', 10, 2)->default(0)->index();
			$table->decimal('amount', 10, 2)->default(0)->index();

			$table->unsignedInteger('count_payments')->default(0);
			$table->unsignedInteger('count_favorites')->default(0);
			$table->unsignedInteger('count_comments')->default(0);
			$table->unsignedInteger('reading_time')->default(0);

			$table->enum('status', [Article::STATUS_DRAFT, Article::STATUS_PUBLISHED, Article::STATUS_APPROVED, Article::STATUS_BLOCKED])
				->default(Article::STATUS_DRAFT)
				->index();

			$table->text('block_reason');

			$table->boolean('need_check')->default(true);

			$table->string('user_ip', 20);

			$table->timestamps();
			$table->softDeletes();
			$table->dateTime('published_at')->nullable()->index();

			$table->foreign('author_id')
				->references('id')
				->on('users');

			$table->foreign('approver_id')
				->references('id')
				->on('users');

			$table->foreign('bocked_by_id')
				->references('id')
				->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('articles');
	}

}
