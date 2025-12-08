<x-mail::message>
# Bonjour !

Vous recevez cet email car nous avons reçu une demande de réinitialisation de mot de passe pour votre compte.

<x-mail::button :url="$actionUrl">
Réinitialiser le mot de passe
</x-mail::button>

Ce lien de réinitialisation de mot de passe expirera dans {{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }} minutes.

Si vous n'avez pas demandé de réinitialisation de mot de passe, aucune action n'est requise.

Cordialement,<br>
{{ config('app.name') }}

<x-mail::subcopy>
Si vous avez des difficultés à cliquer sur le bouton "Réinitialiser le mot de passe", copiez et collez l'URL ci-dessous dans votre navigateur web : <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-mail::subcopy>
</x-mail::message> 