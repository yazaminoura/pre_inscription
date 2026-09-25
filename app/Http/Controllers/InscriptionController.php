<?php

namespace App\Http\Controllers;

use App\Models\Inscription;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InscriptionController extends Controller
{
    public function updateStatut(Request $request, Inscription $inscription)
    {
        $validated = $request->validate([
            'statut' => ['required', Rule::in(array_keys(Inscription::STATUTS))],
            'motif' => 'nullable|string|max:1000',
        ]);

        $inscription->update([
            'statut' => $validated['statut'],
            'motif' => $validated['motif'] ?? null,
            'statut_at' => now(),
        ]);

        return back()->with('toastr', [
            'type' => 'success',
            'message' => 'Statut mis à jour : ' . $inscription->statut_label,
        ]);
    }
}
