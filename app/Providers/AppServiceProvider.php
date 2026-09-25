<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
   public function boot()
    {
        // Dates affichées en français (« il y a 2 jours », « 25 septembre 2026 »)
        \Carbon\Carbon::setLocale('fr');

        // Nom, logo, couleur… saisis dans l'administration
        \App\Models\Etablissement::appliquerALaConfig();
        // Nom court pour les titres : le sigle s'il existe, sinon le nom complet
        config(['etablissement.court' => config('etablissement.sigle') ?: config('etablissement.nom')]);
        // En-tête et pied des emails (« © 2026 … ») : le nom de l'établissement, pas « Laravel »
        config(['app.name' => config('etablissement.nom')]);

        // Email « Mot de passe oublié » des administrateurs, en français
        \Illuminate\Auth\Notifications\ResetPassword::toMailUsing(function ($utilisateur, string $jeton) {
            $lien = url(route('password.reset', ['token' => $jeton, 'email' => $utilisateur->getEmailForPasswordReset()], false));
            $minutes = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');

            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->subject('Réinitialisation de votre mot de passe · ' . config('etablissement.court'))
                ->greeting('Bonjour ' . $utilisateur->name . ',')
                ->line('Vous recevez cet email car une réinitialisation du mot de passe a été demandée pour votre compte d\'administration.')
                ->action('Choisir un nouveau mot de passe', $lien)
                ->line("Ce lien expire dans $minutes minutes.")
                ->line('Si vous n\'avez rien demandé, ignorez cet email : votre mot de passe reste inchangé.')
                ->salutation('Cordialement, ' . config('etablissement.nom'));
        });

        // Add macro to check if any of multiple fields are filled
        \Illuminate\Http\Request::macro('anyFilled', function ($keys) {
            foreach ((array) $keys as $key) {
                if ($this->filled($key)) {
                    return true;
                }
            }
            return false;
        });
    }
}
