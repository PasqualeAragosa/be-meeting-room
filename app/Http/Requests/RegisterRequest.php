<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|min:4',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed|min:8|max:12|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
        ];
    }

    public function messages()
    {
        return [
            'password.regex' => 'La password deve contenere almeno una lettera maiuscola, minuscola, un numero e un carattere speciale tra (@$!%*?&)'
        ];
    }
}
