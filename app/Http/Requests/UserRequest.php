<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        switch($this->method())
        {
            case 'PUT':
                return [
                'name' => 'required',
                'email' => 'required|email',
                'picture' => 'image|mimes:jpeg,jpg,png|image_aspect:1',
                'password' => 'confirmed',
            ];
            default:
            return [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . auth()->user()->id,
                'picture' => 'image|mimes:jpeg,jpg,png|image_aspect:1',
                'password' => 'confirmed',
            ];
        }

    }
}
