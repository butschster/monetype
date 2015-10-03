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

            $table->unsignedInteger('article_id')->nullable();

            $table->decimal('amount', 10, 2)->default(0);

            $table->text('details')->nullable();

            $table->unsignedInteger('type_id');
            $table->unsignedInteger('status_id');
            $table->unsignedInteger('payment_method_id');

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
