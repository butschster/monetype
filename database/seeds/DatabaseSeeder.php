<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(PermissionsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(TagsTableSeeder::class);
        $this->call(ArticlesTableSeeder::class);
        $this->call(PaymentMethodsTableSeeder::class);
        $this->call(TransactionsTableSeeder::class);
        $this->call(CouponsTableSeeder::class);


        Model::reguard();
    }
}
