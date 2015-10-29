<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Transactions\Model\TransactionRobokassa;

class CreateTransactionsRobokassaTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions_robokassa', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id');
            $table->unsignedInteger('transaction_id');
            $table->decimal('amount', 10, 2)->default(0);

            $table->string('payment_id');
            $table->enum('status', [
                TransactionRobokassa::STATUS_NEW,
                TransactionRobokassa::STATUS_COMPLETED,
                TransactionRobokassa::STATUS_CANCELED
            ])->default(TransactionRobokassa::STATUS_NEW);

            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users');

            $table->foreign('transaction_id')
                ->references('id')
                ->on('transactions');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('transactions_robokassa');
    }

}
