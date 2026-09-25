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
            'type_formation' => ['required', 'string', \Illuminate\Validation\Rule::in(config('etablissement.types_formation'))],
            'titre' => 'required|string|max:255',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'user_id' => 'required|exists:users,id',
            'description' => 'nullable|string|max:3000',
            'duree' => 'nullable|string|max:50',
            'places' => 'nullable|integer|min:1|max:10000',
            'conditions_acces' => 'nullable|string|max:3000',
            'modalites_selection' => 'nullable|string|max:3000',
            'debouches' => 'nullable|string|max:3000'
        ];
    }

    public function messages()
    {
        return [
            'type_formation.required' => 'Le type de formation est obligatoire.',
            'type_formation.in' => 'Ce type de formation n\'est pas proposé.',
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