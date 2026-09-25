<?php

// Messages de validation en français (règles utilisées par l'application).
return [
    'accepted' => 'Vous devez cocher « :attribute ».',
    'after_or_equal' => 'Le champ :attribute doit être une date postérieure ou égale au :date.',
    'array' => 'Le champ :attribute doit être une liste.',
    'before' => 'Le champ :attribute doit être une date antérieure au :date.',
    'boolean' => 'Le champ :attribute doit être vrai ou faux.',
    'confirmed' => 'La confirmation du champ :attribute ne correspond pas.',
    'current_password' => 'Le mot de passe est incorrect.',
    'date' => 'Le champ :attribute n\'est pas une date valide.',
    'email' => 'Le champ :attribute doit être une adresse email valide.',
    'exists' => 'Le champ :attribute sélectionné est invalide.',
    'file' => 'Le champ :attribute doit être un fichier.',
    'image' => 'Le champ :attribute doit être une image.',
    'in' => 'Le champ :attribute sélectionné est invalide.',
    'integer' => 'Le champ :attribute doit être un nombre entier.',
    'max' => [
        'array' => 'Le champ :attribute ne peut pas contenir plus de :max éléments.',
        'file' => 'Le fichier :attribute ne doit pas dépasser :max Ko.',
        'numeric' => 'Le champ :attribute ne doit pas dépasser :max.',
        'string' => 'Le champ :attribute ne doit pas dépasser :max caractères.',
    ],
    'mimes' => 'Le fichier :attribute doit être de type : :values.',
    'min' => [
        'array' => 'Le champ :attribute doit contenir au moins :min éléments.',
        'file' => 'Le fichier :attribute doit faire au moins :min Ko.',
        'numeric' => 'Le champ :attribute doit être au moins :min.',
        'string' => 'Le champ :attribute doit contenir au moins :min caractères.',
    ],
    'numeric' => 'Le champ :attribute doit être un nombre.',
    'regex' => 'Le format du champ :attribute est invalide.',
    'required' => 'Le champ :attribute est obligatoire.',
    'required_with' => 'Le champ :attribute est obligatoire quand :values est renseigné.',
    'string' => 'Le champ :attribute doit être du texte.',
    'unique' => 'Ce :attribute est déjà utilisé.',
    'uploaded' => 'Le fichier :attribute n\'a pas pu être envoyé (trop volumineux ?).',
    'url' => 'Le champ :attribute doit être une adresse web valide (https://…).',

    'attributes' => [
        'name' => 'nom', 'email' => 'email', 'password' => 'mot de passe', 'titre' => 'intitulé',
        'type_formation' => 'type de formation', 'date_debut' => 'date d\'ouverture', 'date_fin' => 'date de clôture',
        'nom' => 'nom', 'prenom' => 'prénom', 'adresse' => 'adresse', 'ville' => 'ville', 'pays' => 'pays',
        'province' => 'province', 'photo' => 'photo', 'places' => 'nombre de places', 'duree' => 'durée',
    ],
];
