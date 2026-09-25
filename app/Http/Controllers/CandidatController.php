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
        [$statut, $formationId] = $this->filtres($request);

        // Une ligne = une candidature (un candidat peut postuler à plusieurs formations)
        $inscriptions = $this->candidatures($statut, $formationId)->with(['candidat', 'formation'])->get();

        $compteurs = Inscription::when($formationId, fn ($q) => $q->where('formation_id', $formationId))
            ->selectRaw('statut, count(*) as total')->groupBy('statut')->pluck('total', 'statut');
        $formations = Formation::orderBy('type_formation')->orderBy('titre')->get();
        $filtres = array_filter(['statut' => $statut, 'formation' => $formationId]);

        return view('utilisateur.candidats.index', compact('inscriptions', 'statut', 'compteurs', 'formations', 'formationId', 'filtres'));
    }

    public function show(Request $request, Candidat $candidat)
    {
        $candidat->load(['inscriptions.formation', 'diplomes', 'stages', 'experiences', 'attestations']);

        // Précédent / suivant dans la même liste (mêmes filtres, même ordre) que la page Candidatures
        [$statut, $formationId] = $this->filtres($request);
        $filtres = array_filter(['statut' => $statut, 'formation' => $formationId]);
        $ids = $this->candidatures($statut, $formationId)->pluck('candidat_id')->unique()->values();
        $position = $ids->search($candidat->id);
        $navigation = [
            'filtres' => $filtres,
            'position' => $position === false ? null : $position + 1,
            'total' => $ids->count(),
            'precedent' => $position ? $ids[$position - 1] : null,
            'suivant' => $position !== false && $position + 1 < $ids->count() ? $ids[$position + 1] : null,
        ];

        return view('utilisateur.candidats.show', compact('candidat', 'navigation'));
    }

    private function filtres(Request $request): array
    {
        $statut = $request->query('statut');

        return [isset(Inscription::STATUTS[$statut]) ? $statut : null, $request->integer('formation') ?: null];
    }

    private function candidatures(?string $statut, ?int $formationId)
    {
        return Inscription::query()
            ->whereHas('candidat')
            ->when($statut, fn ($q) => $q->where('statut', $statut))
            ->when($formationId, fn ($q) => $q->where('formation_id', $formationId))
            ->orderByDesc('created_at')
            ->orderByDesc('id');
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
        Storage::disk('dossiers')->delete($fichiers);

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
