<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDiplomeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'candidat_id' => 'required|exists:candidats,id',
            
            // BAC+2 Information
            'type_diplome_bac_2' => 'required|string|max:100',
            'annee_diplome_bac_2' => 'required' ,
            'filiere_diplome_bac_2' => 'required|string|max:100',
            'etablissement_bac_2' => 'required|string|max:100',
            'scan_bac_2' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            
            // BAC+3 Information (for Master)
            'type_diplome_bac_3' => 'nullable|string|max:100',
            'annee_diplome_bac_3' => 'nullable',
            'filiere_diplome_bac_3' => 'nullable|string|max:100',
            'etablissement_bac_3' => 'nullable|string|max:100',
            'scan_bac_3' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Le champ :attribute est obligatoire.',
            'max' => 'Le champ :attribute ne doit pas dépasser :max caractères.',
            'file.max' => 'Le fichier ne doit pas dépasser 10MB.',
            'mimes' =>'La scan de Diplome doit être au format jpg, jpeg ou png.',
        ];
    }
}