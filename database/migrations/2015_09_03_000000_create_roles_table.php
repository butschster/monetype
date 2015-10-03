<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{

	public function up()
	{
		Schema::create('roles', function (Blueprint $table) {
			$table->increments('id');

			$table->string('name', 32)->unique();
			$table->string('description');
			$table->timestamps();
		});


		Schema::create('role_user', function (Blueprint $table) {
			$table->unsignedInteger('role_id');
			$table->unsignedInteger('user_id');

			$table->primary(['role_id', 'user_id']);
		});
	}


	public function down()
	{
		Schema::dropIfExists('roles');
		Schema::dropIfExists('role_user');
	}
}
