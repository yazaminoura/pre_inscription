<?php

// Premier administrateur, créé par la migration « create_default_admin » si la table users est vide.
// Les valeurs restent dans le .env : jamais dans le code, qui est public.
return [
    'nom' => env('ADMIN_NOM') ?: 'Administrateur',
    'email' => env('ADMIN_EMAIL'),
    'password' => env('ADMIN_PASSWORD'),
];
