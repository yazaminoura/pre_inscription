<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('dist/assets/img/logo-fsdm-fes.png') }}">
    <title>FST Fès - Connexion</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('dist/assets/css/login.css') }}">
   
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
            <img src="{{ asset('dist/assets/img/logo-fsdm-fes.png') }}" alt="FST Fès Logo" class="institution-logo">
            <h1>Connexion </h1><h1>Préinscription <span id="current-year"></span></h1>
            <p>Accédez à votre espace personnel</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Adresse Email</label>
                <div class="input-group">
                    <span class="input-icon"><i class="fas fa-envelope"></i></span>
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required placeholder="Votre adresse email">
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Mot de passe</label>
                <div class="input-group">
                    <span class="input-icon"><i class="fas fa-lock"></i></span>
                    <input id="password" type="password" class="form-control" name="password" required placeholder="Votre mot de passe"  onfocus="if(this.value==='········') this.value=''">
                    <i class="password-toggle fas fa-eye" onclick="togglePassword()"></i>
                </div>
            </div>

            <button type="submit" class="btn-login">Se connecter</button>

          <div class="forgot-password">
                <a href="{{ route('password.request') }}">Mot de passe oublié ?</a>
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

        document.addEventListener('DOMContentLoaded', function() {
            const currentYear = new Date().getFullYear();
            const displayYear = currentYear >= 2025 ? currentYear : 2025;
            document.getElementById('current-year').textContent = displayYear;
        });
    </script>
</body>
</html>