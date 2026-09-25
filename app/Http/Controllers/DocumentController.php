<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Affiche une pièce d'un dossier candidat. Route réservée aux administrateurs connectés :
     * les fichiers ne sont plus accessibles par une URL publique.
     */
    public function voir(string $chemin)
    {
        // Pas de remontée de dossier (« ../ ») ni de chemin absolu
        abort_if(str_contains($chemin, '..') || str_starts_with($chemin, '/') || str_contains($chemin, '\\'), 404);

        $disque = Storage::disk('dossiers');
        abort_unless($disque->exists($chemin), 404);

        return $disque->response($chemin, basename($chemin), [
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ], 'inline');
    }
}
