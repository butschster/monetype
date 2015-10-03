<?php

use Modules\Users\Model\User;
use Modules\Users\Model\Role;
use Illuminate\Database\Seeder;
use Modules\Transactions\Model\Account;

class UsersTableSeeder extends Seeder
{

    public function run()
    {
        User::truncate();
        Account::truncate();

        $administrator = User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@site.com',
            'password' => bcrypt('password'),
            'gender'   => 'male',
            'status'   => User::STATUS_APPROVED,
        ]);

        $administrator->assignRole(Role::ROLE_ADMIN);

        factory(User::class, 'user', 100)->create();
    }
}