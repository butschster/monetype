<?php

use Modules\Users\Model\User;
use Illuminate\Database\Seeder;
use Modules\Articles\Model\Article;
use Modules\Transactions\Model\Type;
use Modules\Transactions\Model\Status;
use Modules\Articles\Jobs\PurchaseArticle;
use Modules\Transactions\Model\Transaction;

class TransactionsTableSeeder extends Seeder
{

    public function run()
    {
        Type::truncate();
        Status::truncate();
        Transaction::truncate();

        Status::create([
            'name'  => 'new',
            'title' => 'Новая',
        ]);

        Status::create([
            'name'  => 'canceled',
            'title' => 'Отклонено',
        ]);

        Status::create([
            'name'  => 'completed',
            'title' => 'Проведена',
        ]);

        Type::create([
            'name'              => 'payment',
            'title'             => 'Платеж',
            'comission_percent' => 1,
        ]);

        Type::create([
            'name'  => 'article_check',
            'title' => 'Проверка статьи'
        ]);

        Type::create([
            'name'      => 'transfer',
            'title'     => 'Перевод',
            'comission' => 5,
        ]);

        Type::create([
            'name'  => 'refund',
            'title' => 'Возврат',
        ]);

        Type::create([
            'name'  => 'cashin',
            'title' => 'Зачисление средств',
        ]);

        Type::create([
            'name'              => 'cashout',
            'title'             => 'Вывод средств',
            'comission_percent' => 10,
        ]);

        Type::create([
            'name'      => 'coupon',
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