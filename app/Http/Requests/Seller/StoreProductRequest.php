<?php

namespace App\Http\Requests\Seller;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (auth()->check() && auth()->user()->role === 'seller');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image_primary' => ['required','image','mimes:jpeg,jpg,png,svg','max:2048'],
            'images.*'  => ['image','mimes:jpeg,jpg,png,svg','max:2048'],
            'name' => ['required', 'unique:products,name','max:255'],
        ];
    }
}
