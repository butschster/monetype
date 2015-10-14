<?php

use Modules\Users\Model\User;
use Modules\Users\Model\Role;
use Illuminate\Database\Seeder;
use Modules\Transactions\Model\Account;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UsersTableSeeder extends Seeder
{

    public function run()
    {
        User::truncate();
        Account::truncate();

        $administrator = User::create([
            'username' => 'admin',
            'name'     => 'Administrator',
            'email'    => 'admin@site.com',
            'password' => bcrypt('password'),
            'gender'   => 'male',
            'status'   => User::STATUS_APPROVED,
        ]);

        $administrator->assignRole(Role::ROLE_ADMIN);
        $administrator->account->update(['balance' => 100]);

        User::create([
            'username' => bcrypt('credit'),
            'email'    => 'credit@site.com',
            'password' => bcrypt('password'),
            'gender'   => 'other',
            'status'   => User::STATUS_APPROVED,
        ]);

        User::create([
            'username' => bcrypt('debit'),
            'email'    => 'debit@site.com',
            'password' => bcrypt('password'),
            'gender'   => 'other',
            'status'   => User::STATUS_APPROVED,
        ]);

        factory(User::class, 'user', 100)->create()->each(function (User $user) {
            $user->account->update(['balance' => 100]);

            if (File::copy(
                public_path('img' . DIRECTORY_SEPARATOR . 'logo.png'),
                public_path('img' . DIRECTORY_SEPARATOR . 'avatar.png')
            )) {
                $user->attachAvatar(
                    new UploadedFile(public_path('img' . DIRECTORY_SEPARATOR . 'avatar.png'), 'avatar.png', 'image/png', 0, 0, true)
                );
            }
        });
    }
}