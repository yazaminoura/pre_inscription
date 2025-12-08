<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFormationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'type_formation' => ['required', 'string', 'in:Licence,Master'],
            'titre' => 'required|string|max:255',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'user_id' => 'required|exists:users,id'
        ];
    }

    public function messages()
    {
        return [
            'type_formation.required' => 'Le type de formation est obligatoire.',
            'type_formation.in' => 'Le type de formation doit être soit "licence" soit "master".',
            'titre.required' => 'Le titre est obligatoire.',
            'date_debut.required' => 'La date de début est obligatoire.',
            'date_debut.date' => 'La date de début doit être une date valide.',
            'date_fin.required' => 'La date de fin est obligatoire.',
            'date_fin.date' => 'La date de fin doit être une date valide.',
            'date_fin.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début.',
            'user_id.required' => 'L\'utilisateur est obligatoire.',
            'user_id.exists' => 'L\'utilisateur sélectionné est invalide.'
        ];
    }
}