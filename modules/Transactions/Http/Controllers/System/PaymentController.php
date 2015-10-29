<?php

namespace Modules\Transactions\Http\Controllers\System;

use Modules\Transactions\Model\TransactionGateway;
use Modules\Core\Http\Controllers\System\Controller;

abstract class PaymentController extends Controller
{

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel()
    {
        /** @var TransactionGateway $transaction */
        $transaction = $this->session->get('transaction');

        if ( ! is_null($transaction)) {
            $this->session->forget('transaction');
            $transaction->cancel();
        }

        return $this->errorRedirect(
            trans('transactions::transaction.message.transaction_canceled'),
            route('front.profile.me')
        );
    }
}
