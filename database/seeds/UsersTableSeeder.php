<?php

use Endroid\QrCode\QrCode;
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

        File::cleanDirectory(public_path('avatars'));
        File::cleanDirectory(public_path('backgrounds'));

        File::copy(
            base_path('storage' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . '.gitignore'),
            public_path('avatars' . DIRECTORY_SEPARATOR . '.gitignore')
        );

        File::copy(
            base_path('storage' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . '.gitignore'),
            public_path('backgrounds' . DIRECTORY_SEPARATOR . '.gitignore')
        );

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

            $filename = $this->getnerateAvatar($user->email);
            $user->attachAvatar(
                new UploadedFile($filename, 'avatar.png', 'image/png', 0, 0, true)
            );
        });
    }


    /**
     * @param $string
     *
     * @return string
     */
    protected function getnerateAvatar($string)
    {
        $filename = public_path(uniqid() . '.png');

        (new QrCode())
            ->setText($string)
            ->setSize(100)
            ->setPadding(5)->setForegroundColor([
                'r' => 255,
                'g' => 255,
                'b' => 255,
                'a' => 0
            ])->setBackgroundColor([
                'r' => rand(0, 255),
                'g' => rand(0, 255),
                'b' => rand(0, 255),
                'a' => 0
            ])->save($filename);

        return $filename;
    }
}