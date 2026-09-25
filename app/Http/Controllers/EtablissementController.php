<?php

namespace App\Http\Controllers;

use App\Models\Etablissement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EtablissementController extends Controller
{
    public function edit()
    {
        return view('utilisateur.etablissement.edit', ['etablissement' => Etablissement::actuel()]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'sigle' => 'nullable|string|max:30',
            'slogan' => 'nullable|string|max:255',
            'presentation' => 'nullable|string|max:5000',
            'ville' => 'nullable|string|max:100',
            'pays' => 'nullable|string|max:100',
            'adresse' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:40',
            'email' => 'nullable|email|max:255',
            'site' => 'nullable|url|max:255',
            'couleur' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,webp,svg|max:2048',
            'retirer_logo' => 'nullable|boolean',
            'traductions' => 'nullable|array',
            'traductions.*' => 'nullable|array',
            'traductions.*.*' => 'nullable|string|max:5000',
        ], [], [
            'nom' => 'nom de l\'établissement', 'site' => 'site web', 'couleur' => 'couleur', 'logo' => 'logo',
        ]);

        $etablissement = Etablissement::actuel();

        if ($request->boolean('retirer_logo') && $etablissement->logo) {
            Storage::disk('public')->delete($etablissement->logo);
            $validated['logo'] = null;
        }
        if ($request->hasFile('logo')) {
            if ($etablissement->logo) {
                Storage::disk('public')->delete($etablissement->logo);
            }
            $validated['logo'] = $request->file('logo')->store('etablissement', 'public');
        } elseif (!$request->boolean('retirer_logo')) {
            unset($validated['logo']);
        }
        unset($validated['retirer_logo']);
        $validated['traductions'] = Etablissement::nettoyerTraductions($validated['traductions'] ?? null);

        $etablissement->fill($validated)->save();

        return redirect()->route('etablissement.edit')->with('toastr', [
            'type' => 'success',
            'message' => 'Informations de l\'établissement enregistrées',
        ]);
    }

    /** Envoie un email de test à l'administrateur connecté. */
    public function testerEmail(Request $request)
    {
        try {
            \Illuminate\Support\Facades\Mail::to($request->user()->email)->send(new \App\Mail\EmailTest());
        } catch (\Throwable $e) {
            return back()->with('email_test', ['ok' => false, 'message' => $e->getMessage()]);
        }

        return back()->with('email_test', config('mail.default') === 'log'
            ? ['ok' => false, 'message' => 'L\'envoi est en mode « log » : l\'email a été écrit dans le journal, pas envoyé. Renseignez le serveur SMTP dans le fichier .env.']
            : ['ok' => true, 'message' => 'Email envoyé à ' . $request->user()->email . '. Vérifiez votre boîte de réception (et les spams).']);
    }
}
