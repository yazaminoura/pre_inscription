<?php

// Valeurs par défaut, neutres. L'établissement saisit les siennes dans l'administration
// (menu Paramètres > Établissement) : elles sont stockées en base et remplacent celles-ci.
return [
    'nom' => env('ETAB_NOM', 'Votre établissement'),
    'sigle' => env('ETAB_SIGLE'),
    'ville' => env('ETAB_VILLE'),
    'pays' => env('ETAB_PAYS'),
    'adresse' => null,
    'telephone' => null,
    'email' => null,
    'site' => env('ETAB_SITE'),
    'slogan' => null,
    'presentation' => null,
    'logo' => env('ETAB_LOGO', 'images/logo.png'),
    'couleur' => env('ETAB_COULEUR', '#096a9b'),
];
