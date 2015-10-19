<?php

namespace Modules\Articles\Http\Requests;

use Modules\Core\Http\Requests\ApiRequest;

class StoreArticleRequest extends ApiRequest
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
            'title'       => 'required|max:255',
            'text_source' => 'required|min:500',
            'tags'        => 'required|mintags:3'
        ];
    }


    /**
     * Set custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return trans('articles::article.field');
    }
}
