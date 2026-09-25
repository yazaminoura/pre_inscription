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
    protected $table = 'etablissement';

    protected $guarded = ['id'];

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

        config(['etablissement' => array_merge(config('etablissement', []), $valeurs)]);
    }
}
