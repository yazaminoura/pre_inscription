<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

/**
 * Identité de l'établissement (une seule ligne), modifiable depuis l'administration.
 * Les valeurs sont recopiées dans config('etablissement.*') au démarrage : les vues n'utilisent que la config.
 */
class Etablissement extends Model
{
    use Concerns\Traduisible;

    public const CHAMPS_TRADUISIBLES = ['nom', 'slogan', 'presentation'];

    protected $table = 'etablissement';

    protected $guarded = ['id'];

    /** Remplace nom / accroche / présentation de la config par leur traduction dans la langue courante. */
    public static function traduireLaConfig(string $langue): void
    {
        foreach (config("etablissement.traductions.$langue", []) as $champ => $valeur) {
            if (in_array($champ, self::CHAMPS_TRADUISIBLES, true) && filled($valeur)) {
                config(["etablissement.$champ" => $valeur]);
            }
        }
    }

    public static function actuel(): self
    {
        return static::query()->first() ?? new static();
    }

    /** Recopie les valeurs saisies par-dessus les valeurs par défaut de config/etablissement.php. */
    public static function appliquerALaConfig(): void
    {
        try {
            if (!Schema::hasTable('etablissement')) {
                return;
            }
            $ligne = static::query()->first();
        } catch (\Throwable $e) {
            return; // base indisponible (installation, tests) : on garde les valeurs par défaut
        }
        if (!$ligne) {
            return;
        }

        $valeurs = array_filter($ligne->only([
            'nom', 'sigle', 'ville', 'pays', 'adresse', 'telephone', 'email', 'site', 'slogan', 'presentation', 'couleur', 'logo',
        ]), fn ($v) => $v !== null && $v !== '');
        if (isset($valeurs['logo'])) {
            $valeurs['logo'] = 'storage/' . $valeurs['logo'];
        }
        $valeurs['traductions'] = $ligne->traductions ?? [];

        config(['etablissement' => array_merge(config('etablissement', []), $valeurs)]);
    }
}
