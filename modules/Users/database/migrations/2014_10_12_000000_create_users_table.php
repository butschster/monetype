<?php

use Modules\Users\Model\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->rememberToken();

            $table->enum('gender', ['male', 'female', 'other'])
                ->default('other');

            $table->enum('status', [User::STATUS_NEW, User::STATUS_APPROVED, User::STATUS_BLOCKED])
                ->default(User::STATUS_NEW)
                ->index();

            $table->text('block_reason');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
