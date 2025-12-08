<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$this->user->id,
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, $this->user->password)) {
                        $fail('Le mot de passe actuel est incorrect.');
                    }
                }
            ],
            'password' => 'nullable|string|min:8|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'current_password.required' => 'La confirmation par mot de passe est requise pour toute modification.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ];
    }
}