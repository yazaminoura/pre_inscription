<?php

// Identité de l'établissement : tout se change ici ou dans le .env, pas dans les vues.
return [
    'nom' => env('ETAB_NOM', 'Faculté des Sciences et Techniques'),
    'nom_court' => env('ETAB_NOM_COURT', 'FST'),
    'ville' => env('ETAB_VILLE', 'Fès'),
    'site' => env('ETAB_SITE', 'https://fst-usmba.ac.ma'),
    'logo' => env('ETAB_LOGO', 'images/logo.png'),
    'couleur' => env('ETAB_COULEUR', '#096a9b'),
];
