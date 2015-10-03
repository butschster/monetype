<?php

use Illuminate\Database\Seeder;
use Modules\Transactions\Model\PaymentMethod;

class PaymentMethodsTableSeeder extends Seeder
{

    public function run()
    {
        PaymentMethod::truncate();

        PaymentMethod::create([
            'name'  => 'cash',
            'title' => 'Наличные',
        ]);

        PaymentMethod::create([
            'name'  => 'robocassa',
            'title' => 'Robocassa',
        ]);

        PaymentMethod::create([
            'name'  => 'account',
            'title' => 'Счет',
        ]);
    }
}