<?php

namespace App\Models\Concerns;

/**
 * Champs saisis en français dans les colonnes normales, et traduits (facultativement)
 * dans la colonne JSON « traductions » : {"en": {"titre": "..."}, "ar": {...}}.
 */
trait Traduisible
{
    public function initializeTraduisible(): void
    {
        $this->mergeCasts(['traductions' => 'array']);
    }

    /** Valeur du champ dans la langue courante, ou en français si la traduction manque. */
    public function tr(string $champ): ?string
    {
        $langue = app()->getLocale();
        $traduit = $langue === 'fr' ? null : ($this->traductions[$langue][$champ] ?? null);

        return filled($traduit) ? $traduit : $this->getAttribute($champ);
    }

    /** Nettoie les traductions reçues d'un formulaire : garde seulement les langues/champs prévus et non vides. */
    public static function nettoyerTraductions(?array $traductions): ?array
    {
        $propres = [];
        foreach (['en', 'ar'] as $langue) {
            foreach (static::CHAMPS_TRADUISIBLES as $champ) {
                $valeur = trim((string) ($traductions[$langue][$champ] ?? ''));
                if ($valeur !== '') {
                    $propres[$langue][$champ] = $valeur;
                }
            }
        }

        return $propres ?: null;
    }
}
