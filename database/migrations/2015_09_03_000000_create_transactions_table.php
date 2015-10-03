<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('debit')->index();
            $table->unsignedInteger('credit')->index();

            $table->string('type_id');
            $table->string('status_id');
            $table->string('payment_method_id');
            $table->unsignedInteger('article_id')->nullable();

            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('comission', 10, 2)->default(0);

            $table->text('details')->nullable();

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
        Schema::drop('transactions');
    }

}
