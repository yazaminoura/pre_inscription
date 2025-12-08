<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDiplomeRequest extends FormRequest
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
            'annee_diplome_bac_2' => 'required',
            'filiere_diplome_bac_2' => 'required|string|max:100',
            'etablissement_bac_2' => 'required|string|max:100',
            'scan_bac_2' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
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
            'candidat_id.required' => 'L\'identifiant du candidat est obligatoire.',
            'candidat_id.exists' => 'Le candidat sélectionné est invalide.',
            'type_diplome_bac_2.required' => 'Le type de diplôme BAC+2 est obligatoire.',
            'type_diplome_bac_2.max' => 'Le type de diplôme BAC+2 ne doit pas dépasser 100 caractères.',
            'annee_diplome_bac_2.required' => 'L’année du diplôme BAC+2 est obligatoire.',
            'filiere_diplome_bac_2.required' => 'La filière du diplôme BAC+2 est obligatoire.',
            'filiere_diplome_bac_2.max' => 'La filière du diplôme BAC+2 ne doit pas dépasser 100 caractères.',
            'etablissement_bac_2.required' => 'L’établissement du diplôme BAC+2 est obligatoire.',
            'etablissement_bac_2.max' => 'L’établissement du diplôme BAC+2 ne doit pas dépasser 100 caractères.',
            'scan_bac_2.mimes' => 'Le scan du diplôme BAC+2 doit être au format pdf, jpg, jpeg ou png.',
            'scan_bac_2.max' => 'Le scan du diplôme BAC+2 ne doit pas dépasser 10MB.',
            'type_diplome_bac_3.max' => 'Le type de diplôme BAC+3 ne doit pas dépasser 100 caractères.',
            'filiere_diplome_bac_3.max' => 'La filière du diplôme BAC+3 ne doit pas dépasser 100 caractères.',
            'etablissement_bac_3.max' => 'L’établissement du diplôme BAC+3 ne doit pas dépasser 100 caractères.',
            'scan_bac_3.mimes' => 'Le scan du diplôme BAC+3 doit être au format pdf, jpg, jpeg ou png.',
            'scan_bac_3.max' => 'Le scan du diplôme BAC+3 ne doit pas dépasser 10MB.',
        ];
    }
}