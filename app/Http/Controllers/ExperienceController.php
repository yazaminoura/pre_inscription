<?php
namespace App\Http\Controllers;
use App\Models\Experience;
use App\Models\Candidat;
use App\Http\Requests\StoreExperienceRequest;
use App\Http\Requests\UpdateExperienceRequest;
use Illuminate\Support\Facades\Storage;
use Brian2694\Toastr\Facades\Toastr;

class ExperienceController extends Controller
{
    public function index()
    {
        $experiences = Experience::with('candidat')->get();
        return view('utilisateur.experiences.index', compact('experiences'));
    }

    public function create()
    {
        $candidats = Candidat::all();
        return view('utilisateur.experiences.create', compact('candidats'));
    }

    public function store(StoreExperienceRequest $request)
    {
        $validated = $request->validated();
        $candidat = Candidat::findOrFail($validated['candidat_id']);

        if ($request->hasFile('attestation')) {
            $count = Experience::where('candidat_id', $candidat->id)->count() + 1;
            $file = $request->file('attestation');
            $extension = $file->getClientOriginalExtension();
            $timestamp = now()->format('YmdHis');
            $filename = strtoupper($candidat->CNE)
                . strtolower(str_replace(' ', '', $candidat->nom))
                . strtolower(str_replace(' ', '', $candidat->prenom))
                . 'experiences' . $count . '_' . $timestamp . '.' . $extension;
            $path = $file->storeAs('experiences', $filename, 'public');
            $validated['attestation'] = $path;
        }

        Experience::create($validated);

        return redirect()->route('experiences.index')
            ->with('toastr', [
                'type' => 'success',
                'message' => 'Expérience ajoutée avec succès'
            ]);
    }

    public function update(UpdateExperienceRequest $request, $id)
    {
        $experience = Experience::findOrFail($id);
        $validated = $request->validated();
        $candidat = Candidat::findOrFail($validated['candidat_id']);

        if ($request->hasFile('attestation')) {
            if ($experience->attestation && Storage::disk('public')->exists($experience->attestation)) {
                Storage::disk('public')->delete($experience->attestation);
            }
            $count = Experience::where('candidat_id', $candidat->id)
                ->where('id', '!=', $experience->id)
                ->count() + 1;
            $file = $request->file('attestation');
            $extension = $file->getClientOriginalExtension();
            $timestamp = now()->format('YmdHis');
            $filename = strtoupper($candidat->CNE)
                . strtolower(str_replace(' ', '', $candidat->nom))
                . strtolower(str_replace(' ', '', $candidat->prenom))
                . 'experiences' . $count . '_' . $timestamp . '.' . $extension;
            $path = $file->storeAs('experiences', $filename, 'public');
            $validated['attestation'] = $path;
        }

        $experience->update($validated);

        return redirect()->route('experiences.index')
            ->with('toastr', [
                'type' => 'success',
                'message' => 'Expérience mise à jour avec succès'
            ]);
    }

    public function destroy($id)
    {
        $experience = Experience::findOrFail($id);
        if ($experience->attestation && Storage::disk('public')->exists($experience->attestation)) {
            Storage::disk('public')->delete($experience->attestation);
        }
        $experience->delete();
        return redirect()->route('experiences.index')
            ->with('toastr', [
                'type' => 'success',
                'message' => 'Expérience supprimée avec succès'
            ]);
    }
}