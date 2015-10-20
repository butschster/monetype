<?php

use Modules\Users\Model\User;
use Modules\Users\Model\Coupon;
use Illuminate\Database\Seeder;
use Modules\Users\Model\CouponType;
use Modules\Users\Jobs\CreateCoupon;

class CouponsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Coupon::truncate();
        CouponType::truncate();

        CouponType::create([
            'name'  => 'user',
            'title' => 'Пользовательские'
        ]);

        CouponType::create([
            'name'  => 'register',
            'title' => 'Регистрационные'
        ]);

        User::where('id', '>', 3)->with([
            'account' => function ($query) {
                $query->where('balance', '>', 3);
            }
        ])->take(20)->orderByRaw('RAND()')->get()->each(function (User $user) {
            Bus::dispatch(new CreateCoupon($user, 10, 'user'));
        });

        for ($i = 20; $i > 0; $i--) {
            Bus::dispatch(new CreateCoupon(User::find(2), 10, 'register'));
        }
    }
}