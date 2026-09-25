<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use ZipArchive;

class CreerSauvegarde extends Command
{
    protected $signature = 'sauvegarde:creer';

    protected $description = 'Sauvegarde la base de données, les pièces des candidats et le logo dans un fichier .zip';

    // Dossiers de storage/app ajoutés à l'archive
    private const DOSSIERS = ['dossiers', 'public/etablissement'];

    public function handle(): int
    {
        $cible = config('sauvegarde.dossier');
        File::ensureDirectoryExists($cible);

        $nom = 'preinscription_' . now()->format('Y-m-d_His');
        $sql = $cible . DIRECTORY_SEPARATOR . $nom . '.sql';
        $zip = $cible . DIRECTORY_SEPARATOR . $nom . '.zip';

        try {
            $this->exporterBase($sql);
            $this->creerArchive($zip, $sql);
        } catch (\Throwable $e) {
            File::delete([$sql, $zip]);
            $this->error('Sauvegarde échouée : ' . $e->getMessage());
            report($e);

            return self::FAILURE;
        } finally {
            File::delete($sql);
        }

        $supprimees = $this->nettoyer($cible);
        $this->info('Sauvegarde créée : ' . $zip . ' (' . round(filesize($zip) / 1048576, 1) . ' Mo)');
        if ($supprimees) {
            $this->line("$supprimees ancienne(s) sauvegarde(s) supprimée(s).");
        }

        return self::SUCCESS;
    }

    private function exporterBase(string $fichier): void
    {
        $db = config('database.connections.' . config('database.default'));
        if (($db['driver'] ?? null) !== 'mysql') {
            throw new \RuntimeException('seule une base MySQL/MariaDB est prise en charge.');
        }

        // Le mot de passe passe par MYSQL_PWD pour ne pas apparaître dans la liste des processus
        $process = new Process([
            config('sauvegarde.mysqldump'),
            '--host=' . $db['host'],
            '--port=' . $db['port'],
            '--user=' . $db['username'],
            '--single-transaction',
            '--routines',
            '--default-character-set=utf8mb4',
            '--result-file=' . $fichier,
            $db['database'],
        ], null, ['MYSQL_PWD' => (string) $db['password']], null, 600);
        $process->run();

        if (!$process->isSuccessful() || !is_file($fichier) || filesize($fichier) === 0) {
            throw new \RuntimeException('mysqldump : ' . trim($process->getErrorOutput() ?: 'introuvable, renseignez SAUVEGARDE_MYSQLDUMP dans .env'));
        }
    }

    private function creerArchive(string $zip, string $sql): void
    {
        $archive = new ZipArchive();
        if ($archive->open($zip, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException("impossible de créer $zip");
        }

        $archive->addFile($sql, 'base.sql');
        foreach (self::DOSSIERS as $dossier) {
            $racine = storage_path('app/' . $dossier);
            if (!is_dir($racine)) {
                continue;
            }
            foreach (File::allFiles($racine) as $f) {
                $archive->addFile($f->getPathname(), 'fichiers/' . $dossier . '/' . str_replace('\\', '/', $f->getRelativePathname()));
            }
        }

        if (!$archive->close()) {
            throw new \RuntimeException("écriture de $zip impossible");
        }
    }

    /** Garde les N sauvegardes les plus récentes. */
    private function nettoyer(string $cible): int
    {
        $anciennes = collect(File::glob($cible . DIRECTORY_SEPARATOR . 'preinscription_*.zip'))
            ->sort()->reverse()->slice(max(1, config('sauvegarde.garder')));
        File::delete($anciennes->all());

        return $anciennes->count();
    }
}
