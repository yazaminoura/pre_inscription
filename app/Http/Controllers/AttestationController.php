<?php
namespace App\Http\Controllers;
use App\Models\Attestation;
use App\Models\Candidat;
use App\Http\Requests\StoreAttestationRequest;
use App\Http\Requests\UpdateAttestationRequest;
use Illuminate\Support\Facades\Storage;
use Brian2694\Toastr\Facades\Toastr;

class AttestationController extends Controller
{
    public function index()
    {
        $attestations = Attestation::with('candidat')->get();
        return view('utilisateur.attestations.index', compact('attestations'));
    }

    public function create()
    {
        $candidats = Candidat::all();
        return view('utilisateur.attestations.create', compact('candidats'));
    }

    public function store(StoreAttestationRequest $request)
    {
        $validated = $request->validated();
        $candidat = Candidat::findOrFail($validated['candidat_id']);

        if ($request->hasFile('attestation')) {
            $count = Attestation::where('candidat_id', $candidat->id)->count() + 1;
            $file = $request->file('attestation');
            $extension = $file->getClientOriginalExtension();
            $timestamp = now()->format('YmdHis');
            $filename = strtoupper($candidat->CNE)
                . strtolower(str_replace(' ', '', $candidat->nom))
                . strtolower(str_replace(' ', '', $candidat->prenom))
                . 'attestation' . $count . '_' . $timestamp . '.' . $extension;
            $path = $file->storeAs('attestations', $filename, 'public');
            $validated['attestation'] = $path;
        }

        Attestation::create($validated);

        return redirect()->route('attestations.index')
            ->with('toastr', [
                'type' => 'success',
                'message' => 'Attestation ajoutée avec succès'
            ]);
    }

    public function update(UpdateAttestationRequest $request, $id)
    {
        $attestation = Attestation::findOrFail($id);
        $validated = $request->validated();
        $candidat = Candidat::findOrFail($validated['candidat_id']);

        if ($request->hasFile('attestation')) {
            if ($attestation->attestation && Storage::disk('public')->exists($attestation->attestation)) {
                Storage::disk('public')->delete($attestation->attestation);
            }
            $count = Attestation::where('candidat_id', $candidat->id)
                ->where('type_attestation', $validated['type_attestation'])
                ->where('id', '!=', $attestation->id)
                ->count() + 1;
            $file = $request->file('attestation');
            $extension = $file->getClientOriginalExtension();
            $timestamp = now()->format('YmdHis');
            $filename = strtoupper($candidat->CNE)
                . strtolower(str_replace(' ', '', $candidat->nom))
                . strtolower(str_replace(' ', '', $candidat->prenom))
                . '_' . strtolower($validated['type_attestation'])
                . '_' . $count
                . '_' . $timestamp
                . '.' . $extension;
            $path = $file->storeAs('attestations', $filename, 'public');
            $validated['attestation'] = $path;
        }

        $attestation->update($validated);

        return redirect()->route('attestations.index')
            ->with('toastr', [
                'type' => 'success',
                'message' => 'Attestation modifiée avec succès'
            ]);
    }

    public function edit($id)
    {
        $attestation = Attestation::findOrFail($id);
        $candidats = Candidat::all();
        return view('utilisateur.attestations.edit', compact('attestation', 'candidats'));
    }

    public function destroy($id)
    {
        $attestation = Attestation::findOrFail($id);
        if ($attestation->attestation && Storage::disk('public')->exists($attestation->attestation)) {
            Storage::disk('public')->delete($attestation->attestation);
        }
        $attestation->delete();
        return redirect()->route('attestations.index')
            ->with('toastr', [
                'type' => 'success',
                'message' => 'Attestation supprimée avec succès'
            ]);
    }
}