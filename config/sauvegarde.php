<?php

// Sauvegarde quotidienne : base de données + pièces des candidats + logo, dans un fichier .zip.
return [
    // Où ranger les .zip. Idéalement un autre disque ou un dossier synchronisé (OneDrive, Google Drive…)
    'dossier' => env('SAUVEGARDE_DOSSIER') ?: storage_path('app/sauvegardes'),

    // Nombre de sauvegardes gardées ; les plus anciennes sont supprimées
    'garder' => (int) (env('SAUVEGARDE_GARDER') ?: 14),

    // Chemin de mysqldump s'il n'est pas dans le PATH
    // (Windows : "C:\Program Files\MySQL\MySQL Server 8.4\bin\mysqldump.exe")
    'mysqldump' => env('SAUVEGARDE_MYSQLDUMP') ?: 'mysqldump',
];
