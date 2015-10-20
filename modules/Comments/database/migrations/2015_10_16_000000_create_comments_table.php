<?php

use Modules\Comments\Model\Comment;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('parent_id')->nullable();
            $table->integer('lft')->nullable();
            $table->integer('rgt')->nullable();
            $table->integer('depth')->nullable();

            $table->string('title')->nullable();
            $table->text('text');

            $table->unsignedInteger('author_id');
            $table->unsignedInteger('article_id');


            $table->enum('status', [Comment::STATUS_PUBLISHED, Comment::STATUS_SPAM, Comment::STATUS_BLOCKED])
                ->default(Comment::STATUS_PUBLISHED)
                ->index();

            $table->string('user_ip', 20);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('author_id')
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
        Schema::dropIfExists('comments');
    }
}