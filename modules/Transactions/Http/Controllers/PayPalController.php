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
use Modules\Transactions\Http\Requests\PayPalRequest;
use Modules\Core\Http\Controllers\System\FrontController;

class PayPalController extends FrontController
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

    public function send()
    {
        return DB::transaction(function () {
            $payer = (new Payer)->setPaymentMethod("paypal");

            $amountTotal = 100;

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

            $approvalUrl = $response->getApprovalLink();

            return redirect($approvalUrl);
        });
    }


    /**
     * @param PayPalRequest $payPalRequest
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function success(PayPalRequest $payPalRequest)
    {
        $paymentId = $payPalRequest->get('paymentId');
        $payerID = $payPalRequest->get('PayerID');

        $payment = Payment::get($paymentId, $this->getApiContext());

        $execution = new PaymentExecution;
        $execution->setPayerId($payerID);

        $transaction = new Transaction;
        $amount = new Amount;
        $details = new Details;

        $amount->setCurrency('RUB');
        $amount->setTotal(100);
        $amount->setDetails($details);
        $transaction->setAmount($amount);

        $execution->addTransaction($transaction);

        try {
            $result = $payment->execute($execution, $this->getApiContext());
        } catch (PayPalConnectionException $pce) {
            abort(500);
        }

        /** @var TransactionPayPal $transaction */
        $transaction = TransactionPayPal::where('payment_id', $result->getId())->firstOrFail();
        $transaction->complete();

        return redirect()->route('profile.showById');
    }


    public function cancel()
    {
        return redirect()->route('profile.showById');
    }
}