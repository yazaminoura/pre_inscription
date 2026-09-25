<?php

use Database\Seeders\DemoSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Données de démonstration (établissement fictif, formations, 24 candidats) chargées au premier « php artisan migrate ».
     * Rien n'est fait si des formations existent déjà, pendant les tests, ou avec DEMO_DONNEES=false dans .env.
     */
    public function up(): void
    {
        if (!config('etablissement.demo') || app()->environment('testing') || DB::table('formations')->exists()) {
            return;
        }

        app(DemoSeeder::class)->setContainer(app())->__invoke();
    }

    public function down(): void
    {
        // Les données ont pu être modifiées depuis : on ne les supprime pas
    }
};
