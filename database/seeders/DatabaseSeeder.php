<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data
        DB::table('inscriptions')->delete();
        DB::table('experiences')->delete();
        DB::table('stages')->delete();
        DB::table('attestations')->delete();
        DB::table('diplomes')->delete();
        DB::table('candidats')->delete();
        DB::table('formations')->delete();
        DB::table('administrateurs')->delete();

        // Insert Administrateurs
       DB::table('users')->insert([
            'name'=>' FST Admin2',
            'email'=>'adminfst2025N2@fsdm.ma',
            'password'=>Hash::make('20255202'),          
            'created_at' => now(),
            'updated_at'=>now(),
        ]);

        // Insert Formations
        DB::table('formations')->insert([
            [
                'type_formation' => 'Licence',
                'titre' => 'Licence en Informatique',
                'date_debut' => '2025-09-15',
                'date_fin' => '2028-06-30',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'type_formation' => 'Master',
                'titre' => 'Master en Génie Logiciel',
                'date_debut' => '2024-09-15',
                'date_fin' => '2027-06-30',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Insert Candidats
        DB::table('candidats')->insert([
            [
                'email' => 'nourayazami2004@gmail.com',
                'nom' => 'Martin',
                'prenom' => 'Pierre',
                'nom_ar' => 'مارتن',
                'prenom_ar' => 'بيير',
                'CNE' => 'G123456789',
                'CIN' => 'AB123456',
                'date_naissance' => '2000-05-15',
                'ville_naissance' => 'Casablanca',
                'ville_naissance_ar' => 'الدار البيضاء',
                'province' => 'Casablanca-Settat',
                'pay_naissance' => 'Maroc',
                'nationalite' => 'Marocaine',
                'sexe' => 'M',
                'telephone_mob' => '+212617365256',
                'telephone_fix' => '0522123456',
                'ville' => 'Casablanca',
                'pays' => 'Maroc',
                'adresse' => '123 Rue Principale, Casablanca',
                'CV' => 'cv_pierre_martin.pdf',
                'demande' => 'demande_licence_info.pdf',
                'scan_cartid' => 'cin_pierre_martin.pdf',
                'photo' => 'photo_pierre.jpg',
                'serie_bac' => 'Sciences Mathématiques',
                'annee_bac' => '2018',
                'scan_bac' => 'bac_pierre_martin.pdf',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'email' => 'candidat2@example.com',
                'nom' => 'Bernard',
                'prenom' => 'Sophie',
                'nom_ar' => 'برنار',
                'prenom_ar' => 'صوفي',
                'CNE' => 'G987654321',
                'CIN' => 'CD654321',
                'date_naissance' => '1999-08-22',
                'ville_naissance' => 'Rabat',
                'ville_naissance_ar' => 'الرباط',
                'province' => 'Rabat-Salé-Kénitra',
                'pay_naissance' => 'Maroc',
                'nationalite' => 'Marocaine',
                'sexe' => 'F',
                'telephone_mob' => '0698765432',
                'telephone_fix' => '0537123456',
                'ville' => 'Rabat',
                'pays' => 'Maroc',
                'adresse' => '456 Avenue Mohammed V, Rabat',
                'CV' => 'cv_sophie_bernard.pdf',
                'demande' => 'demande_master_gl.pdf',
                'scan_cartid' => 'cin_sophie_bernard.pdf',
                'photo' => 'photo_sophie.jpg',
                'serie_bac' => 'Sciences Physiques',
                'annee_bac' => '2017',
                'scan_bac' => 'bac_sophie_bernard.pdf',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Insert Diplomes
        DB::table('diplomes')->insert([
            [
                'type_diplome_bac_2' => 'DEUG',
                'annee_diplome_bac_2' => '2020',
                'filiere_diplome_bac_2' => 'Sciences Mathématiques et Informatique',
                'scan_bac_2' => 'deug_pierre_martin.pdf',
                'etablissement_bac_2' => 'Université Hassan II, Casablanca',
                'type_diplome_bac_3' => 'Licence',
                'annee_diplome_bac_3' => '2022',
                'filiere_diplome_bac_3' => 'Informatique',
                'etablissement_bac_3' => 'Université Hassan II, Casablanca',
                'scan_bac_3' => 'licence_pierre_martin.pdf',
                'candidat_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'type_diplome_bac_2' => 'DEUG',
                'annee_diplome_bac_2' => '2019',
                'filiere_diplome_bac_2' => 'Sciences Physiques et Chimie',
                'scan_bac_2' => 'deug_sophie_bernard.pdf',
                'etablissement_bac_2' => 'Université Mohammed V, Rabat',
                'type_diplome_bac_3' => 'Licence',
                'annee_diplome_bac_3' => '2021',
                'filiere_diplome_bac_3' => 'Physique',
                'etablissement_bac_3' => 'Université Mohammed V, Rabat',
                'scan_bac_3' => 'licence_sophie_bernard.pdf',
                'candidat_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Insert Attestations
        DB::table('attestations')->insert([
            [
                'candidat_id' => 1,
                'attestation' => 'attestation_pierre_langue.pdf',
                'description' => 'Attestation de niveau avancé en anglais',
                'type_attestation' => 'Langue',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'candidat_id' => 2,
                'attestation' => 'attestation_sophie_benevolat.pdf',
                'description' => 'Attestation de bénévolat dans une association',
                'type_attestation' => 'Bénévolat',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Insert Stages
        DB::table('stages')->insert([
            [
                'candidat_id' => 1,
                'fonction' => 'Stagiaire développeur',
                'periode' => 'Juillet 2021 - Août 2021',
                'attestation' => 'attestation_stage_pierre.pdf',
                'etablissement' => 'XYZ Technologies',
                'secteur_activite' => 'Informatique',
                'description' => 'Développement d\'une application web',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'candidat_id' => 2,
                'fonction' => 'Stagiaire en laboratoire',
                'periode' => 'Juillet 2020 - Août 2020',
                'attestation' => 'attestation_stage_sophie.pdf',
                'etablissement' => 'Laboratoire National de Physique',
                'secteur_activite' => 'Recherche scientifique',
                'description' => 'Participation à des expériences de physique',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Insert Experiences
        DB::table('experiences')->insert([
            [
                'candidat_id' => 1,
                'fonction' => 'Développeur freelance',
                'secteur_activite' => 'Informatique',
                'periode' => '2022 - présent',
                'attestation' => 'attestation_freelance_pierre.pdf',
                'etablissement' => 'Auto-entrepreneur',
                'description' => 'Développement de sites web pour des clients',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Insert Inscriptions
        DB::table('inscriptions')->insert([
            [
                'annee' => '2025-09-01',
                'candidat_id' => 1,
                'formation_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'annee' => '2025-09-01',
                'candidat_id' => 2,
                'formation_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}