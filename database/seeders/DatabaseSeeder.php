<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Données de démonstration uniquement. Aucun compte ni mot de passe n'est créé ici :
     * le premier administrateur se crée avec « php artisan admin:creer ».
     */
    public function run(): void
    {
        $this->call(DemoSeeder::class);
    }
}
