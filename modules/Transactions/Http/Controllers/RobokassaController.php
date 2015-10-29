<?php

namespace Modules\Transactions\Http\Controllers;

use DB;
use Idma\Robokassa\Payment;
use Modules\Transactions\Model\TransactionRobokassa;
use Modules\Transactions\Http\Requests\PaymentRequest;
use Modules\Transactions\Http\Controllers\System\PaymentController;

class RobokassaController extends PaymentController
{

    /**
     * @return Payment
     */
    protected function getApiContext()
    {
        return new Payment(
            config('robokassa.login'),
            config('robokassa.password1'),
            config('robokassa.password2'),
            config('robokassa.test_mode')
        );
    }

    /**
     * @param PaymentRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function create(PaymentRequest $request)
    {
        return DB::transaction(function () use($request) {
            $amountTotal = $request->get('amount');

            $payment = $this->getApiContext();

            $transaction = new TransactionRobokassa;
            $transaction->assignRecipient($this->user);
            $transaction->amount = $amountTotal;
            $transaction->save();

            $payment
                ->setInvoiceId($transaction->id)
                ->setSum($amountTotal)
                ->setDescription("{$this->user->id}");

            return redirect($payment->getPaymentUrl());
        });
    }

    public function result()
    {
        $payment = $this->getApiContext();

        if ($payment->validateResult($this->request->all())) {

            /** @var TransactionRobokassa $transaction */
            $transaction = TransactionRobokassa::findOrFail($payment->getInvoiceId());

            if ($payment->getSum() == $transaction->amount) {
                $transaction->complete();
                return $payment->getSuccessAnswer();
            }
        }
    }


    public function success()
    {
        $payment = $this->getApiContext();

        if ($payment->validateResult($this->request->all())) {

            /** @var TransactionRobokassa $transaction */
            $transaction = TransactionRobokassa::findOrFail($payment->getInvoiceId());

            if ($payment->getSum() == $transaction->amount) {
                return $this->successRedirect(
                    trans('transactions::transaction.message.transaction_success', ['amount' => $transaction->amount]),
                    route('front.profile.me')
                );
            }

        }

        abort(500);
    }


    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel()
    {
        $payment = $this->getApiContext();

        if ($payment->validateResult($this->request->all())) {

            /** @var TransactionRobokassa $transaction */
            $transaction = TransactionRobokassa::findOrFail($payment->getInvoiceId());

            if ($payment->getSum() == $transaction->amount) {
                $transaction->cancel();

                return $this->errorRedirect(
                    trans('transactions::transaction.message.transaction_canceled'),
                    route('front.profile.me')
                );
            }
        }

        abort(500);
    }
}
