<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAttestationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'candidat_id' => 'required|exists:candidats,id',
            'attestation' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'description' => 'required|string',
            'type_attestation' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'candidat_id.required' => 'L\'identifiant du candidat est obligatoire.',
            'candidat_id.exists' => 'Le candidat sélectionné est invalide.',
            'attestation.mimes' => 'Le fichier doit être de type PDF, JPG, JPEG ou PNG.',
            'description.required' => 'La description est obligatoire.',
            'type_attestation.required' => 'Le type d\'attestation est obligatoire.',
        ];
    }
}