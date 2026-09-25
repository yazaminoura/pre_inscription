{{-- Page d'erreur autonome : ne dépend ni de la base ni des assets compilés, pour s'afficher même quand le site est en panne --}}
@php
    $couleur = config('etablissement.couleur', '#096a9b');
    $rtl = app()->getLocale() === 'ar';
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $rtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <title>@yield('titre') · {{ config('etablissement.nom') }}</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 16px;
               background: #f3f6f9; color: #1c2b39; font-family: system-ui, -apple-system, "Segoe UI", Tahoma, Arial, sans-serif; }
        .carte { max-width: 480px; width: 100%; background: #fff; border: 1px solid #e3eaf0; border-top: 5px solid {{ $couleur }};
                 border-radius: 14px; padding: 36px 28px; text-align: center; box-shadow: 0 10px 30px rgba(28, 43, 57, .06); }
        .code { font-size: 56px; font-weight: 800; color: {{ $couleur }}; line-height: 1; margin: 0 0 12px; }
        h1 { font-size: 22px; margin: 0 0 10px; }
        p { color: #5b6b79; line-height: 1.6; margin: 0 0 26px; }
        a { display: inline-block; background: {{ $couleur }}; color: #fff; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; }
    </style>
</head>
<body>
    <div class="carte">
        <div class="code">@yield('code')</div>
        <h1>@yield('titre')</h1>
        <p>@yield('message')</p>
        <a href="{{ url('/') }}">{{ __("Retour à l'accueil") }}</a>
    </div>
</body>
</html>
