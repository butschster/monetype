<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tags', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 50)->index();
			$table->unsignedInteger('count')->default(0);
		});
		
		Schema::create('article_tag', function(Blueprint $table)
		{
			$table->unsignedInteger('article_id')->index();
			$table->unsignedInteger('tag_id')->index();
			$table->unique(['article_id', 'tag_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tags');
		Schema::drop('article_tag');
	}

}
