<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset(config('etablissement.logo')) }}">
    <title>@yield('title') - {{ config('etablissement.nom_court') }} {{ config('etablissement.ville') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..24,400..600,0..1,0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <style>:root { --brand: {{ config('etablissement.couleur') }}; }</style>
</head>
<body>
<div class="auth-page">
    <aside class="auth-aside">
        <div>
            <span class="logo-tile"><img src="{{ asset(config('etablissement.logo')) }}" alt="{{ config('etablissement.nom') }}"></span>
            <h1>Préinscription {{ date('Y') }}</h1>
            <p>{{ config('etablissement.nom') }} - {{ config('etablissement.ville') }}. Espace réservé à l'équipe qui étudie les candidatures.</p>
            <ul>
                <li><span class="material-symbols-rounded">folder_shared</span> Tous les dossiers au même endroit</li>
                <li><span class="material-symbols-rounded">gavel</span> Décisions suivies par statut</li>
                <li><span class="material-symbols-rounded">download</span> Exports Excel par formation</li>
            </ul>
        </div>
        <small>© {{ date('Y') }} {{ config('etablissement.nom') }}</small>
    </aside>

    <main class="auth-main">
        <div class="auth-card">
            <div class="auth-mobile-logo"><img src="{{ asset(config('etablissement.logo')) }}" alt=""></div>
            @yield('content')
        </div>
    </main>
</div>
<script>
    function togglePassword(btn) {
        const input = btn.parentElement.querySelector('input');
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.querySelector('span').textContent = show ? 'visibility_off' : 'visibility';
    }
</script>
</body>
</html>
