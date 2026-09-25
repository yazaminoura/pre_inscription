<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Premier administrateur, créé à partir du .env (ADMIN_EMAIL / ADMIN_PASSWORD) :
     * le mot de passe n'est jamais écrit dans le code, qui est public.
     * Ne fait rien s'il existe déjà un compte ou si les deux valeurs ne sont pas renseignées.
     */
    public function up(): void
    {
        $email = config('admin.email');
        $motDePasse = config('admin.password');

        if (!$email || !$motDePasse || DB::table('users')->exists()) {
            return;
        }
        if (strlen($motDePasse) < 8) {
            throw new RuntimeException('ADMIN_PASSWORD doit faire au moins 8 caractères.');
        }

        DB::table('users')->insert([
            'name' => config('admin.nom'),
            'email' => $email,
            'password' => Hash::make($motDePasse),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        // Le compte n'est pas supprimé : il a pu être modifié depuis
    }
};
