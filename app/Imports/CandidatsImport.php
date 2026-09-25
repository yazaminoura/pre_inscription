<?php

namespace App\Imports;

use App\Models\Candidat;
use App\Models\Formation;
use App\Models\Inscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date as DateExcel;

/**
 * Import de candidats depuis un fichier Excel/CSV dans une formation.
 * Les colonnes sont reconnues par leur titre : un fichier exporté peut être réimporté tel quel.
 * Un candidat déjà connu (même CNE ; l'email peut être partagé) est mis à jour, pas dupliqué. Les pièces jointes ne s'importent pas.
 */
class CandidatsImport implements ToArray
{
    // Titre de colonne => champ ; le même titre que dans l'export
    public const COLONNES = [
        'Nom' => 'nom',
        'Prénom' => 'prenom',
        'الاسم العائلي' => 'nom_ar',
        'الاسم الشخصي' => 'prenom_ar',
        'CNE' => 'CNE',
        'CIN' => 'CIN',
        'Email' => 'email',
        'Date de naissance' => 'date_naissance',
        'Sexe' => 'sexe',
        'Ville naissance' => 'ville_naissance',
        'مدينة الولادة' => 'ville_naissance_ar',
        'Province' => 'province',
        'Pays naissance' => 'pay_naissance',
        'Nationalité' => 'nationalite',
        'Téléphone mobile' => 'telephone_mob',
        'Téléphone fixe' => 'telephone_fix',
        'Adresse' => 'adresse',
        'Ville' => 'ville',
        'Pays' => 'pays',
        'Bac Type' => 'serie_bac',
        'Bac Year' => 'annee_bac',
        'Type Bac2' => 'type_diplome_bac_2',
        'Year Bac2' => 'annee_diplome_bac_2',
        'Filière Bac2' => 'filiere_diplome_bac_2',
        'Establishment Bac2' => 'etablissement_bac_2',
        'Type Bac3' => 'type_diplome_bac_3',
        'Year Bac3' => 'annee_diplome_bac_3',
        'Filière Bac3' => 'filiere_diplome_bac_3',
        'Establishment Bac3' => 'etablissement_bac_3',
        'Statut' => 'statut',
        'Motif / précision' => 'motif',
    ];

    private const DIPLOME = [
        'type_diplome_bac_2', 'annee_diplome_bac_2', 'filiere_diplome_bac_2', 'etablissement_bac_2',
        'type_diplome_bac_3', 'annee_diplome_bac_3', 'filiere_diplome_bac_3', 'etablissement_bac_3',
    ];

    // Colonnes obligatoires en base, remplies à vide quand le fichier ne les donne pas
    private const VIDES = [
        'nom_ar' => '', 'prenom_ar' => '', 'ville_naissance' => '', 'ville_naissance_ar' => '', 'province' => '',
        'pay_naissance' => '', 'nationalite' => '', 'telephone_mob' => '', 'ville' => '', 'pays' => '', 'adresse' => '',
        'CV' => '', 'demande' => '', 'scan_cartid' => '', 'photo' => '', 'serie_bac' => '', 'annee_bac' => '', 'scan_bac' => '',
    ];

    private array $lignes = [];

    public function array(array $lignes): void
    {
        // Première feuille seulement
        if (!$this->lignes) {
            $this->lignes = $lignes;
        }
    }

    /**
     * @return array{crees: int, maj: int, erreurs: list<string>}
     */
    public function importer(UploadedFile $fichier, Formation $formation, ?User $par): array
    {
        Excel::import($this, $fichier);
        $rapport = ['crees' => 0, 'maj' => 0, 'erreurs' => []];

        $entetes = array_map(fn ($t) => $this->normaliser((string) $t), array_shift($this->lignes) ?? []);
        $champs = [];
        foreach (self::COLONNES as $titre => $champ) {
            $index = array_search($this->normaliser($titre), $entetes, true);
            if ($index !== false) {
                $champs[$champ] = $index;
            }
        }
        $manquantes = array_diff(['nom', 'prenom', 'email', 'CNE', 'CIN', 'date_naissance', 'sexe'], array_keys($champs));
        if ($manquantes) {
            $titres = array_map(fn ($c) => array_search($c, self::COLONNES, true), $manquantes);
            $rapport['erreurs'][] = 'Colonnes introuvables : ' . implode(', ', $titres) . '. Utilisez le modèle.';

            return $rapport;
        }

        foreach ($this->lignes as $i => $ligne) {
            $numero = $i + 2; // ligne 1 = titres
            $valeurs = array_map(fn ($index) => $this->valeur($ligne[$index] ?? null), $champs);
            if (!array_filter($valeurs, fn ($v) => $v !== null)) {
                continue; // ligne vide
            }

            $valeurs['date_naissance'] = $this->date($valeurs['date_naissance']);
            $valeurs['sexe'] = $this->sexe($valeurs['sexe']);
            $valeurs['statut'] = $this->statut($valeurs['statut'] ?? null);
            if (isset($valeurs['email'])) {
                $valeurs['email'] = mb_strtolower($valeurs['email']);
            }

            $validation = Validator::make($valeurs, [
                'nom' => 'required', 'prenom' => 'required', 'CNE' => 'required', 'CIN' => 'required',
                'email' => 'required|email', 'date_naissance' => 'required|date', 'sexe' => 'required|in:M,F',
            ], [
                'sexe.in' => 'Sexe : M, F, Homme ou Femme.', 'date_naissance.date' => 'date de naissance illisible.',
            ], ['date_naissance' => 'date de naissance', 'CNE' => 'CNE', 'CIN' => 'CIN', 'prenom' => 'prénom']);
            if ($validation->fails()) {
                $rapport['erreurs'][] = "Ligne $numero : " . implode(' ', $validation->errors()->all());
                continue;
            }

            try {
                DB::transaction(function () use ($valeurs, $formation, $par, &$rapport) {
                    $this->enregistrer($valeurs, $formation, $par, $rapport);
                });
            } catch (\Throwable $e) {
                report($e);
                $rapport['erreurs'][] = "Ligne $numero : enregistrement impossible.";
            }
        }

        return $rapport;
    }

