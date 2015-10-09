<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{

	public function up()
	{
		Schema::create('permissions', function (Blueprint $table) {
			$table->increments('id');

			$table->string('name', 32)->unique();
			$table->string('description');
			$table->timestamps();
		});

		Schema::create('permission_role', function (Blueprint $table) {
			$table->unsignedInteger('permission_id');
			$table->unsignedInteger('role_id');

			$table->primary(['permission_id', 'role_id']);

			$table->foreign('permission_id')
				->references('id')
				->on('permissions')
				->onDelete('cascade');

			$table->foreign('role_id')
				->references('id')
				->on('roles')
				->onDelete('cascade');
		});
	}


	public function down()
	{
		Schema::dropIfExists('permissions');
		Schema::dropIfExists('permission_role');
	}
}
