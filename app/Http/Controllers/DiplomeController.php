<?php
namespace App\Http\Controllers;
use App\Http\Requests\StoreDiplomeRequest;
use App\Http\Requests\UpdateDiplomeRequest;
use App\Models\Diplome;
use App\Models\Candidat;
use Illuminate\Support\Facades\Storage;
use Brian2694\Toastr\Facades\Toastr;

class DiplomeController extends Controller
{
    public function index()
    {
        $diplomes = Diplome::with('candidat')->get();
        return view('utilisateur.diplomes.index', compact('diplomes'));
    }

    public function create()
    {
        $candidats = Candidat::all();
        return view('utilisateur.diplomes.create', compact('candidats'));
    }

    public function store(StoreDiplomeRequest $request)
    {
        $validated = $request->validated();
        $candidat = Candidat::findOrFail($validated['candidat_id']);
        $count = Diplome::where('candidat_id', $candidat->id)->count() + 1;

        if ($request->hasFile('scan_bac_2')) {
            $fileBac_2 = $request->file('scan_bac_2');
            $extensionBac_2 = $fileBac_2->getClientOriginalExtension();
            $filenameBac_2 = strtoupper($candidat->CNE)
                . '_' . strtolower(str_replace(' ', '', $candidat->nom))
                . strtolower(str_replace(' ', '', $candidat->prenom))
                . '_bac_2_' . $count . '_' . now()->format('YmdHis') . '.' . $extensionBac_2;
            $pathBac_2 = $fileBac_2->storeAs('bac_2', $filenameBac_2, 'public');
            $validated['scan_bac_2'] = $pathBac_2;
        }

        if ($request->hasFile('scan_bac_3')) {
            $fileBac_3 = $request->file('scan_bac_3');
            $extensionBac_3 = $fileBac_3->getClientOriginalExtension();
            $filenameBac_3 = strtoupper($candidat->CNE)
                . '_' . strtolower(str_replace(' ', '', $candidat->nom))
                . strtolower(str_replace(' ', '', $candidat->prenom))
                . '_bac_3_' . $count . '_' . now()->format('YmdHis') . '.' . $extensionBac_3;
            $pathBac_3 = $fileBac_3->storeAs('bac_3', $filenameBac_3, 'public');
            $validated['scan_bac_3'] = $pathBac_3;
        }

        Diplome::create($validated);

        return redirect()->route('diplomes.index')
            ->with('toastr', [
                'type' => 'success',
                'message' => 'Diplôme ajouté avec succès'
            ]);
    }



    public function edit($id)
    {
        $diplome = Diplome::findOrFail($id);
        $candidats = Candidat::all();
        return view('utilisateur.diplomes.edit', compact('diplome', 'candidats'));
    }

    public function update(UpdateDiplomeRequest $request, $id)
    {
        $diplome = Diplome::findOrFail($id);
        $validated = $request->validated();
        $candidat = Candidat::findOrFail($validated['candidat_id']);
        $count = Diplome::where('candidat_id', $candidat->id)->count();

        if ($request->hasFile('scan_bac_2')) {
            if ($diplome->scan_bac_2 && Storage::disk('public')->exists($diplome->scan_bac_2)) {
                Storage::disk('public')->delete($diplome->scan_bac_2);
            }
            $fileBac_2 = $request->file('scan_bac_2');
            $extensionBac_2 = $fileBac_2->getClientOriginalExtension();
            $filenameBac_2 = strtoupper($candidat->CNE)
                . '_' . strtolower(str_replace(' ', '', $candidat->nom))
                . strtolower(str_replace(' ', '', $candidat->prenom))
                . '_bac_2_' . $count . '_' . now()->format('YmdHis') . '.' . $extensionBac_2;
            $pathBac_2 = $fileBac_2->storeAs('bac_2', $filenameBac_2, 'public');
            $validated['scan_bac_2'] = $pathBac_2;
        } else {
            $validated['scan_bac_2'] = $diplome->scan_bac_2 ?? null;
        }

        if ($request->hasFile('scan_bac_3')) {
            if ($diplome->scan_bac_3 && Storage::disk('public')->exists($diplome->scan_bac_3)) {
                Storage::disk('public')->delete($diplome->scan_bac_3);
            }
            $fileBac_3 = $request->file('scan_bac_3');
            $extensionBac_3 = $fileBac_3->getClientOriginalExtension();
            $filenameBac_3 = strtoupper($candidat->CNE)
                . '_' . strtolower(str_replace(' ', '', $candidat->nom))
                . strtolower(str_replace(' ', '', $candidat->prenom))
                . '_bac_3_' . $count . '_' . now()->format('YmdHis') . '.' . $extensionBac_3;
            $pathBac_3 = $fileBac_3->storeAs('bac_3', $filenameBac_3, 'public');
            $validated['scan_bac_3'] = $pathBac_3;
        } else {
            $validated['scan_bac_3'] = $diplome->scan_bac_3 ?? null;
        }

        $diplome->update($validated);

        return redirect()->route('diplomes.index')
            ->with('toastr', [
                'type' => 'success',
                'message' => 'Diplôme mis à jour avec succès'
            ]);
    }

    public function destroy($id)
    {
        $diplome = Diplome::findOrFail($id);

        if ($diplome->scan_bac_2 && Storage::disk('public')->exists($diplome->scan_bac_2)) {
            Storage::disk('public')->delete($diplome->scan_bac_2);
        }
        if ($diplome->scan_bac_3 && Storage::disk('public')->exists($diplome->scan_bac_3)) {
            Storage::disk('public')->delete($diplome->scan_bac_3);
        }

        $diplome->delete();

        return redirect()->route('diplomes.index')
            ->with('toastr', [
                'type' => 'success',
                'message' => 'Diplôme supprimé avec succès'
            ]);
    }
}