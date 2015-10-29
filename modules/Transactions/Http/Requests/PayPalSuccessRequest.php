<?php

namespace Modules\Transactions\Http\Requests;

use App\Http\Requests\Request;

class PayPalSuccessRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'paymentId' => 'required',
            'PayerID'   => 'required',
        ];
    }


    /**
     * Set custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return trans('transactions::transaction.paypal.field');
    }
}
