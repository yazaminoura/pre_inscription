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

    /** Fiche publique d'une formation (ouverte ou à venir ; une formation clôturée n'est plus affichée). */
    public function formation(Formation $formation)
    {
        abort_if(today()->gt(\Carbon\Carbon::parse($formation->date_fin)), 404);

        $ouverte = today()->gte(\Carbon\Carbon::parse($formation->date_debut));
        $autres = $this->formationsOuvertes()->where('id', '!=', $formation->id)->take(3);

        return view('candidateur.formation', compact('formation', 'ouverte', 'autres'));
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
            'parcours' => $this->exigencesParcours($data),
        ]);
    }

    public function submitStep(Request $request)
    {
        $step = max(1, min(6, (int) $request->input('step', 1)));
        $data = session('form_data', []);

        if ($step > $this->etapeAtteinte($data)) {
            return redirect()->route('candidat.form', ['step' => $this->etapeAtteinte($data)]);
        }

        $validated = $request->validate($this->regles($step, $data), $this->messages(), array_map('__', $this->libelles()));

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
            // Vérification finale refusée : renvoyer à l'étape où se trouve le champ en cause
            $cle = array_key_first($e->errors());
            $etape = str_starts_with($cle, 'titre') ? 1 : (in_array($cle, ['CNE', 'CIN'], true) ? 2 : (str_contains($cle, 'diplome') ? 4 : 6));
            return redirect()->route('candidat.form', ['step' => $etape])->withErrors($e->errors());
        } catch (\Throwable $e) {
            Log::error('Erreur lors de l\'enregistrement de la préinscription : ' . $e->getMessage());
            return redirect()->route('candidat.form', ['step' => 6])
                ->with('error', __("Une erreur est survenue lors de l'enregistrement. Veuillez réessayer."));
        }

        $this->envoyerConfirmation($inscription);
        session()->forget('form_data');
        RecuController::autoriser($inscription->reference);

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
        // Types dans l'ordre de la config (du DUT au doctorat), puis par intitulé
        $ordre = array_flip(config('etablissement.types_formation'));

        return Formation::whereDate('date_debut', '<=', today())
            ->whereDate('date_fin', '>=', today())
            ->orderBy('titre')
            ->get()
            ->sortBy(fn ($f) => $ordre[$f->type_formation] ?? PHP_INT_MAX)
            ->values();
    }

    /** Dernière étape à laquelle le candidat a le droit d'accéder. */
    private function etapeAtteinte(array $data): int
    {
        return min(6, max(1, (int) ($data['_etape'] ?? 1)));
    }

    /**
     * Ce que l'étape « Parcours » demande pour la formation choisie :
     * - afficher : sections Bac+2 / Bac+3 visibles (inutiles pour une formation recrutant après le bac, sans voie alternative) ;
     * - experience : question « années d'expérience » (seulement s'il existe une voie alternative) ;
     * - condition : texte de la condition d'accès, montré au candidat.
     * Aucun diplôme n'est exigé individuellement : c'est le plus haut diplôme (ou la voie alternative) qui compte.
     */
    private function exigencesParcours(array $data): array
    {
        $formation = Formation::find($data['titre_id'] ?? 0);

        return [
            'afficher' => $formation && ($formation->rangAcces() >= 2 || ($formation->rangAlternatif() ?? 0) >= 2),
            'experience' => $formation && $formation->rangAlternatif() !== null,
            'condition' => $formation?->conditionAcces() ?? '',
        ];
    }

    /** Refuse l'étape « Parcours » si le candidat n'atteint pas la condition d'accès de la formation. */
    private function verifierRecevabilite(array $data): void
    {
        $formation = Formation::find($data['titre_id'] ?? 0);
        if ($formation && !$formation->estRecevable($this->plusHautDiplome($data), $data['annees_experience'] ?? null)) {
            throw ValidationException::withMessages([
                'type_diplome_bac_3' => __('Cette formation demande : :condition.', ['condition' => $formation->conditionAcces()]),
            ]);
        }
    }

    /** Plus haut diplôme renseigné : 3 (Bac+3), 2 (Bac+2) ou 0 (bac seulement). */
    private function plusHautDiplome(array $data): int
    {
        return filled($data['type_diplome_bac_3'] ?? null) ? 3 : (filled($data['type_diplome_bac_2'] ?? null) ? 2 : 0);
    }

    // Champs en arabe : lettres arabes seulement ; leurs équivalents en français : pas de lettres arabes
    private const CHAMPS_ARABES = ['nom_ar', 'prenom_ar', 'ville_naissance_ar'];
    private const CHAMPS_LATINS = ['nom', 'prenom', 'ville_naissance', 'pay_naissance', 'nationalite'];
    private const ARABE = "/^[\p{Arabic}\s'\-]*$/u";
    private const SANS_ARABE = '/\p{Arabic}/u';

    private function messages(): array
    {
        $messages = [];
        foreach (self::CHAMPS_ARABES as $champ) {
            $messages["$champ.regex"] = __("Ce champ s'écrit en lettres arabes.");
        }
        foreach (self::CHAMPS_LATINS as $champ) {
            $messages["$champ.not_regex"] = __("Ce champ s'écrit en lettres latines.");
        }

        return $messages;
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
                'nom' => ['required', 'string', 'max:50', 'not_regex:' . self::SANS_ARABE],
                'prenom' => ['required', 'string', 'max:50', 'not_regex:' . self::SANS_ARABE],
                'nom_ar' => ['nullable', 'string', 'max:50', 'regex:' . self::ARABE],
                'prenom_ar' => ['nullable', 'string', 'max:50', 'regex:' . self::ARABE],
                'CNE' => 'required|string|max:20',
                'CIN' => 'required|string|max:20',
                'date_naissance' => 'required|date|before:-15 years',
                'sex' => 'required|in:Homme,Femme',
                'nationalite' => ['required', 'string', 'max:50', 'not_regex:' . self::SANS_ARABE],
                'ville_naissance' => ['required', 'string', 'max:50', 'not_regex:' . self::SANS_ARABE],
                'ville_naissance_ar' => ['nullable', 'string', 'max:50', 'regex:' . self::ARABE],
                'pay_naissance' => ['required', 'string', 'max:50', 'not_regex:' . self::SANS_ARABE],
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
            4 => array_merge([
                'serie_bac' => 'required|string|max:50',
                'annee_bac' => "required|$annee",
                'scan_bac' => $fichier('scan_bac'),
            ], $this->exigencesParcours($data)['experience'] ? ['annees_experience' => 'nullable|integer|min:0|max:50'] : [],
            ...array_map(function ($n) use ($data, $annee) {
                // Bac+2 / Bac+3 : chacun facultatif, mais complet s'il est commencé. La recevabilité (plus haut diplôme
                // ou voie alternative) est vérifiée ensuite, dans etapeParcours().
                if (!$this->exigencesParcours($data)['afficher']) {
                    return [];
                }
                $si = "nullable|required_with:type_diplome_bac_$n|";
                return [
                    "type_diplome_bac_$n" => 'nullable|string|max:100',
                    "filiere_diplome_bac_$n" => $si . 'string|max:100',
                    "etablissement_bac_$n" => $si . 'string|max:100',
                    "annee_diplome_bac_$n" => $si . $annee,
                    "scan_bac_$n" => (empty($data["scan_bac_$n"]) ? $si : 'nullable|') . self::FICHIER,
                ];
            }, [2, 3])),
            5 => collect(['stages', 'experiences', 'attestations'])->flatMap(fn ($liste) => [
                $liste => 'nullable|array|max:' . self::MAX_ENTREES,
                "$liste.*.attestation" => 'nullable|' . self::FICHIER,
                "$liste.*.description" => 'nullable|string|max:255',
            ] + ($liste === 'attestations' ? [
                "$liste.*.type_attestation" => 'required|string|max:100',
            ] : [
                "$liste.*.fonction" => 'required|string|max:100',
                "$liste.*.etablissement" => 'required|string|max:100',
                "$liste.*.periode" => 'nullable|integer|min:1|max:600', // durée en mois
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
            'nom' => 'nom', 'prenom' => 'prénom', 'CNE' => 'CNE', 'CIN' => 'CIN', 'email' => 'email', 'annees_experience' => 'années d\'expérience',
            'adresse' => 'adresse', 'ville' => 'ville', 'province' => 'province', 'pays' => 'pays', 'photo' => 'photo',
            'stages.*.periode' => 'période', 'stages.*.secteur_activite' => 'secteur d\'activité', 'stages.*.description' => 'missions',
            'experiences.*.periode' => 'période', 'experiences.*.secteur_activite' => 'secteur d\'activité', 'experiences.*.description' => 'missions',
            'attestations.*.description' => 'précision', 'stages.*.attestation' => 'justificatif',
            'experiences.*.attestation' => 'justificatif', 'attestations.*.attestation' => 'justificatif',
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
            throw ValidationException::withMessages(['titre_id' => __("Cette formation n'est pas ouverte aux préinscriptions.")]);
        }
        // Autre niveau d'accès que la formation choisie avant : l'étape « Parcours » est à refaire
        $avant = Formation::find($data['titre_id'] ?? 0);
        if ($avant && $avant->conditionAcces() !== $formation->conditionAcces() && ($data['_etape'] ?? 1) > 4) {
            $data['_etape'] = 4;
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
                'CNE' => __('Une préinscription existe déjà pour ce CNE dans cette formation (réf. :ref).', ['ref' => $dejaInscrit]),
            ]);
        }

        return array_merge($data, $validated);
    }

    private function etapeParcours(Request $request, array $validated, array $data): array
    {
        // Un diplôme dont le type est vidé est retiré en entier
        foreach ([2, 3] as $n) {
            if (array_key_exists("type_diplome_bac_$n", $validated) && blank($validated["type_diplome_bac_$n"])) {
                foreach (['filiere_diplome', 'etablissement', 'annee_diplome', 'scan'] as $champ) {
                    unset($data["{$champ}_bac_$n"], $validated["{$champ}_bac_$n"]);
                }
            }
        }

        // Recevabilité vérifiée avant d'enregistrer les fichiers : plus haut diplôme, ou voie alternative
        $apres = array_merge($data, array_filter($validated, fn ($v) => !$v instanceof UploadedFile));
        $this->verifierRecevabilite($apres);

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

        return $fichier->storeAs($dossier, $nom . '.' . strtolower($fichier->getClientOriginalExtension()), 'dossiers');
    }

    private function enregistrer(array $data): Inscription
    {
        // Dernière vérification (la formation a pu fermer, ou un doublon a pu arriver entre-temps)
        $this->etapeFormation(['titre_id' => $data['titre_id'] ?? 0], $data);
        $this->etapeIdentite(['CNE' => $data['CNE'], 'CIN' => $data['CIN']], $data);
        $this->verifierRecevabilite($data);

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
            'annees_experience' => $this->exigencesParcours($data)['experience'] ? ($data['annees_experience'] ?? null) : null,
        ]);

        // Diplômes post-bac : ceux que le candidat a renseignés (un Bac+3 seul suffit, pas besoin du Bac+2)
        $montrer = $this->exigencesParcours($data)['afficher'];
        $diplome = ['candidat_id' => $candidat->id];
        foreach ([2, 3] as $n) {
            foreach (['type_diplome', 'annee_diplome', 'filiere_diplome', 'etablissement', 'scan'] as $champ) {
                $diplome["{$champ}_bac_$n"] = $montrer ? ($data["{$champ}_bac_$n"] ?? null) : null;
            }
        }
        if (filled($diplome['type_diplome_bac_2']) || filled($diplome['type_diplome_bac_3'])) {
            Diplome::create($diplome);
        }

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
