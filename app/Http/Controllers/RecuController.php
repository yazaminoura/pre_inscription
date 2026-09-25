<?php

namespace App\Http\Controllers;

use App\Models\Inscription;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Récapitulatif PDF d'une préinscription, nommé NOM_Prenom_REFERENCE.pdf.
 * Téléchargeable seulement par la session qui vient de déposer le dossier, ou qui l'a retrouvé sur « Suivre mon dossier ».
 */
class RecuController extends Controller
{
    /** Autorise la session courante à télécharger le récapitulatif de cette référence. */
    public static function autoriser(string $reference): void
    {
        session(['recus' => array_values(array_unique(array_merge(session('recus', []), [$reference])))]);
    }

    public function telecharger(string $reference)
    {
        abort_unless(in_array($reference, session('recus', []), true), 403);

        $inscription = Inscription::with(['formation', 'historique', 'candidat.diplomes', 'candidat.stages', 'candidat.experiences', 'candidat.attestations'])
            ->where('reference', $reference)->firstOrFail();
        $candidat = $inscription->candidat;

        // L'arabe n'est pas bien mis en forme par le moteur PDF : le récapitulatif est alors en français
        if (app()->getLocale() === 'ar') {
            app()->setLocale('fr');
        }

        $pdf = Pdf::loadView('pdf.recu', [
            'inscription' => $inscription,
            'candidat' => $candidat,
            'logo' => $this->imageEnDataUri(config('etablissement.logo'), false),
            'photo' => $candidat->photo ? $this->imageEnDataUri($candidat->photo, true) : null,
        ])->setPaper('a4')
            // N'embarque que les caractères utilisés : ~900 Ko -> quelques dizaines de Ko
            ->setOption('isFontSubsettingEnabled', true);

        $nom = Str::of($candidat->nom)->ascii()->upper()->replaceMatches('/[^A-Z0-9]+/', '-')->trim('-')
            . '_' . Str::of($candidat->prenom)->ascii()->title()->replaceMatches('/[^A-Za-z0-9]+/', '-')->trim('-')
            . '_' . $inscription->reference . '.pdf';

        return $pdf->download($nom);
    }

    /** Image intégrée au PDF (logo public ou photo du dossier privé). */
    private function imageEnDataUri(?string $chemin, bool $prive): ?string
    {
        if (!$chemin) {
            return null;
        }
        $contenu = $prive
            ? Storage::disk('dossiers')->get($chemin)
            : (is_file(public_path($chemin)) ? file_get_contents(public_path($chemin)) : null);
        if (!$contenu) {
            return null;
        }
        $type = str_ends_with(strtolower($chemin), '.svg') ? 'image/svg+xml' : (finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $contenu) ?: 'image/png');

        return 'data:' . $type . ';base64,' . base64_encode($contenu);
    }
}
