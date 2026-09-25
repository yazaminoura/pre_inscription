<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset(config('etablissement.logo')) }}">
    <title>@hasSection('title')@yield('title') · @endif{{ config('etablissement.court') }} - Préinscription {{ date('Y') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..24,400..600,0..1,0" />
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/public.css') }}">
    <style>:root { --brand: {{ config('etablissement.couleur') }}; }</style>
    @stack('styles')
</head>
<body class="public-body">
    <header class="public-header">
        <div class="public-header-inner">
            <a href="{{ route('accueil') }}" class="public-brand">
                <img src="{{ asset(config('etablissement.logo')) }}" alt="{{ config('etablissement.nom') }}">
                <span>
                    <span class="public-brand-name">{{ config('etablissement.nom') }}</span>
                    <span class="public-brand-sub">Préinscription en ligne <span class="badge-annee">{{ date('Y') }}</span></span>
                </span>
            </a>
            <nav class="public-nav">
                <a href="{{ route('accueil') }}" class="{{ request()->routeIs('accueil', 'formation.public') ? 'active' : '' }}">Formations</a>
                @if (config('etablissement.presentation'))<a href="{{ route('accueil') }}#etablissement">L'établissement</a>@endif
                @if (session('form_data'))
                    <a href="{{ route('candidat.form', ['step' => session('form_data._etape', 1)]) }}" class="{{ request()->routeIs('candidat.form') ? 'active' : '' }}">Ma préinscription</a>
                @endif
            </nav>
        </div>
    </header>

    <main class="public-main">
        @yield('content')
    </main>

    <footer class="public-footer">
        © {{ date('Y') }} {{ config('etablissement.nom') }}
        @if (config('etablissement.site'))
            · <a href="{{ config('etablissement.site') }}" target="_blank" rel="noopener">{{ parse_url(config('etablissement.site'), PHP_URL_HOST) ?: config('etablissement.site') }}</a>
        @endif
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
