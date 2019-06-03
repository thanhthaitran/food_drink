<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUsers extends FormRequest
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
            'name' => 'required|string|max:50',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:6|max:50',
            'address' => 'required',
            'phone' => 'required|numeric|min:10',
            'avatar' => 'image|mimes:png,jpg,jpeg',
        ];
    }
}
