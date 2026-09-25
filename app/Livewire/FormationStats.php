<?php

namespace App\Livewire;

use App\Models\Formation;
use App\Models\Inscription;
use Livewire\Component;

class FormationStats extends Component
{
    public function render()
    {
        $formations = Formation::withCount('inscriptions')->orderByDesc('inscriptions_count')->get();

        // [formation_id][statut] => nombre
        $parStatut = Inscription::selectRaw('formation_id, statut, count(*) as total')
            ->groupBy('formation_id', 'statut')
            ->get()
            ->groupBy('formation_id')
            ->map(fn ($lignes) => $lignes->pluck('total', 'statut'));

        return view('livewire.formation-stats', compact('formations', 'parStatut'))
            ->extends('utilisateur.layouts.app')
            ->section('content');
    }
}
