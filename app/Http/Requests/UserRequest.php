<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
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
