<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('from_user_id');
            $table->unsignedInteger('to_user_id')->nullable();

            $table->string('type_id');

            $table->string('code');
            $table->decimal('amount', 10, 2);

            $table->timestamps();
            $table->date('expired_at')->nullable();
            $table->softDeletes();

            $table->unique('id');

            $table->foreign('type_id')
                ->references('name')
                ->on('coupon_types');

            $table->foreign('from_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('to_user_id')
                  ->references('id')
                  ->on('users')
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
        Schema::drop('coupons');
    }
}
