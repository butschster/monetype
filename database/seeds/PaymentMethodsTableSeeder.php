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
            'name'  => 'robokassa',
            'title' => 'Robokassa',
        ]);

        PaymentMethod::create([
            'name'  => 'paypal',
            'title' => 'PayPal',
        ]);

        PaymentMethod::create([
            'name'  => 'account',
            'title' => 'Счет',
        ]);
    }
}