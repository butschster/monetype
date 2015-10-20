<?php

namespace Modules\Comments\Http\Requests;

use App\Http\Requests\Request;

class CommentPostRequest extends Request
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
            'title' => 'max:255',
            'text'  => 'required|min:10',
        ];
    }


    /**
     * Set custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return trans('comments::comment.field');
    }
}