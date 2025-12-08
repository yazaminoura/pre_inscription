<?php

namespace App\Livewire;

use Illuminate\Mail\Mailables\Content;
use Livewire\Component;
use App\Models\Formation;
use App\Exports\FormationCandidatsExport;
use Maatwebsite\Excel\Facades\Excel;
use PhpParser\Node\Stmt\Return_;

class FormationStats extends Component
{
    public function render()
{
    $formations = Formation::withCount('inscriptions')->get();
    
    \Log::info('Formations data:', ['count' => $formations->count(), 'data' => $formations->toArray()]);
    
    return view('livewire.formation-stats', [
        'formations' => $formations
    ])->extends('utilisateur.layouts.app')->section('content');
}
    public function exportCandidats($formationId)
    {
        return redirect()->route('export.candidats', ['id' => $formationId]);
    }
    
}