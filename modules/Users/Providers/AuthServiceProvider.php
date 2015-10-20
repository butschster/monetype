<?php

namespace Modules\Users\Providers;

use Schema;
use Modules\Users\Model\User;
use Modules\Users\Model\Coupon;
use Modules\Users\Model\Permission;
use Modules\Users\Observers\UserObserver;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [];


    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate $gate
     *
     * @return void
     */
    public function boot(GateContract $gate)
    {
        parent::registerPolicies($gate);

        User::observe(new UserObserver);

        $gate->define('delete-coupon', function (User $user, Coupon $coupon) {
            return $user->id === $coupon->from_user_id;
        });

        if (Schema::hasTable('permission')) {
            // Dynamically register permissions with Laravel's Gate.
            foreach ($this->getPermissions() as $permission) {
                $gate->define($permission->name, function (User $user) use ($permission) {
                    return $user->hasPermission($permission);
                });
            }
        }
    }


    /**
     * Fetch the collection of site permissions.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getPermissions()
    {
        return Permission::with('roles')->get();
    }
}