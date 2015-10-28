<?php

use Modules\Users\Model\User;
use Illuminate\Database\Seeder;
use Modules\Articles\Model\Article;
use Modules\Transactions\Model\Type;
use Modules\Transactions\Model\Status;
use Modules\Articles\Jobs\PurchaseArticle;
use Modules\Transactions\Model\Transaction;
use Modules\Transactions\Model\TransactionPayPal;

class TransactionsTableSeeder extends Seeder
{

    public function run()
    {
        Type::truncate();
        Status::truncate();
        Transaction::truncate();
        TransactionPayPal::truncate();

        Status::create([
            'name'  => Transaction::STATUS_NEW,
            'title' => 'Новая',
        ]);

        Status::create([
            'name'  => Transaction::STATUS_PROCESSING,
            'title' => 'В процессе',
        ]);

        Status::create([
            'name'  => Transaction::STATUS_FAILED,
            'title' => 'Не проведен',
        ]);

        Status::create([
            'name'  => Transaction::STATUS_REFUNDED,
            'title' => 'Возврат',
        ]);

        Status::create([
            'name'  => Transaction::STATUS_CANCELED,
            'title' => 'Отклонено',
        ]);

        Status::create([
            'name'  => Transaction::STATUS_COMPLETED,
            'title' => 'Проведена',
        ]);

        Type::create([
            'name'              => Transaction::TYPE_PAYMENT,
            'title'             => 'Платеж',
            'comission_percent' => 1,
        ]);

        Type::create([
            'name'  => Transaction::TYPE_ARTICLE_CHECK,
            'title' => 'Проверка статьи',
        ]);

        Type::create([
            'name'      => Transaction::TYPE_TRANSFER,
            'title'     => 'Перевод',
            'comission' => 5,
        ]);

        Type::create([
            'name'  => Transaction::TYPE_REFUND,
            'title' => 'Возврат',
        ]);

        Type::create([
            'name'  => Transaction::TYPE_CASHIN,
            'title' => 'Зачисление средств',
        ]);

        Type::create([
            'name'              => Transaction::TYPE_CASHOUT,
            'title'             => 'Вывод средств',
            'comission_percent' => 10,
        ]);

        Type::create([
            'name'      => Transaction::TYPE_COUPON,
            'title'     => 'Создание купона',
            'comission' => 5,
        ]);

        if ( ! App::environment('local')) {
            return;
        }

        $users    = User::where('id', '>', 3)->get();
        $articles = Article::all();

        $faker = \Faker\Factory::create();

        foreach (range(1, 500) as $i) {
            $user    = $faker->randomElement($users->all());
            $article = $faker->randomElement($articles->all());

            if ( ! is_null($user) and ! is_null($article)) {
                Bus::dispatch(new PurchaseArticle($article, $user));
            }
        }
    }
}