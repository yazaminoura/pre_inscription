<?php

namespace App\Http\Controllers;

use App\Mail\StatutChange;
use App\Models\Inscription;
use Illuminate\Http\Request;

/** Page publique « Suivre mon dossier » : référence + email du candidat. */
class SuiviController extends Controller
{
    public function formulaire(Request $request)
    {
        return view('candidateur.suivi', ['reference' => $request->query('reference'), 'inscription' => null]);
    }

    public function consulter(Request $request)
    {
        $validated = $request->validate([
            'reference' => 'required|string|max:20',
            'email' => 'required|email|max:100',
        ], [], ['reference' => __('référence'), 'email' => __('email')]);

        $inscription = Inscription::with(['formation', 'candidat', 'historique'])
            ->where('reference', strtoupper(trim($validated['reference'])))
            ->whereHas('candidat', fn ($q) => $q->whereRaw('LOWER(email) = ?', [mb_strtolower(trim($validated['email']))]))
            ->first();

        if (!$inscription) {
            // Même message que la référence ou l'email soit faux : on ne révèle pas lequel
            return back()->withInput()->withErrors(['reference' => __('Aucun dossier ne correspond à cette référence et cet email.')]);
        }

        return view('candidateur.suivi', [
            'reference' => $inscription->reference,
            'inscription' => $inscription,
            'message' => StatutChange::MESSAGES[$inscription->statut] ?? '',
        ]);
    }
}
