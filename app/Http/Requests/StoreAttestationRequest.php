<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttestationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
             'candidat_id' => 'required|exists:candidats,id',
            'attestation' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:2048'
            ],
            'description' => 'nullable|string|max:500',
            'type_attestation' => 'nullable|string',
        ];
    }
    public function messages()
    {
        return [
            'attestation.mimes' => 'Le fichier doit être de type: pdf, jpg, jpeg ou png.',
            'attestation.max' => 'Le fichier ne doit pas dépasser 2MB.',
            'type_attestation.in' => 'Le type d\'attestation sélectionné est invalide.',
        ];
    }
}
