<?php
namespace App\Http\Controllers;
use App\Http\Requests\StoreCandidatRequest;
use App\Http\Requests\UpdateCandidatRequest;
use App\Models\Candidat;
use App\Models\Formation;
use App\Models\Inscription;
use Illuminate\Support\Facades\Storage;
use Brian2694\Toastr\Facades\Toastr;

class CandidatController extends Controller
{
    public function index()
    {
        $candidats = Candidat::with([
            'stages',
            'attestations',
            'diplomes',
            'experiences',
            'inscriptions.formation'
        ])->get();
        return view('utilisateur.candidats.index', compact('candidats'));
    }

    public function create()
    {
        $currentDate = now()->toDateString();
        $formations = Formation::where('date_debut', '<=', $currentDate)
            ->where('date_fin', '>=', $currentDate)
            ->get();
        return view('utilisateur.candidats.create', compact('formations'));
    }

    public function store(StoreCandidatRequest $request)
    {
        $validated = $request->validated();
        $baseName = strtoupper($validated['CNE']) . strtolower(str_replace(' ', '', $validated['nom'])) . strtolower(str_replace(' ', '', $validated['prenom']));
        $timestamp = now()->format('YmdHis');

        if ($request->hasFile('CV')) {
            $CVExtension = $request->file('CV')->getClientOriginalExtension();
            $CVName = $baseName . '_CV_' . $timestamp . '.' . $CVExtension;
            $validated['CV'] = $request->file('CV')->storeAs('CV', $CVName, 'public');
        }

        if ($request->hasFile('demande')) {
            $demandeExtension = $request->file('demande')->getClientOriginalExtension();
            $demandeName = $baseName . '_demande_' . $timestamp . '.' . $demandeExtension;
            $validated['demande'] = $request->file('demande')->storeAs('demande', $demandeName, 'public');
        }

        if ($request->hasFile('scan_cartid')) {
            $cinExtension = $request->file('scan_cartid')->getClientOriginalExtension();
            $cinName = $baseName . '_cin_' . $timestamp . '.' . $cinExtension;
            $validated['scan_cartid'] = $request->file('scan_cartid')->storeAs('cart', $cinName, 'public');
        }

        if ($request->hasFile('photo')) {
            $photoExtension = $request->file('photo')->getClientOriginalExtension();
            $photoName = $baseName . '_photo_' . $timestamp . '.' . $photoExtension;
            $validated['photo'] = $request->file('photo')->storeAs('photos', $photoName, 'public');
        }

        if ($request->hasFile('scan_bac')) {
            $bacExtension = $request->file('scan_bac')->getClientOriginalExtension();
            $bacName = $baseName . '_bac_' . $timestamp . '.' . $bacExtension;
            $validated['scan_bac'] = $request->file('scan_bac')->storeAs('bac', $bacName, 'public');
        }

        $candidat = Candidat::create($validated);

        Inscription::create([
            'candidat_id' => $candidat->id,
            'formation_id' => $validated['formation_id'],
            'annee' => now(),
        ]);

        return redirect()->route('diplomes.create')
            ->with('toastr', [
                'type' => 'success',
                'message' => 'Candidat ajouté avec succès'
            ]);
    }

    public function edit($id)
    {
        $candidat = Candidat::findOrFail($id);
        $formations = Formation::all();
        return view('candidats.edit', compact('candidat', 'formations'));
    }

