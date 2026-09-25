<?php

namespace App\Console\Commands;

use App\Http\Controllers\CandidatformController;
use App\Mail\StatutChange;
use App\Models\Inscription;
use Illuminate\Console\Command;

class GenererTraductions extends Command
{
    protected $signature = 'traductions:generer';

    protected $description = 'Génère lang/en.json et lang/ar.json depuis lang/sources/traductions.php et signale les textes non traduits';

    public function handle(): int
    {
        $table = require lang_path('sources/traductions.php');

        foreach (['en' => 0, 'ar' => 1] as $langue => $colonne) {
            $json = array_map(fn ($t) => $t[$colonne], $table);
            ksort($json);
            file_put_contents(lang_path("$langue.json"), json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "\n");
            $this->info("lang/$langue.json : " . count($json) . ' textes');
        }

        $manquants = array_diff(array_keys($this->clesUtilisees()), array_keys($table));
        if ($manquants) {
            $this->warn(count($manquants) . ' texte(s) du site public sans traduction :');
            foreach ($manquants as $cle) {
                $this->line('  - ' . $cle);
            }
            return self::FAILURE;
        }
        $this->info('Tous les textes du site public sont traduits.');

        return self::SUCCESS;
    }

    /** Textes passés à __() / trans_choice() dans les pages publiques, plus les listes dynamiques. */
    private function clesUtilisees(): array
    {
        $fichiers = array_merge(
            glob(resource_path('views/candidateur/*.blade.php')),
            glob(resource_path('views/candidateur/*/*.blade.php')),
            [resource_path('views/components/champ.blade.php'), app_path('Http/Controllers/CandidatformController.php'), app_path('Http/Controllers/SuiviController.php'), app_path('Models/Formation.php')]
        );
        $cles = [];
        foreach ($fichiers as $f) {
            $c = file_get_contents($f);
            preg_match_all("/(?:__|trans_choice)\(\s*'((?:[^'\\\\]|\\\\.)*)'/u", $c, $m1);
            preg_match_all('/(?:__|trans_choice)\(\s*"((?:[^"\\\\]|\\\\.)*)"/u', $c, $m2);
            foreach (array_merge($m1[1], $m2[1]) as $k) {
                $cles[stripslashes($k)] = true;
            }
        }
        foreach (CandidatformController::ETAPES as $e) $cles[$e] = true;
        foreach (Inscription::STATUTS as [$libelle]) $cles[$libelle] = true;
        foreach (config('etablissement.types_formation') as $t) $cles[$t] = true;
        foreach (StatutChange::MESSAGES as $m) $cles[$m] = true;
        $libelles = new \ReflectionMethod(CandidatformController::class, 'libelles');
        $libelles->setAccessible(true);
        foreach ($libelles->invoke(new CandidatformController()) as $l) $cles[$l] = true;
        unset($cles['']);

        return $cles;
    }
}
