<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['email', 'required', 'unique:users','max:255'],
            'login' => ['required','string'],
            'password' => ['required', 'min:6', 'max:255', 'confirmed'],
            'image' => ['extensions:jpg, jpeg, png'],
        ];
    }
}
