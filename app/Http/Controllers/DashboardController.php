<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\Inscription;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $parStatut = Inscription::selectRaw('statut, count(*) as total')->groupBy('statut')->pluck('total', 'statut');
        $total = $parStatut->sum();

        $stats = [
            'total' => $total,
            'aujourdhui' => Inscription::whereDate('created_at', Carbon::today())->count(),
            'semaine' => Inscription::where('created_at', '>=', Carbon::today()->subDays(6))->count(),
            'a_traiter' => ($parStatut['en_attente'] ?? 0) + ($parStatut['en_cours'] ?? 0),
            'formations_ouvertes' => Formation::whereDate('date_debut', '<=', today())->whereDate('date_fin', '>=', today())->count(),
        ];

        // Candidatures des 14 derniers jours
        $parJour = Inscription::where('created_at', '>=', Carbon::today()->subDays(13))
            ->selectRaw('DATE(created_at) as jour, count(*) as total')
            ->groupBy('jour')
            ->pluck('total', 'jour');
        $courbe = collect(range(13, 0))->map(function ($i) use ($parJour) {
            $jour = Carbon::today()->subDays($i);
            return ['label' => $jour->format('d/m'), 'total' => (int) ($parJour[$jour->toDateString()] ?? 0)];
        });

        $formations = Formation::withCount([
            'inscriptions',
            'inscriptions as acceptees_count' => fn ($q) => $q->where('statut', 'acceptee'),
            'inscriptions as en_attente_count' => fn ($q) => $q->whereIn('statut', ['en_attente', 'en_cours']),
        ])->orderByDesc('inscriptions_count')->get();

        $recentes = Inscription::with(['formation', 'candidat'])
            ->whereHas('candidat')
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard', compact('stats', 'parStatut', 'courbe', 'formations', 'recentes'));
    }
}
