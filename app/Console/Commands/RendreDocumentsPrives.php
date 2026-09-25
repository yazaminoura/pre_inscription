<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class RendreDocumentsPrives extends Command
{
    protected $signature = 'documents:rendre-prives';

    protected $description = 'Déplace les pièces des candidats du dossier public vers le stockage privé (à lancer une fois après la mise à jour)';

    // Dossiers où l'ancienne version rangeait les pièces des candidats
    private const DOSSIERS = ['CV', 'demande', 'cart', 'photos', 'bac', 'bac_2', 'bac_3', 'stages', 'experiences', 'attestations'];

    public function handle(): int
    {
        $public = Storage::disk('public');
        $prive = Storage::disk('dossiers');
        $deplaces = 0;

        foreach (self::DOSSIERS as $dossier) {
            foreach ($public->allFiles($dossier) as $fichier) {
                if (!$prive->exists($fichier)) {
                    $prive->writeStream($fichier, $public->readStream($fichier));
                }
                $public->delete($fichier);
                $deplaces++;
            }
            $public->deleteDirectory($dossier);
        }

        $this->info("$deplaces fichier(s) déplacé(s) vers le stockage privé.");

        return self::SUCCESS;
    }
}
