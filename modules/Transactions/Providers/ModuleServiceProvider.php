<?php

namespace Modules\Transactions\Providers;

use Modules\Core\Providers\ServiceProvider;
use Modules\Transactions\Model\Transaction;
use Modules\Transactions\Observers\TransactionObserver;

class ModuleServiceProvider extends ServiceProvider
{

    public function boot()
    {
        Transaction::observe(new TransactionObserver);
    }

    public function register()
    {

    }
}