    private function enregistrer(array $valeurs, Formation $formation, ?User $par, array &$rapport): void
    {
        $fiche = array_filter(
            array_diff_key($valeurs, array_flip([...self::DIPLOME, 'statut', 'motif'])),
            fn ($v) => $v !== null
        );

        // Le CNE identifie le candidat : deux candidats peuvent avoir le même email
        $candidat = Candidat::where('CNE', $valeurs['CNE'])->first();
        if ($candidat) {
            $candidat->update($fiche);
            $rapport['maj']++;
        } else {
            $candidat = Candidat::create($fiche + self::VIDES);
            $rapport['crees']++;
        }

        $diplome = array_filter(array_intersect_key($valeurs, array_flip(self::DIPLOME)), fn ($v) => $v !== null);
        if ($diplome) {
            $candidat->diplomes()->updateOrCreate([], $diplome);
        }

        $inscription = Inscription::where('candidat_id', $candidat->id)->where('formation_id', $formation->id)->first();
        if (!$inscription) {
            Inscription::create([
                'candidat_id' => $candidat->id,
                'formation_id' => $formation->id,
                'annee' => $formation->date_debut,
                'statut' => $valeurs['statut'] ?? 'en_attente',
                'motif' => $valeurs['motif'] ?? null,
                'statut_at' => ($valeurs['statut'] ?? 'en_attente') === 'en_attente' ? null : now(),
            ]);
        } elseif (isset($valeurs['statut']) && ($valeurs['statut'] !== $inscription->statut || ($valeurs['motif'] ?? null) !== $inscription->motif)) {
            $inscription->changerStatut($valeurs['statut'], $valeurs['motif'] ?? null, $par);
        }
    }

    private function normaliser(string $texte): string
    {
        return mb_strtolower(trim(preg_replace('/\s+/u', ' ', $texte)));
    }

    /** Texte de la cellule ; pour un lien =HYPERLINK("…", "texte") de l'export, le texte affiché. */
    private function valeur($cellule): ?string
    {
        if ($cellule === null) {
            return null;
        }
        $texte = trim((string) $cellule);
        if (preg_match('/^=HYPERLINK\(.*,\s*"([^"]*)"\)$/i', $texte, $m)) {
            $texte = trim($m[1]);
        }

        return $texte === '' || in_array($texte, ['Email not provided', 'Phone not provided'], true) ? null : $texte;
    }

    private function date(?string $valeur): ?string
    {
        if ($valeur === null) {
            return null;
        }
        if (is_numeric($valeur)) {
            return DateExcel::excelToDateTimeObject((float) $valeur)->format('Y-m-d');
        }
        foreach (['Y-m-d', 'd/m/Y', 'd-m-Y', 'd.m.Y', 'Y-m-d H:i:s'] as $format) {
            try {
                $date = Carbon::createFromFormat('!' . $format, $valeur);
            } catch (\Throwable) {
                continue;
            }
            if ($date && $date->format($format) === $valeur) {
                return $date->format('Y-m-d');
            }
        }

        return $valeur; // la validation la signalera
    }

    private function sexe(?string $valeur): ?string
    {
        return match (mb_strtolower((string) $valeur)) {
            'm', 'h', 'homme', 'masculin', 'male' => 'M',
            'f', 'femme', 'féminin', 'feminin', 'female' => 'F',
            default => $valeur,
        };
    }

    /** « Acceptée » ou « acceptee » => acceptee ; inconnu ou vide => null (statut inchangé, ou « En attente » à la création). */
    private function statut(?string $valeur): ?string
    {
        if ($valeur === null) {
            return null;
        }
        foreach (Inscription::STATUTS as $cle => [$libelle]) {
            if (in_array($this->normaliser($valeur), [$cle, $this->normaliser($libelle)], true)) {
                return $cle;
            }
        }

        return null;
    }
}
