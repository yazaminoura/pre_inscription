<?php
namespace App\Http\Controllers;
use App\Exports\CandidatsExport;
use App\Models\Formation;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
   public function export($formationId)
{
    session()->flash('toastr', [
        'type' => 'success',
        'message' => 'Candidats exportés avec succès'
    ]);

    $formation = Formation::findOrFail($formationId);

    return Excel::download(new CandidatsExport($formationId), 'candidats_' . Str::slug($formation->titre) . '.xlsx');
}

    /** Toutes les candidatures, toutes formations confondues. */
    public function exportTout()
    {
        return Excel::download(new CandidatsExport(), 'candidats_toutes-formations_' . now()->format('Y-m-d') . '.xlsx');
    }
}
