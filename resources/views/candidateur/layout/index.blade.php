@php $rtl = app()->getLocale() === 'ar'; @endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $rtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset(config('etablissement.logo')) }}">
    <title>@hasSection('title')@yield('title') · @endif{{ config('etablissement.court') }} - {{ __('Préinscription') }} {{ date('Y') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap{{ $rtl ? '.rtl' : '' }}.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800{{ $rtl ? '&family=Noto+Kufi+Arabic:wght@400;500;600;700;800' : '' }}&display=swap" rel="stylesheet">
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
                    <span class="public-brand-sub">{{ __('Préinscription en ligne') }} <span class="badge-annee">{{ date('Y') }}</span></span>
                </span>
            </a>
            <nav class="public-nav">
                <a href="{{ route('accueil') }}" class="{{ request()->routeIs('accueil', 'formation.public') ? 'active' : '' }}">{{ __('Formations') }}</a>
                @if (config('etablissement.presentation'))<a href="{{ route('accueil') }}#etablissement">{{ __("L'établissement") }}</a>@endif
                <a href="{{ route('suivi') }}" class="{{ request()->routeIs('suivi*') ? 'active' : '' }}">{{ __('Suivre mon dossier') }}</a>
                @if (session('form_data'))
                    <a href="{{ route('candidat.form', ['step' => session('form_data._etape', 1)]) }}" class="{{ request()->routeIs('candidat.form') ? 'active' : '' }}">{{ __('Ma préinscription') }}</a>
                @endif
            </nav>
            <div class="dropdown">
                <button class="lang-btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" aria-label="{{ __('Langue') }}">
                    <span class="material-symbols-rounded">translate</span> {{ strtoupper(app()->getLocale()) }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end lang-menu">
                    @foreach (\App\Http\Middleware\DefinirLangue::LANGUES as $code => $nom)
                        <li>
                            <a class="dropdown-item {{ app()->getLocale() === $code ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['lang' => $code]) }}" lang="{{ $code }}">
                                <span class="code">{{ strtoupper($code) }}</span> {{ $nom }}
                                @if (app()->getLocale() === $code)<span class="material-symbols-rounded check">check</span>@endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
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
        · <a href="{{ route('login') }}">{{ __('Espace administration') }}</a>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
