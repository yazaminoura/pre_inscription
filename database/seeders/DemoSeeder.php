<?php

namespace Database\Seeders;

use App\Models\Candidat;
use App\Models\Diplome;
use App\Models\Experience;
use App\Models\Formation;
use App\Models\Inscription;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * Données de démonstration : formations ouvertes + candidats avec statuts variés.
 * php artisan db:seed --class=DemoSeeder
 */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = User::query()->value('id');

        $formations = collect([
            ['Licence', 'Licence Génie Informatique'],
            ['Licence', 'Licence Génie Électrique'],
            ['Licence', 'Licence Biotechnologie'],
            ['Master', 'Master Intelligence Artificielle et Data Science'],
            ['Master', 'Master Génie Logiciel'],
            ['Master', 'Master Énergies Renouvelables'],
        ])->map(fn ($f) => Formation::firstOrCreate(
            ['titre' => $f[1]],
            ['type_formation' => $f[0], 'date_debut' => '2026-09-01', 'date_fin' => '2026-12-31', 'user_id' => $adminId]
        ));

        // nom, prénom, sexe, ville, pays, nationalité, n° formation, statut, motif
        $candidats = [
            ['El Amrani', 'Yassine', 'M', 'Fès', 'Maroc', 'Marocaine', 3, 'acceptee', null],
            ['Benali', 'Salma', 'F', 'Meknès', 'Maroc', 'Marocaine', 3, 'en_cours', null],
            ['Tazi', 'Omar', 'M', 'Casablanca', 'Maroc', 'Marocaine', 4, 'refusee', 'Moyenne Bac+3 insuffisante'],
            ['Idrissi', 'Khadija', 'F', 'Rabat', 'Maroc', 'Marocaine', 4, 'liste_attente', 'Position 3 sur la liste'],
            ['Ouazzani', 'Hamza', 'M', 'Taza', 'Maroc', 'Marocaine', 0, 'en_attente', null],
            ['Chraibi', 'Imane', 'F', 'Fès', 'Maroc', 'Marocaine', 0, 'acceptee', null],
            ['Diallo', 'Mamadou', 'M', 'Dakar', 'Sénégal', 'Sénégalaise', 5, 'en_cours', null],
            ['Traoré', 'Aminata', 'F', 'Bamako', 'Mali', 'Malienne', 1, 'en_attente', null],
            ['Haddad', 'Nour', 'F', 'Tunis', 'Tunisie', 'Tunisienne', 3, 'en_attente', null],
            ['Martin', 'Lucas', 'M', 'Lyon', 'France', 'Française', 4, 'liste_attente', 'Dossier incomplet : relevé de notes manquant'],
            ['Berrada', 'Ayoub', 'M', 'Oujda', 'Maroc', 'Marocaine', 2, 'refusee', 'Profil ne correspondant pas aux prérequis'],
            ['Alaoui', 'Sara', 'F', 'Agadir', 'Maroc', 'Marocaine', 5, 'en_attente', null],
        ];

        $pdf = "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj 2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj "
             . "3 0 obj<</Type/Page/Parent 2 0 R/MediaBox[0 0 595 842]>>endobj\ntrailer<</Root 1 0 R>>\n%%EOF";

        foreach ($candidats as $i => [$nom, $prenom, $sexe, $ville, $pays, $nationalite, $f, $statut, $motif]) {
            $formation = $formations[$f];
            $n = str_pad($i + 1, 3, '0', STR_PAD_LEFT);
            $base = 'DEMO' . $n;

            if (Candidat::where('CNE', 'D13' . $n . '4567')->exists()) {
                continue;
            }

            $fichiers = [];
            foreach (['CV' => 'CV', 'demande' => 'demande', 'scan_cartid' => 'cart', 'scan_bac' => 'bac'] as $champ => $dossier) {
                $fichiers[$champ] = "$dossier/{$base}_{$champ}.pdf";
                Storage::disk('public')->put($fichiers[$champ], $pdf);
            }
            // Pas de fichier photo : l'admin affiche alors les initiales
            $fichiers['photo'] = "photos/{$base}_photo.png";
            Storage::disk('public')->put("bac_2/{$base}_bac2.pdf", $pdf);

            $candidat = Candidat::create($fichiers + [
                'email' => strtolower(str_replace(' ', '', "$prenom.$nom")) . '@example.com',
                'nom' => $nom,
                'prenom' => $prenom,
                'nom_ar' => '',
                'prenom_ar' => '',
                'CNE' => 'D13' . $n . '4567',
                'CIN' => ($pays === 'Maroc' ? 'C' : 'P') . (100000 + $i * 7919),
                'date_naissance' => (2001 + $i % 4) . '-0' . (1 + $i % 9) . '-1' . ($i % 9),
                'ville_naissance' => $ville,
                'ville_naissance_ar' => '',
                'province' => $ville,
                'pay_naissance' => $pays,
                'nationalite' => $nationalite,
                'sexe' => $sexe,
                'telephone_mob' => '+2126' . str_pad(10000000 + $i * 1234567, 8, '0', STR_PAD_LEFT),
                'telephone_fix' => null,
                'ville' => $ville,
                'pays' => $pays,
                'adresse' => ($i + 12) . ' Avenue Hassan II, ' . $ville,
                'serie_bac' => ['Sciences Mathématiques A', 'Sciences Physiques', 'Sciences de la Vie et de la Terre'][$i % 3],
                'annee_bac' => (string) (2019 + $i % 3),
            ]);

            $master = $formation->type_formation === 'Master';
            Diplome::create([
                'candidat_id' => $candidat->id,
                'type_diplome_bac_2' => 'DEUST',
                'annee_diplome_bac_2' => (string) (2021 + $i % 3),
                'filiere_diplome_bac_2' => ['MIP', 'BCG', 'GE-GM'][$i % 3],
                'etablissement_bac_2' => 'FST Fès',
                'scan_bac_2' => "bac_2/{$base}_bac2.pdf",
                'type_diplome_bac_3' => $master ? 'Licence' : null,
                'annee_diplome_bac_3' => $master ? (string) (2023 + $i % 3) : null,
                'filiere_diplome_bac_3' => $master ? 'Génie Informatique' : null,
                'etablissement_bac_3' => $master ? 'FST Fès' : null,
            ]);

            if ($i % 2 === 0) {
                Stage::create([
                    'candidat_id' => $candidat->id,
                    'fonction' => 'Stagiaire développeur',
                    'periode' => 'Juillet - Août 2025',
                    'etablissement' => ['OCP', 'CDG Invest', 'Capgemini', 'Inwi'][$i % 4],
                    'secteur_activite' => 'Informatique',
                    'description' => "Développement d'une application de gestion",
                ]);
            }
            if ($i % 3 === 0) {
                Experience::create([
                    'candidat_id' => $candidat->id,
                    'fonction' => 'Développeur junior',
                    'periode' => '2025 - 2026',
                    'etablissement' => 'Freelance',
                    'secteur_activite' => 'Informatique',
                    'description' => 'Sites web pour des PME',
                ]);
            }

            Inscription::create([
                'candidat_id' => $candidat->id,
                'formation_id' => $formation->id,
                'annee' => '2026-09-01',
                'statut' => $statut,
                'motif' => $motif,
                'statut_at' => $statut === 'en_attente' ? null : now(),
                'created_at' => now()->subDays(12 - $i),
            ]);
        }
    }
}