    public function update(UpdateCandidatRequest $request, $id)
    {
        $candidat = Candidat::findOrFail($id);
        $validated = $request->validated();
        $baseName = strtoupper($validated['CNE']) . strtolower(str_replace(' ', '', $validated['nom'])) . strtolower(str_replace(' ', '', $validated['prenom']));
        $timestamp = now()->format('YmdHis');

        if ($request->hasFile('CV')) {
            if ($candidat->CV && Storage::disk('public')->exists($candidat->CV)) {
                Storage::disk('public')->delete($candidat->CV);
            }
            $CVExtension = $request->file('CV')->getClientOriginalExtension();
            $CVName = $baseName . '_CV_' . $timestamp . '.' . $CVExtension;
            $validated['CV'] = $request->file('CV')->storeAs('CV', $CVName, 'public');
        }

        if ($request->hasFile('demande')) {
            if ($candidat->demande && Storage::disk('public')->exists($candidat->demande)) {
                Storage::disk('public')->delete($candidat->demande);
            }
            $demandeExtension = $request->file('demande')->getClientOriginalExtension();
            $demandeName = $baseName . '_demande_' . $timestamp . '.' . $demandeExtension;
            $validated['demande'] = $request->file('demande')->storeAs('demande', $demandeName, 'public');
        } else {
            $validated['demande'] = $candidat->demande ?? null;
        }

        if ($request->hasFile('scan_cartid')) {
            if ($candidat->scan_cartid && Storage::disk('public')->exists($candidat->scan_cartid)) {
                Storage::disk('public')->delete($candidat->scan_cartid);
            }
            $cinExtension = $request->file('scan_cartid')->getClientOriginalExtension();
            $cinName = $baseName . '_cin_' . $timestamp . '.' . $cinExtension;
            $validated['scan_cartid'] = $request->file('scan_cartid')->storeAs('cart', $cinName, 'public');
        } else {
            $validated['scan_cartid'] = $candidat->scan_cartid ?? null;
        }

        if ($request->hasFile('photo')) {
            if ($candidat->photo && Storage::disk('public')->exists($candidat->photo)) {
                Storage::disk('public')->delete($candidat->photo);
            }
            $photoExtension = $request->file('photo')->getClientOriginalExtension();
            $photoName = $baseName . '_photo_' . $timestamp . '.' . $photoExtension;
            $validated['photo'] = $request->file('photo')->storeAs('photos', $photoName, 'public');
        } else {
            $validated['photo'] = $candidat->photo ?? null;
        }

        if ($request->hasFile('scan_bac')) {
            if ($candidat->scan_bac && Storage::disk('public')->exists($candidat->scan_bac)) {
                Storage::disk('public')->delete($candidat->scan_bac);
            }
            $bacExtension = $request->file('scan_bac')->getClientOriginalExtension();
            $bacName = $baseName . '_bac_' . $timestamp . '.' . $bacExtension;
            $validated['scan_bac'] = $request->file('scan_bac')->storeAs('bac', $bacName, 'public');
        } else {
            $validated['scan_bac'] = $candidat->scan_bac ?? null;
        }

        $candidat->update($validated);

        if ($request->has('formation_id')) {
            $inscription = Inscription::where('candidat_id', $candidat->id)->first();
            if ($inscription) {
                $inscription->update([
                    'formation_id' => $validated['formation_id'],
                    'annee' => now(),
                ]);
            }
        }

        return redirect()->route('candidats.index')
            ->with('toastr', [
                'type' => 'success',
                'message' => 'Candidat mis à jour avec succès'
            ]);
    }

    public function destroy($id)
    {
        $candidat = Candidat::findOrFail($id);
        $candidat->diplomes()->delete();
        $candidat->stages()->delete();
        $candidat->experiences()->delete();
        $candidat->attestations()->delete();

        if ($candidat->CV && Storage::disk('public')->exists($candidat->CV)) {
            Storage::disk('public')->delete($candidat->CV);
        }
        if ($candidat->demande && Storage::disk('public')->exists($candidat->demande)) {
            Storage::disk('public')->delete($candidat->demande);
        }
        if ($candidat->scan_cartid && Storage::disk('public')->exists($candidat->scan_cartid)) {
            Storage::disk('public')->delete($candidat->scan_cartid);
        }
        if ($candidat->photo && Storage::disk('public')->exists($candidat->photo)) {
            Storage::disk('public')->delete($candidat->photo);
        }
        if ($candidat->scan_bac && Storage::disk('public')->exists($candidat->scan_bac)) {
            Storage::disk('public')->delete($candidat->scan_bac);
        }

        $candidat->delete();

        return redirect()->route('candidats.index')
            ->with('toastr', [
                'type' => 'success',
                'message' => 'Candidat supprimé avec succès'
            ]);
    }
}