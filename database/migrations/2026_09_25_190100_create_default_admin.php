<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Compte administrateur par défaut : admin@gmail.com / password.
     * Créé seulement si aucun compte n'existe. À changer dès la première connexion (php artisan admin:modifier).
     */
    public function up(): void
    {
        if (DB::table('users')->exists()) {
            return;
        }

        DB::table('users')->insert([
            'name' => 'Administrateur',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('users')->where('email', 'admin@gmail.com')->delete();
    }
};
