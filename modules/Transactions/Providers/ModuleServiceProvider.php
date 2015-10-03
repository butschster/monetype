<?php

namespace Modules\Transactions\Providers;

use Modules\Transactions\Model\Transaction;
use Modules\Transactions\Observers\TransactionObserver;
use KodiCMS\ModulesLoader\Providers\ModuleServiceProvider as BaseServiceProvider;

class ModuleServiceProvider extends BaseServiceProvider
{

    public function boot()
    {
        Transaction::observe(new TransactionObserver);
    }
}