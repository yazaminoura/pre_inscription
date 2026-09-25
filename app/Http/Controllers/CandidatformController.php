<?php

namespace App\Http\Controllers;

use App\Mail\InscriptionConfirmation;
use App\Models\Attestation;
use App\Models\Candidat;
use App\Models\Diplome;
use App\Models\Experience;
use App\Models\Formation;
use App\Models\Inscription;
use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class CandidatformController extends Controller
{
    // Ordre des étapes : du plus général au plus détaillé, les pièces jointes à la fin.
    public const ETAPES = [
        1 => 'Formation',
        2 => 'Identité',
        3 => 'Coordonnées',
        4 => 'Parcours',
        5 => 'Expérience',
        6 => 'Documents',
    ];

    private const FICHIER = 'file|mimes:pdf,jpg,jpeg,png|max:10240';
    private const MAX_ENTREES = 3; // stages / expériences / attestations

    /** Page d'accueil : les formations ouvertes. */
    public function accueil()
    {
        $formations = $this->formationsOuvertes()->groupBy('type_formation');
        $aVenir = Formation::whereDate('date_debut', '>', today())->orderBy('date_debut')->get();

        return view('candidateur.accueil', compact('formations', 'aVenir'));
    }

    public function showForm(Request $request)
    {
        $data = session('form_data', []);

        // Arrivée depuis la page d'accueil avec une formation choisie
        if ($request->filled('formation')) {
            $formation = $this->formationsOuvertes()->firstWhere('id', $request->integer('formation'));
            if ($formation) {
                $data['titre_id'] = $formation->id;
                session(['form_data' => $data]);
            }
            return redirect()->route('candidat.form', ['step' => 1]);
        }

        $step = max(1, min(6, (int) $request->query('step', 1)));
        $atteinte = $this->etapeAtteinte($data);
        if ($step > $atteinte) {
            return redirect()->route('candidat.form', ['step' => $atteinte]);
        }

        $formations = $this->formationsOuvertes();
        $formationChoisie = $formations->firstWhere('id', $data['titre_id'] ?? null);

        return view('candidateur.candidat.form', [
            'step' => $step,
            'etapes' => self::ETAPES,
            'formations' => $formations,
            'formationChoisie' => $formationChoisie,
            'data' => $data,
        ]);
    }

    public function submitStep(Request $request)
    {
        $step = max(1, min(6, (int) $request->input('step', 1)));
        $data = session('form_data', []);

        if ($step > $this->etapeAtteinte($data)) {
            return redirect()->route('candidat.form', ['step' => $this->etapeAtteinte($data)]);
        }

        $validated = $request->validate($this->regles($step, $data), [], $this->libelles());

        $data = match ($step) {
            1 => $this->etapeFormation($validated, $data),
            2 => $this->etapeIdentite($validated, $data),
            3 => array_merge($data, $validated),
            4 => $this->etapeParcours($request, $validated, $data),
            5 => $this->etapeExperience($request, $data),
            6 => $this->etapeDocuments($request, $data),
        };
        $data['_etape'] = max($data['_etape'] ?? 1, $step + 1);
        session(['form_data' => $data]);

        if ($step < 6) {
            return redirect()->route('candidat.form', ['step' => $step + 1]);
        }

        try {
            // Tout ou rien : pas de candidat à moitié enregistré si une étape échoue
            $inscription = DB::transaction(fn () => $this->enregistrer($data));
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('Erreur lors de l\'enregistrement de la préinscription : ' . $e->getMessage());
            return redirect()->route('candidat.form', ['step' => 6])
                ->with('error', 'Une erreur est survenue lors de l\'enregistrement. Veuillez réessayer.');
        }

        $this->envoyerConfirmation($inscription);
        session()->forget('form_data');

        return redirect()->route('candidat.merci')->with('inscription_ok', $inscription->reference);
    }

    public function merci()
    {
        $reference = session('inscription_ok');
        if (!$reference) {
            return redirect()->route('accueil');
        }
        $inscription = Inscription::with('formation', 'candidat')->where('reference', $reference)->first();

        return view('candidateur.merci', compact('inscription'));
    }

    /** Recommencer : vide le brouillon en cours. */
    public function recommencer()
    {
        session()->forget('form_data');

        return redirect()->route('accueil');
    }

    // ------------------------------------------------------------------

    private function formationsOuvertes()
    {
        return Formation::whereDate('date_debut', '<=', today())
            ->whereDate('date_fin', '>=', today())
            ->orderBy('type_formation')
            ->orderBy('titre')
            ->get();
    }

    /** Dernière étape à laquelle le candidat a le droit d'accéder. */
    private function etapeAtteinte(array $data): int
    {
        return min(6, max(1, (int) ($data['_etape'] ?? 1)));
    }

    private function regles(int $step, array $data): array
    {
        // Un fichier déjà envoyé n'est plus obligatoire quand on revient sur l'étape
        $fichier = fn (string $cle, bool $obligatoire = true) =>
            ($obligatoire && empty($data[$cle]) ? 'required|' : 'nullable|') . self::FICHIER;
        $annee = 'integer|min:1970|max:' . now()->year;

        return match ($step) {
            1 => [
                'titre_id' => 'required|integer',
            ],
            2 => [
                'nom' => 'required|string|max:50',
                'prenom' => 'required|string|max:50',
                'nom_ar' => 'nullable|string|max:50',
                'prenom_ar' => 'nullable|string|max:50',
                'CNE' => 'required|string|max:20',
                'CIN' => 'required|string|max:20',
                'date_naissance' => 'required|date|before:-15 years',
                'sex' => 'required|in:Homme,Femme',
                'nationalite' => 'required|string|max:50',
                'ville_naissance' => 'required|string|max:50',
                'ville_naissance_ar' => 'nullable|string|max:50',
                'pay_naissance' => 'required|string|max:50',
            ],
            3 => [
                'email' => 'required|email:rfc|max:100',
                'telephone_mob' => ['required', 'regex:/^\+?[0-9 ]{8,17}$/'],
                'telephone_fix' => ['nullable', 'regex:/^\+?[0-9 ]{8,17}$/'],
                'adresse' => 'required|string|max:255',
                'ville' => 'required|string|max:50',
                'province' => 'required|string|max:50',
                'pays' => 'required|string|max:50',
            ],
            4 => [
                'serie_bac' => 'required|string|max:50',
                'annee_bac' => "required|$annee",
                'scan_bac' => $fichier('scan_bac'),
                'type_diplome_bac_2' => 'required|string|max:100',
                'filiere_diplome_bac_2' => 'required|string|max:100',
                'etablissement_bac_2' => 'required|string|max:100',
                'annee_diplome_bac_2' => "required|$annee",
                'scan_bac_2' => $fichier('scan_bac_2'),
                'type_diplome_bac_3' => 'nullable|string|max:100',
                'filiere_diplome_bac_3' => 'nullable|required_with:type_diplome_bac_3|string|max:100',
                'etablissement_bac_3' => 'nullable|required_with:type_diplome_bac_3|string|max:100',
                'annee_diplome_bac_3' => "nullable|required_with:type_diplome_bac_3|$annee",
                'scan_bac_3' => $fichier('scan_bac_3', false),
            ],
            5 => collect(['stages', 'experiences', 'attestations'])->flatMap(fn ($liste) => [
                $liste => 'nullable|array|max:' . self::MAX_ENTREES,
                "$liste.*.attestation" => 'nullable|' . self::FICHIER,
                "$liste.*.description" => 'nullable|string|max:255',
            ] + ($liste === 'attestations' ? [
                "$liste.*.type_attestation" => 'required|string|max:100',
            ] : [
                "$liste.*.fonction" => 'required|string|max:100',
                "$liste.*.etablissement" => 'required|string|max:100',
                "$liste.*.periode" => 'nullable|string|max:100',
                "$liste.*.secteur_activite" => 'nullable|string|max:100',
            ]))->all(),
            6 => [
                'CV' => $fichier('CV'),
                'demande' => $fichier('demande'),
                'scan_cartid' => $fichier('scan_cartid'),
                'photo' => ($data['photo'] ?? null ? 'nullable' : 'required') . '|file|mimes:jpg,jpeg,png|max:5120',
                'certifie' => 'accepted',
            ],
        };
    }

    private function libelles(): array
    {
        return [
            'titre_id' => 'formation', 'nom_ar' => 'nom (arabe)', 'prenom_ar' => 'prénom (arabe)',
            'date_naissance' => 'date de naissance', 'sex' => 'sexe', 'nationalite' => 'nationalité',
            'ville_naissance' => 'ville de naissance', 'pay_naissance' => 'pays de naissance',
            'telephone_mob' => 'téléphone mobile', 'telephone_fix' => 'téléphone fixe',
            'serie_bac' => 'série du bac', 'annee_bac' => 'année du bac', 'scan_bac' => 'scan du bac',
            'type_diplome_bac_2' => 'type de diplôme Bac+2', 'filiere_diplome_bac_2' => 'filière Bac+2',
            'etablissement_bac_2' => 'établissement Bac+2', 'annee_diplome_bac_2' => 'année Bac+2', 'scan_bac_2' => 'scan du diplôme Bac+2',
            'type_diplome_bac_3' => 'type de diplôme Bac+3', 'filiere_diplome_bac_3' => 'filière Bac+3',
            'etablissement_bac_3' => 'établissement Bac+3', 'annee_diplome_bac_3' => 'année Bac+3', 'scan_bac_3' => 'scan du diplôme Bac+3',
            'stages.*.fonction' => 'intitulé du stage', 'stages.*.etablissement' => 'organisme du stage',
            'experiences.*.fonction' => 'poste', 'experiences.*.etablissement' => 'employeur',
            'attestations.*.type_attestation' => 'type d\'attestation',
            'CV' => 'CV', 'demande' => 'lettre de demande', 'scan_cartid' => 'pièce d\'identité',
            'certifie' => 'attestation sur l\'honneur',
        ];
    }

    private function etapeFormation(array $validated, array $data): array
    {
        $formation = $this->formationsOuvertes()->firstWhere('id', (int) $validated['titre_id']);
        if (!$formation) {
            throw ValidationException::withMessages(['titre_id' => 'Cette formation n\'est pas ouverte aux préinscriptions.']);
        }
        $data['titre_id'] = $formation->id;
        $data['type_formation'] = $formation->type_formation;

        return $data;
    }

    private function etapeIdentite(array $validated, array $data): array
    {
        $validated['CNE'] = strtoupper(trim($validated['CNE']));
        $validated['CIN'] = strtoupper(trim($validated['CIN']));

        $dejaInscrit = Inscription::where('formation_id', $data['titre_id'] ?? 0)
            ->whereHas('candidat', fn ($q) => $q->where('CNE', $validated['CNE']))
            ->value('reference');
        if ($dejaInscrit) {
            throw ValidationException::withMessages([
                'CNE' => "Une préinscription existe déjà pour ce CNE dans cette formation (réf. $dejaInscrit).",
            ]);
        }

        return array_merge($data, $validated);
    }

    private function etapeParcours(Request $request, array $validated, array $data): array
    {
        foreach (['scan_bac' => 'bac', 'scan_bac_2' => 'bac_2', 'scan_bac_3' => 'bac_3'] as $champ => $dossier) {
            unset($validated[$champ]);
            if ($request->hasFile($champ)) {
                $data[$champ] = $this->stocker($request->file($champ), $dossier, $data, $champ);
            }
        }

        return array_merge($data, $validated);
    }

    private function etapeExperience(Request $request, array $data): array
    {
        foreach (['stages' => 'stages', 'experiences' => 'experiences', 'attestations' => 'attestations'] as $liste => $dossier) {
            // Chemins de fichiers déjà envoyés pour cette liste : seuls ceux-là peuvent être repris
            $connus = collect($data[$liste] ?? [])->pluck('attestation')->filter()->all();
            $entrees = [];
            foreach ($request->input($liste, []) as $index => $entree) {
                $fichier = $request->file("$liste.$index.attestation");
                $ancien = $entree['attestation_actuelle'] ?? null;
                unset($entree['attestation_actuelle']);
                $entree['attestation'] = $fichier
                    ? $this->stocker($fichier, $dossier, $data, $liste . '_' . (count($entrees) + 1))
                    : (in_array($ancien, $connus, true) ? $ancien : null);
                $entrees[] = $entree;
            }
            $data[$liste] = array_slice($entrees, 0, self::MAX_ENTREES);
        }

        return $data;
    }

    private function etapeDocuments(Request $request, array $data): array
    {
        foreach (['CV' => 'CV', 'demande' => 'demande', 'scan_cartid' => 'cart', 'photo' => 'photos'] as $champ => $dossier) {
            if ($request->hasFile($champ)) {
                $data[$champ] = $this->stocker($request->file($champ), $dossier, $data, $champ);
            }
        }

        return $data;
    }

    private function stocker(UploadedFile $fichier, string $dossier, array $data, string $type): string
    {
        $base = strtoupper($data['CNE'] ?? 'X') . '_' . preg_replace('/[^a-z]/', '', strtolower($data['nom'] ?? 'candidat'));
        $nom = $base . '_' . $type . '_' . now()->format('YmdHis') . '_' . substr(bin2hex(random_bytes(3)), 0, 6);

        return $fichier->storeAs($dossier, $nom . '.' . strtolower($fichier->getClientOriginalExtension()), 'public');
    }

    private function enregistrer(array $data): Inscription
    {
        // Dernière vérification (la formation a pu fermer, ou un doublon a pu arriver entre-temps)
        $this->etapeFormation(['titre_id' => $data['titre_id'] ?? 0], $data);
        $this->etapeIdentite(['CNE' => $data['CNE'], 'CIN' => $data['CIN']], $data);

        $candidat = Candidat::create([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'nom_ar' => $data['nom_ar'] ?? '',
            'prenom_ar' => $data['prenom_ar'] ?? '',
            'CNE' => $data['CNE'],
            'CIN' => $data['CIN'],
            'email' => $data['email'],
            'date_naissance' => $data['date_naissance'],
            'ville_naissance' => $data['ville_naissance'],
            'ville_naissance_ar' => $data['ville_naissance_ar'] ?? '',
            'province' => $data['province'],
            'pay_naissance' => $data['pay_naissance'],
            'nationalite' => $data['nationalite'],
            'sexe' => $data['sex'] === 'Femme' ? 'F' : 'M',
            'telephone_mob' => $data['telephone_mob'],
            'telephone_fix' => $data['telephone_fix'] ?? null,
            'adresse' => $data['adresse'],
            'ville' => $data['ville'],
            'pays' => $data['pays'],
            'CV' => $data['CV'],
            'demande' => $data['demande'],
            'scan_cartid' => $data['scan_cartid'],
            'photo' => $data['photo'],
            'serie_bac' => $data['serie_bac'],
            'annee_bac' => $data['annee_bac'],
            'scan_bac' => $data['scan_bac'],
        ]);

        Diplome::create([
            'candidat_id' => $candidat->id,
            'type_diplome_bac_2' => $data['type_diplome_bac_2'],
            'annee_diplome_bac_2' => $data['annee_diplome_bac_2'],
            'filiere_diplome_bac_2' => $data['filiere_diplome_bac_2'],
            'etablissement_bac_2' => $data['etablissement_bac_2'],
            'scan_bac_2' => $data['scan_bac_2'],
            'type_diplome_bac_3' => $data['type_diplome_bac_3'] ?? null,
            'annee_diplome_bac_3' => $data['annee_diplome_bac_3'] ?? null,
            'filiere_diplome_bac_3' => $data['filiere_diplome_bac_3'] ?? null,
            'etablissement_bac_3' => $data['etablissement_bac_3'] ?? null,
            'scan_bac_3' => $data['scan_bac_3'] ?? null,
        ]);

        $champs = [
            'stages' => ['fonction', 'etablissement', 'periode', 'secteur_activite', 'description', 'attestation'],
            'experiences' => ['fonction', 'etablissement', 'periode', 'secteur_activite', 'description', 'attestation'],
            'attestations' => ['type_attestation', 'description', 'attestation'],
        ];
        $modeles = ['stages' => Stage::class, 'experiences' => Experience::class, 'attestations' => Attestation::class];
        foreach ($champs as $liste => $cles) {
            foreach ($data[$liste] ?? [] as $entree) {
                $modeles[$liste]::create(['candidat_id' => $candidat->id] + collect($cles)->mapWithKeys(fn ($c) => [$c => $entree[$c] ?? null])->all());
            }
        }

        return Inscription::create([
            'formation_id' => $data['titre_id'],
            'candidat_id' => $candidat->id,
            'annee' => now()->format('Y-m-d'),
        ]);
    }

    private function envoyerConfirmation(Inscription $inscription): void
    {
        $inscription->load('candidat.diplomes', 'candidat.stages', 'candidat.experiences', 'candidat.attestations', 'formation');
        $c = $inscription->candidat;
        $d = $c->diplomes->first();

        $diplomes = [['type' => 'Baccalauréat', 'filiere' => $c->serie_bac, 'annee' => $c->annee_bac, 'etablissement' => '—']];
        if ($d) {
            $diplomes[] = ['type' => $d->type_diplome_bac_2, 'filiere' => $d->filiere_diplome_bac_2, 'annee' => $d->annee_diplome_bac_2, 'etablissement' => $d->etablissement_bac_2];
            if ($d->type_diplome_bac_3) {
                $diplomes[] = ['type' => $d->type_diplome_bac_3, 'filiere' => $d->filiere_diplome_bac_3, 'annee' => $d->annee_diplome_bac_3, 'etablissement' => $d->etablissement_bac_3];
            }
        }

        try {
            Mail::to($c->email)->send(new InscriptionConfirmation(
                trim($c->nom . ' ' . $c->prenom),
                $c->email,
                [
                    'nom' => $c->nom, 'prenom' => $c->prenom, 'CNE' => $c->CNE, 'CIN' => $c->CIN,
                    'email' => $c->email, 'telephone' => $c->telephone_mob, 'date_naissance' => $c->date_naissance,
                    'ville_naissance' => $c->ville_naissance, 'nationalite' => $c->nationalite,
                    'sex' => $c->sexe === 'F' ? 'Femme' : 'Homme', 'adresse' => $c->adresse, 'ville' => $c->ville, 'pays' => $c->pays,
                ],
                [
                    'type_formation' => $inscription->formation->type_formation,
                    'titre' => $inscription->formation->titre,
                    'date_debut' => $inscription->formation->date_debut,
                    'date_fin' => $inscription->formation->date_fin,
                ],
                $diplomes,
                $c->stages->toArray(),
                $c->experiences->toArray(),
                $c->attestations->toArray()
            ));
        } catch (\Throwable $e) {
            // L'inscription est valable même si l'email ne part pas
            Log::error('Erreur lors de l\'envoi de l\'email de confirmation : ' . $e->getMessage());
        }
    }
}
