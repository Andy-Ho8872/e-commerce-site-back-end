<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    // public function authorize()
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string' ,
            'unit_price' => 'required|numeric' ,
            'imgUrl' => 'required|url' ,
            'stock_quantity' => 'required|integer' ,
            'available' => 'boolean' ,
            'discount_rate' => 'numeric|min:0.1|max:1' ,
            'rating' => 'numeric|min:0|max:5' ,
        ];
    }
}
