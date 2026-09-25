<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset(config('etablissement.logo')) }}">
    <title>Connexion - {{ config('etablissement.court') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('dist/assets/css/login.css') }}">
    <style>
        :root {
            --primary-color: #073b57;
            --secondary-color: {{ config('etablissement.couleur') }};
            --accent-color: #7cc8ec;
        }
        .login-header .institution-logo {
            width: 150px;
            height: auto;
            background: #fff;
            padding: 12px 16px;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .18);
            object-fit: contain;
        }
        .login-error {
            background: rgba(198, 40, 40, .15);
            border: 1px solid rgba(198, 40, 40, .5);
            color: #fff;
            border-radius: 8px;
            padding: 10px 12px;
            margin-bottom: 16px;
            font-size: .9rem;
        }
        .candidat-link {
            text-align: center;
            margin-top: 14px;
            font-size: .85rem;
            color: var(--text-muted);
        }
        .candidat-link a {
            color: #fff;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="background-icons">
        <div class="university-icon icon-cap"></div>
        <div class="university-icon icon-book"></div>
        <div class="university-icon icon-flask"></div>
        <div class="university-icon icon-atom"></div>
        <div class="university-icon icon-cap"></div>
        <div class="university-icon icon-microscope"></div>
        <div class="university-icon icon-pencil"></div>
        <div class="university-icon icon-calculator"></div>
        <div class="university-icon icon-globe"></div>
        <div class="university-icon icon-cap"></div>
        <div class="university-icon icon-book"></div>
        <div class="university-icon icon-cap"></div>
    </div>

    <div class="login-container">
        <div class="login-header">
            <img src="{{ asset(config('etablissement.logo')) }}" alt="{{ config('etablissement.nom') }}" class="institution-logo">
            <h1>Espace administration</h1><h1>Préinscription {{ date('Y') }}</h1>
            <p>{{ config('etablissement.nom') }}</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            @if ($errors->any())
                <div class="login-error"><i class="fas fa-circle-exclamation"></i> {{ $errors->first() }}</div>
            @endif

            <div class="form-group">
                <label for="email" class="form-label">Adresse Email</label>
                <div class="input-group">
                    <span class="input-icon"><i class="fas fa-envelope"></i></span>
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Votre adresse email">
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Mot de passe</label>
                <div class="input-group">
                    <span class="input-icon"><i class="fas fa-lock"></i></span>
                    <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password" placeholder="Votre mot de passe">
                    <i class="password-toggle fas fa-eye" onclick="togglePassword()"></i>
                </div>
            </div>

            <button type="submit" class="btn-login">Se connecter</button>

            <div class="forgot-password">
                <a href="{{ route('password.request') }}">Mot de passe oublié ?</a>
            </div>
            <div class="candidat-link">
                Vous êtes candidat ? <a href="{{ route('accueil') }}">Déposer une préinscription</a>
            </div>
        </form>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.password-toggle');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</body>
</html>
