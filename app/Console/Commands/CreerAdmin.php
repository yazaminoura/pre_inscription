<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreerAdmin extends Command
{
    protected $signature = 'admin:creer {--nom=} {--email=}';

    protected $description = 'Crée un compte administrateur (le mot de passe est demandé, jamais écrit dans le code)';

    public function handle(): int
    {
        $nom = $this->option('nom') ?: $this->ask('Nom et prénom');
        $email = $this->option('email') ?: $this->ask('Email');
        $motDePasse = $this->secret('Mot de passe (8 caractères minimum)');
        $confirmation = $this->secret('Confirmez le mot de passe');

        $validation = Validator::make(
            ['nom' => $nom, 'email' => $email, 'password' => $motDePasse, 'password_confirmation' => $confirmation],
            ['nom' => 'required|string|max:255', 'email' => 'required|email|unique:users,email', 'password' => 'required|min:8|confirmed']
        );
        if ($validation->fails()) {
            foreach ($validation->errors()->all() as $erreur) {
                $this->error($erreur);
            }
            return self::FAILURE;
        }

        User::create(['name' => $nom, 'email' => $email, 'password' => Hash::make($motDePasse)]);
        $this->info("Administrateur créé : $email");

        return self::SUCCESS;
    }
}
