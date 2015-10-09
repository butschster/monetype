<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title');
            $table->string('slug')->unique()->index();

            $table->integer('count_articles')->default(0);
            $table->decimal('publish_cost')->default(0.05);

            $table->timestamps();
        });

        Schema::create('article_category', function (Blueprint $table) {
            $table->unsignedInteger('article_id');

            $table->unsignedInteger('category_id');

            $table->unique(['article_id', 'category_id']);

            $table->foreign('article_id')
                ->references('id')
                ->on('articles')
                ->onDelete('cascade');

            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
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
        Schema::drop('categories');
    }

}