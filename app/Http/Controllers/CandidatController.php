<?php
namespace App\Http\Controllers;

use App\Models\Candidat;
use App\Models\Formation;
use App\Models\Inscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CandidatController extends Controller
{
    public function index(Request $request)
    {
        $statut = $request->query('statut');
        $statut = isset(Inscription::STATUTS[$statut]) ? $statut : null;
        $formationId = $request->integer('formation') ?: null;

        // Une ligne = une candidature (un candidat peut postuler à plusieurs formations)
        $inscriptions = Inscription::with(['candidat', 'formation'])
            ->whereHas('candidat')
            ->when($statut, fn ($q) => $q->where('statut', $statut))
            ->when($formationId, fn ($q) => $q->where('formation_id', $formationId))
            ->latest()
            ->get();

        $compteurs = Inscription::when($formationId, fn ($q) => $q->where('formation_id', $formationId))
            ->selectRaw('statut, count(*) as total')->groupBy('statut')->pluck('total', 'statut');
        $formations = Formation::orderBy('type_formation')->orderBy('titre')->get();

        return view('utilisateur.candidats.index', compact('inscriptions', 'statut', 'compteurs', 'formations', 'formationId'));
    }

    public function show(Candidat $candidat)
    {
        $candidat->load(['inscriptions.formation', 'diplomes', 'stages', 'experiences', 'attestations']);

        return view('utilisateur.candidats.show', compact('candidat'));
    }

    public function destroy(Candidat $candidat)
    {
        // Tous les fichiers du dossier, pas seulement ceux de la fiche candidat
        $fichiers = collect([$candidat->CV, $candidat->demande, $candidat->scan_cartid, $candidat->photo, $candidat->scan_bac])
            ->merge($candidat->diplomes->flatMap(fn ($d) => [$d->scan_bac_2, $d->scan_bac_3]))
            ->merge($candidat->stages->pluck('attestation'))
            ->merge($candidat->experiences->pluck('attestation'))
            ->merge($candidat->attestations->pluck('attestation'))
            ->filter()
            ->all();
        Storage::disk('public')->delete($fichiers);

        $candidat->diplomes()->delete();
        $candidat->stages()->delete();
        $candidat->experiences()->delete();
        $candidat->attestations()->delete();
        $candidat->delete();

        return redirect()->route('candidats.index')
            ->with('toastr', [
                'type' => 'success',
                'message' => 'Dossier supprimé',
            ]);
    }
}
