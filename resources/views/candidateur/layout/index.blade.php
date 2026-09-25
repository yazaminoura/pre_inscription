<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset(config('etablissement.logo')) }}">
    <title>{{ config('etablissement.nom_court') }} {{ config('etablissement.ville') }} - Préinscription {{ date('Y') }}</title>
    @livewireStyles
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('dist/assets/css/formacandidats.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />

    <style>
        :root {
            --brand: {{ config('etablissement.couleur') }};
        }

        body {
            margin: 0;
            font-family: 'Inter', system-ui, 'Segoe UI', sans-serif;
            background: linear-gradient(180deg, #eef4f8 0, #f6f9fb 320px);
            color: #2b3440;
        }

        /* En-tête */
        .header {
            background: #fff;
            border-bottom: 4px solid var(--brand);
            padding: .75rem 1.5rem;
            box-shadow: 0 2px 12px rgba(9, 106, 155, .08);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header .logo {
            height: 56px;
            width: auto;
        }

        .header .title-section {
            border-left: 2px solid #e3ebf1;
            padding-left: 1rem;
        }

        .header .site-title {
            font-size: 1.35rem;
            margin: 0;
            font-weight: 800;
            color: var(--brand);
            line-height: 1.2;
        }

        .header .site-subtitle {
            font-size: .9rem;
            margin: 0;
            color: #6b7a88;
        }

        .header .site-subtitle .badge-annee {
            background: var(--brand);
            color: #fff;
            border-radius: 20px;
            padding: 1px 10px;
            font-weight: 700;
            font-size: .8rem;
            margin-left: .25rem;
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
                gap: .5rem;
            }

            .header .title-section {
                border-left: 0;
                padding-left: 0;
            }

            .header .site-title {
                font-size: 1.1rem;
            }

            .header .logo {
                height: 44px;
            }
        }

        /* Styles pour le contenu de la page d'index */
        .main-content {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .welcome-section {
            background: #FFFFFF;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .welcome-section h2 {
            color: #1a4b8c;
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .welcome-section p {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }

        .cta-button {
            background-color: #1a4b8c;
            color: #FFFFFF;
            padding: 0.75rem 1.5rem;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1.1rem;
            transition: background-color 0.3s;
        }

        .cta-button:hover {
            background-color: #1a4b8c;
        }

        /* Styles pour le pied de page */
        html, body {
    height: 100%;
    margin: 0;
}

body {
    display: flex;
    flex-direction: column;
}

.main-content {
    flex: 1; /* This will allow the content to take the remaining space */
}

footer {
    margin-top: auto; /* Push the footer to the bottom */
    background-color: var(--brand);
    color: #FFFFFF;
    text-align: center;
    padding: 1rem;
    font-size: .9rem;
}

footer p {
    margin: 0;
}

footer a {
    color: #fff;
    font-weight: 700;
}

        .toastify-success {
            font-family: 'Arial', sans-serif;
            font-size: 16px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
            border-radius: 4px;
            padding: 15px 25px;
        }
    </style>
</head>
<body>
    <!-- En-tête -->
    <header class="header">
        <div class="header-content">
            <a href="{{ route('candidat.form') }}"><img src="{{ asset(config('etablissement.logo')) }}" class="logo" alt="{{ config('etablissement.nom') }}"></a>
            <div class="title-section">
                <h1 class="site-title">{{ config('etablissement.nom') }} - {{ config('etablissement.ville') }}</h1>
                <p class="site-subtitle">Préinscription en ligne <span class="badge-annee">{{ date('Y') }}</span></p>
            </div>
        </div>
    </header>

     @yield('content')
    <!-- Pied de page -->
    <footer>
        <p>© {{ date('Y') }} {{ config('etablissement.nom') }} - {{ config('etablissement.ville') }} · <a href="{{ config('etablissement.site') }}" target="_blank" rel="noopener">{{ parse_url(config('etablissement.site'), PHP_URL_HOST) }}</a></p>
    </footer>

    @livewireScripts
    @if(session('toast'))
        <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Toastify({
                    text: @json(session('toast.message')),
                    duration: 8000,
                    close: true,
                    gravity: "top",
                    position: "center",
                    backgroundColor: "#2e7d32",
                    className: "toastify-success",
                }).showToast();
            });
        </script>
    @endif
</body>
</html>