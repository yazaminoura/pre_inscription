<?php

namespace App\Console\Commands;

use App\Models\Attestation;
use App\Models\Candidat;
use App\Models\Diplome;
use App\Models\Experience;
use App\Models\Stage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class NettoyerDossiers extends Command
{
    protected $signature = 'dossiers:nettoyer {--heures=48 : âge minimum d\'un fichier orphelin avant suppression} {--simulation : affiche sans supprimer}';

    protected $description = 'Supprime les pièces envoyées par des candidats qui n\'ont jamais terminé leur préinscription';

    public function handle(): int
    {
        // Tous les fichiers encore utilisés par un dossier enregistré
        $utilises = collect()
            ->merge(Candidat::query()->get(['CV', 'demande', 'scan_cartid', 'photo', 'scan_bac'])->flatMap(fn ($c) => $c->only(['CV', 'demande', 'scan_cartid', 'photo', 'scan_bac'])))
            ->merge(Diplome::query()->pluck('scan_bac_2'))
            ->merge(Diplome::query()->pluck('scan_bac_3'))
            ->merge(Stage::query()->pluck('attestation'))
            ->merge(Experience::query()->pluck('attestation'))
            ->merge(Attestation::query()->pluck('attestation'))
            ->filter()
            ->flip();

        $disque = Storage::disk('dossiers');
        $limite = now()->subHours((int) $this->option('heures'))->getTimestamp();
        $orphelins = collect($disque->allFiles())
            ->reject(fn ($f) => isset($utilises[$f]) || str_starts_with(basename($f), '.'))
            ->filter(fn ($f) => $disque->lastModified($f) < $limite)
            ->values();

        if ($this->option('simulation')) {
            $orphelins->each(fn ($f) => $this->line($f));
            $this->info($orphelins->count() . ' fichier(s) seraient supprimés.');
            return self::SUCCESS;
        }

        $disque->delete($orphelins->all());
        $this->info($orphelins->count() . ' fichier(s) orphelin(s) supprimé(s).');

        return self::SUCCESS;
    }
}
