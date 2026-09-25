<?php

namespace App\Http\Controllers;

use App\Exports\ModeleImportExport;
use App\Imports\CandidatsImport;
use App\Models\Formation;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

/** Import de candidats depuis Excel/CSV : dans la formation choisie, ou celle de chaque ligne du fichier. */
class ImportController extends Controller
{
    public function modele()
    {
        return Excel::download(new ModeleImportExport(), 'modele_import_candidats.xlsx');
    }

    public function importer(Request $request)
    {
        $validated = $request->validate([
            'formation_id' => 'nullable|exists:formations,id',
            'fichier' => 'required|file|mimes:xlsx,xls,csv,txt|max:5120',
        ], [], ['formation_id' => 'formation', 'fichier' => 'fichier']);

        $formation = !empty($validated['formation_id']) ? Formation::findOrFail($validated['formation_id']) : null;

        try {
            $rapport = (new CandidatsImport())->importer($request->file('fichier'), $formation, $request->user());
        } catch (\Throwable $e) {
            report($e);

            return back()->with('toastr', ['type' => 'error', 'message' => 'Fichier illisible. Utilisez un fichier Excel (.xlsx) ou le modèle.']);
        }

        $rapport['formation'] = $formation?->titre ?? 'formations du fichier';
        $total = $rapport['crees'] + $rapport['maj'];

        return back()
            ->with('import_rapport', $rapport)
            ->with('toastr', [
                'type' => $rapport['erreurs'] ? ($total ? 'warning' : 'error') : 'success',
                'message' => "$total candidat(s) importé(s)" . ($rapport['erreurs'] ? ' · ' . count($rapport['erreurs']) . ' erreur(s)' : ''),
            ]);
    }
}
