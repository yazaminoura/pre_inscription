<?php

namespace App\Http\Controllers;

use App\Mail\StatutChange;
use App\Models\Inscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class InscriptionController extends Controller
{
    public function updateStatut(Request $request, Inscription $inscription)
    {
        $validated = $request->validate([
            'statut' => ['required', Rule::in(array_keys(Inscription::STATUTS))],
            'motif' => 'nullable|string|max:1000',
            'notifier' => 'nullable|boolean',
        ]);
        $motif = trim($validated['motif'] ?? '') ?: null;

        // Le candidat doit savoir ce qui manque
        if ($validated['statut'] === 'incomplet' && !$motif) {
            return back()->with('toastr', ['type' => 'error', 'message' => 'Indiquez dans le motif le ou les documents manquants.']);
        }

        if ($validated['statut'] === $inscription->statut && $motif === $inscription->motif) {
            return back()->with('toastr', ['type' => 'info', 'message' => 'Aucun changement.']);
        }

        $ligne = $inscription->changerStatut($validated['statut'], $motif, $request->user());

        $message = 'Statut mis à jour : ' . $inscription->statut_label;
        $type = 'success';
        if ($request->boolean('notifier')) {
            try {
                Mail::to($inscription->candidat->email)->send(new StatutChange($inscription));
                $ligne->update(['notifie' => true]);
                $message .= ' · candidat prévenu par email';
            } catch (\Throwable $e) {
                Log::error('Email de changement de statut non envoyé : ' . $e->getMessage());
                $type = 'warning';
                $message .= ' · l\'email n\'a pas pu être envoyé (vérifiez la configuration email)';
            }
        }

        return back()->with('toastr', ['type' => $type, 'message' => $message]);
    }
}
