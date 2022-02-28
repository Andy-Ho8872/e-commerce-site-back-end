<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreditCardRequest extends FormRequest
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
            'card_type' => 'string',
            'card_number' => 'numeric|max:16',
            'card_holder' => 'string',
            'card_expiration_date' => 'string',
            'card_CVV' => 'numeric|max:3'
        ];
    }
}
