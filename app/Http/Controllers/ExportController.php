<?php
namespace App\Http\Controllers;
use App\Exports\CandidatsExport;
use Maatwebsite\Excel\Facades\Excel;
use Brian2694\Toastr\Facades\Toastr;

class ExportController extends Controller
{
   public function export($formationId)
{
    session()->flash('toastr', [
        'type' => 'success',
        'message' => 'Candidats exportés avec succès'
    ]);

    return Excel::download(new CandidatsExport($formationId), 'candidats_export.xlsx');
}
}
