<?php
namespace App\Http\Controllers;
use App\Models\Stage;
use App\Models\Candidat;
use App\Http\Requests\StoreStageRequest;
use App\Http\Requests\UpdateStageRequest;
use Illuminate\Support\Facades\Storage;
use Brian2694\Toastr\Facades\Toastr;

class StageController extends Controller
{
    public function index()
    {
        $stages = Stage::with('candidat')->get();
        return view('utilisateur.stages.index', compact('stages'));
    }

    public function create()
    {
        $candidats = Candidat::all();
        return view('utilisateur.stages.create', compact('candidats'));
    }

    public function store(StoreStageRequest $request)
    {
        $validated = $request->validated();
        $candidat = Candidat::findOrFail($validated['candidat_id']);

        if ($request->hasFile('attestation')) {
            $count = Stage::where('candidat_id', $candidat->id)->count() + 1;
            $file = $request->file('attestation');
            $extension = $file->getClientOriginalExtension();
            $timestamp = now()->format('YmdHis');
            $filename = strtoupper($candidat->CNE)
                . strtolower(str_replace(' ', '', $candidat->nom))
                . strtolower(str_replace(' ', '', $candidat->prenom))
                . '_stage_' . $count . '_' . $timestamp . '.' . $extension;
            $path = $file->storeAs('stages', $filename, 'public');
            $validated['attestation'] = $path;
        }

        Stage::create($validated);

        return redirect()->route('stages.index')
            ->with('toastr', [
                'type' => 'success',
                'message' => 'Stage ajouté avec succès'
            ]);
    }

    public function edit($id)
    {
        $stage = Stage::findOrFail($id);
        $candidats = Candidat::all();
        return view('stages.edit', compact('stage', 'candidats'));
    }

    public function update(UpdateStageRequest $request, $id)
    {
        $stage = Stage::findOrFail($id);
        $validated = $request->validated();
        $candidat = Candidat::findOrFail($validated['candidat_id']);

        if ($request->hasFile('attestation')) {
            if ($stage->attestation && Storage::disk('public')->exists($stage->attestation)) {
                Storage::disk('public')->delete($stage->attestation);
            }
            $count = Stage::where('candidat_id', $candidat->id)
                ->where('id', '!=', $stage->id)
                ->count() + 1;
            $file = $request->file('attestation');
            $extension = $file->getClientOriginalExtension();
            $timestamp = now()->format('YmdHis');
            $filename = strtoupper($candidat->CNE)
                . strtolower(str_replace(' ', '', $candidat->nom))
                . strtolower(str_replace(' ', '', $candidat->prenom))
                . '_stage_' . $count . '_' . $timestamp . '.' . $extension;
            $path = $file->storeAs('stages', $filename, 'public');
            $validated['attestation'] = $path;
        }

        $stage->update($validated);

        return redirect()->route('stages.index')
            ->with('toastr', [
                'type' => 'success',
                'message' => 'Stage modifié avec succès'
            ]);
    }

    public function destroy($id)
    {
        $stage = Stage::findOrFail($id);
        if ($stage->attestation && Storage::disk('public')->exists($stage->attestation)) {
            Storage::disk('public')->delete($stage->attestation);
        }
        $stage->delete();
        return redirect()->route('stages.index')
            ->with('toastr', [
                'type' => 'success',
                'message' => 'Stage supprimé avec succès'
            ]);
    }
}