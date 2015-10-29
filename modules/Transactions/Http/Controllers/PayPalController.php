<?php

namespace Modules\Transactions\Http\Controllers;

use DB;
use PayPal\Api\Item;
use PayPal\Api\Payer;
use PayPal\Api\Amount;
use PayPal\Api\Payment;
use PayPal\Api\Details;
use PayPal\Api\ItemList;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use PayPal\Api\RedirectUrls;
use PayPal\Api\PaymentExecution;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use Modules\Transactions\Model\TransactionPayPal;
use Modules\Transactions\Http\Requests\PaymentRequest;
use Modules\Transactions\Http\Controllers\System\PaymentController;

class PayPalController extends PaymentController
{

    /**
     * @return ApiContext
     */
    protected function getApiContext()
    {
        return new ApiContext(
            new OAuthTokenCredential(
                config('paypal.client_id'), config('paypal.client_secret')
            )
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
            $payer = (new Payer)->setPaymentMethod("paypal");

            $amountTotal = $request->get('amount');

            $itemList = new ItemList;
            $itemList->setItems([
                (new Item)->setCurrency("RUB")->setName('Виртуальные деньги')->setQuantity(1)->setPrice($amountTotal)
            ]);

            $details = new Details;

            $amount = new Amount;
            $amount
                ->setCurrency("RUB")
                ->setTotal($amountTotal)
                ->setDetails($details);

            $transaction = new Transaction;
            $transaction
                ->setAmount($amount)
                ->setItemList($itemList)
                ->setDescription("Пополнение счета клиента [{$this->user->id} : {$this->user->getName()}]");

            $redirectUrls = new RedirectUrls;
            $redirectUrls
                ->setReturnUrl(route('payments.paypal.success'))
                ->setCancelUrl(route('payments.paypal.cancel'));

            $payment = new Payment;
            $payment
                ->setIntent("sale")
                ->setPayer($payer)
                ->setRedirectUrls($redirectUrls)
                ->setTransactions([$transaction]);

            try {
                $response = $payment->create($this->getApiContext());
            } catch (PayPalConnectionException $pce) {
                abort(500);
            }

            $transaction = new TransactionPayPal;
            $transaction->assignRecipient($this->user);
            $transaction->amount = $amountTotal;
            $transaction->payment_id = $response->getId();
            $transaction->save();

            $this->session->put('transaction', $transaction);

            return redirect($response->getApprovalLink());
        });
    }


    /**
     * @param PayPalSuccessRequest $payPalRequest
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function success(PayPalSuccessRequest $payPalRequest)
    {
        $paymentId = $payPalRequest->get('paymentId');
        $payerID = $payPalRequest->get('PayerID');

        /** @var TransactionPayPal $payPalTransaction */
        $payPalTransaction = $this->session->get('transaction');
        if (is_null($payPalTransaction)) {
            abort(500);
        }

        $payment = Payment::get($paymentId, $this->getApiContext());

        $execution = new PaymentExecution;
        $execution->setPayerId($payerID);

        $transaction = new Transaction;
        $amount = new Amount;
        $details = new Details;

        $amount->setCurrency('RUB');
        $amount->setTotal($payPalTransaction->amount);
        $amount->setDetails($details);
        $transaction->setAmount($amount);

        $execution->addTransaction($transaction);

        try {
            $payment->execute($execution, $this->getApiContext());
        } catch (PayPalConnectionException $pce) {
            abort(500);
        }

        $payPalTransaction->complete();
        $this->session->forget('transaction');

        return $this->successRedirect(
            trans('transactions::transaction.message.transaction_success', ['amount' => $payPalTransaction->amount]),
            route('front.profile.me')
        );
    }
}