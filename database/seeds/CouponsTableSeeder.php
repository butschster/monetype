<?php

use Modules\Users\Model\User;
use Modules\Users\Model\Coupon;
use Illuminate\Database\Seeder;
use Modules\Users\Jobs\ApplyCoupon;
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

        User::where('id', '>', 3)->with(['account' => function($query) {
            $query->where('balance', '>', 3);
        }])->take(10)->orderByRaw('RAND()')->get()->each(function(User $user) {
            Bus::dispatch(new CreateCoupon($user, rand(1, 2)));
        });

        User::where('id', '>', 3)->take(10)->orderByRaw('RAND()')->get()->each(function(User $user) {
            Bus::dispatch(new ApplyCoupon($user, Coupon::first()));
        });
    }
}