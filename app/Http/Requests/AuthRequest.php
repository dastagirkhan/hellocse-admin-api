<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * (Set to true as we are handling authentication)
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Define validation rules.
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:administrators',
            'password' => 'required|min:8',
        ];
    }
}
