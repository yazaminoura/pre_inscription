<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreExperienceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'candidat_id' => 'required|exists:candidats,id',
            'fonction' => 'required|string',
            'secteur_activite' => 'required|string',
            'periode' => 'required',
            'attestation' => 'required|file|mimes:pdf,jpg,jpeg,png',
            'etablissement' => 'required|string',
            'description' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'candidat_id.required' => 'L\'identifiant du candidat est obligatoire.',
            'candidat_id.exists' => 'Le candidat sélectionné est invalide.',
            'fonction.required' => 'La fonction est obligatoire.',
            'secteur_activite.required' => 'Le secteur d\'activité est obligatoire.',
            'periode.required' => 'La période est obligatoire.',
            'attestation.required' => 'Le fichier d\'attestation est obligatoire.',
            'attestation.mimes' => 'Le fichier doit être de type PDF, JPG, JPEG ou PNG.',
            'etablissement.required' => 'L\'établissement est obligatoire.',
            'description.required' => 'La description est obligatoire.',
        ];
    }
}