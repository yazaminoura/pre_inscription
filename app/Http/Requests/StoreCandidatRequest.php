<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCandidatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Personal Information
            'CNE' => 'required|string|max:20',
            'nom' => 'required|string|max:50',
            'prenom' => 'required|string|max:50',
            'nom_ar' => 'nullable|string|max:50',
            'prenom_ar' => 'nullable|string|max:50',
            'CIN' => 'required|string|max:20',
            'date_naissance' => 'required|date',
            'ville_naissance' => 'required|string|max:50',
            'ville_naissance_ar' => 'nullable|string|max:50',
            'province' => 'required|string|max:50',
            'pay_naissance' => 'required|string|max:50',
            'nationalite' => 'required|string|max:50',
            'sexe' => 'required|in:M,F',
            'telephone_mob' =>  ['required', 'regex:/^\+?\d{8,15}$/'],
            'telephone_fix' =>  ['nullable', 'regex:/^(\+212|0)([5-7])\d{8}$/'],
            'adresse' => 'required|string|max:255',
            'email' => 'required|email|max:100',
            
            // Academic Information
            'serie_bac' => 'required|string|max:50',
            'annee_bac' => 'required' ,
            
            // Files
            'CV' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB
            'demande' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'scan_cartid' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'photo' => 'required|file|mimes:jpg,jpeg,png|max:10240',
            'scan_bac' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ];
    }
public function messages()
{
    return [
        // CNE
        'CNE.required' => 'Le CNE est obligatoire.',
        'CNE.max' => 'Le CNE ne doit pas dépasser 20 caractères.',

        // Nom
        'nom.required' => 'Le nom est obligatoire.',
        'nom.max' => 'Le nom ne doit pas dépasser 50 caractères.',

        // Prénom
        'prenom.required' => 'Le prénom est obligatoire.',
        'prenom.max' => 'Le prénom ne doit pas dépasser 50 caractères.',

        // CIN
        'CIN.required' => 'Le CIN est obligatoire.',
        'CIN.max' => 'Le CIN ne doit pas dépasser 20 caractères.',

        // Date de naissance
        'date_naissance.required' => 'La date de naissance est obligatoire.',
        'date_naissance.date' => 'La date de naissance doit être une date valide.',

        // Ville de naissance
        'ville_naissance.required' => 'La ville de naissance est obligatoire.',
        'ville_naissance.max' => 'La ville de naissance ne doit pas dépasser 50 caractères.',

        // Province
        'province.required' => 'La province est obligatoire.',

        // Pay de naissance
        'pay_naissance.required' => 'Le pays de naissance est obligatoire.',

        // Nationalité
        'nationalite.required' => 'La nationalité est obligatoire.',

        // Sexe
        'sexe.required' => 'Le sexe est obligatoire.',
        'sexe.in' => 'Le sexe sélectionné est invalide.',

        // Téléphone
        'telephone_mob.required' => 'Le téléphone mobile est obligatoire.',
        'telephone_mob.regex' => 'Le champ Téléphone doit être un numéro de téléphone valide (8 à 15 chiffres, avec ou sans +).',

        'telephone_fix.regex' => 'Le champ Téléphone doit être un numéro marocain valide sans espaces (ex: +2126******** ou 07********).',

        // Adresse
        'adresse.required' => "L'adresse est obligatoire.",

        // Email
        'email.required' => "L'email est obligatoire.",
        'email.email' => "L'email doit être une adresse valide.",
        'email.max' => "L'email ne doit pas dépasser 100 caractères.",

        // Bac
        'serie_bac.required' => 'La série du bac est obligatoire.',

        'annee_bac.required' => "L'année du bac est obligatoire.",

        // Fichiers
        'CV.required' => 'Le CV est obligatoire.',
        'CV.mimes' => 'Le CV doit être un fichier de type : pdf, jpg, jpeg ou png.',
        'CV.max' => 'Le CV ne doit pas dépasser 10MB.',

        'demande.required' => 'La lettre de demande est obligatoire.',
        'demande.mimes' => 'La lettre de demande doit être un fichier de type : pdf, jpg, jpeg ou png.',
        'demande.max' => 'La lettre de demande ne doit pas dépasser 10MB.',

        'scan_cartid.required' => "Le scan de la carte d'identité est obligatoire.",
        'scan_cartid.mimes' => "Le fichier carte d'identité doit être de type : pdf, jpg, jpeg ou png.",
        'scan_cartid.max' => "Le scan de la carte d'identité ne doit pas dépasser 10MB.",

        'photo.required' => 'La photo est obligatoire.',
        'photo.mimes' => 'La photo doit être au format jpg, jpeg ou png.',
        'photo.max' => 'La photo ne doit pas dépasser 10MB.',

        'scan_bac.required' => 'Le scan du bac est obligatoire.',
        'scan_bac.mimes' => 'Le scan du bac doit être un fichier de type : pdf, jpg, jpeg ou png.',
        'scan_bac.max' => 'Le scan du bac ne doit pas dépasser 10MB.',
    ];
}


   
}