<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ModifierAdmin extends Command
{
    protected $signature = 'admin:modifier {email? : Email actuel du compte}';

    protected $description = "Change l'email et le mot de passe d'un administrateur, sans connaître l'ancien mot de passe";

    public function handle(): int
    {
        $comptes = User::orderBy('email')->pluck('email');
        if ($comptes->isEmpty()) {
            $this->error('Aucun administrateur. Créez-en un avec : php artisan admin:creer');
            return self::FAILURE;
        }

        $email = $this->argument('email') ?: $this->choice('Quel compte ?', $comptes->all());
        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error("Aucun administrateur avec l'email $email.");
            return self::FAILURE;
        }

        $nouvelEmail = trim($this->ask('Nouvel email (Entrée pour garder le même)', $user->email));
        $motDePasse = $this->secret('Nouveau mot de passe (8 caractères minimum)');
        $confirmation = $this->secret('Confirmez le mot de passe');

        $validation = Validator::make(
            ['email' => $nouvelEmail, 'password' => $motDePasse, 'password_confirmation' => $confirmation],
            ['email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)], 'password' => 'required|min:8|confirmed']
        );
        if ($validation->fails()) {
            foreach ($validation->errors()->all() as $erreur) {
                $this->error($erreur);
            }
            return self::FAILURE;
        }

        // remember_token vidé : les sessions « Se souvenir de moi » ouvertes avec l'ancien mot de passe sont déconnectées
        $user->forceFill(['email' => $nouvelEmail, 'password' => Hash::make($motDePasse), 'remember_token' => null])->save();
        $this->info("Compte mis à jour : $nouvelEmail");

        return self::SUCCESS;
    }
}
