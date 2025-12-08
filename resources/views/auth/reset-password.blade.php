<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('dist/assets/img/logo-fsdm-fes.png') }}">
    <title>FST Fès - Réinitialisation du mot de passe</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #1a2a6c;
            --secondary-color: #eab9b9;
            --accent-color: #4CAF50;
            --button-hover: #e0e0e0;
            --text-light: rgba(255, 255, 255, 0.9);
            --text-muted: rgba(255, 255, 255, 0.6);
            --glass-background: rgba(0, 0, 0, 0.3);
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            background-size: 200% 200%;
            animation: gradientAnimation 15s ease infinite;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 10px;
            position: relative;
            overflow: hidden;
        }

        @keyframes gradientAnimation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .reset-container {
            width: 100%;
            max-width: 420px;
            background: var(--glass-background);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            border: 1px solid var(--glass-border);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            z-index: 10;
            position: relative;
            color: var(--text-light);
        }

        .reset-container:hover {
            transform: scale(1.02);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.4);
        }

        .reset-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .reset-header h1 {
            color: var(--text-light);
            font-size: 24px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .reset-header p {
            color: var(--text-muted);
            font-size: 14px;
            margin: 0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            color: var(--text-light);
            font-size: 13px;
            margin-bottom: 6px;
            display: block;
            font-weight: 500;
        }

        .input-group {
            display: flex;
            align-items: center;
            border-radius: 6px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.15);
            transition: border-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .input-group:focus-within {
            border-color: var(--accent-color);
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.4);
        }

        .input-icon {
            padding: 10px 12px;
            background: rgba(255, 255, 255, 0.08);
            color: var(--text-muted);
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            background: transparent;
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 13px;
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        .form-control:focus {
            outline: none;
        }

        .btn-reset {
            width: 100%;
            padding: 12px;
            background: white;
            border: none;
            border-radius: 6px;
            color: var(--primary-color);
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            margin-top: 20px;
        }

        .btn-reset:hover {
            background: var(--accent-color);
            color: white;
            transform: scale(1.02);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .error-message {
            color: #ef4444;
            font-size: 12px;
            margin-top: 5px;
        }

        .success-message {
            background-color: rgba(76, 175, 80, 0.2);
            color: var(--text-light);
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid var(--accent-color);
        }

        .back-to-login {
            text-align: center;
            margin-top: 20px;
        }

        .back-to-login a {
            color: var(--text-muted);
            font-size: 13px;
            text-decoration: none;
            transition: color 0.3s ease-in-out;
        }

        .back-to-login a:hover {
            color: var(--accent-color);
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="reset-header">
            <h1>Réinitialisation du mot de passe</h1>
            <p>Entrez votre nouveau mot de passe</p>
        </div>

        @if (session('status'))
            <div class="success-message">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div class="form-group">
                <label for="email">Adresse Email</label>
                <div class="input-group">
                    <span class="input-icon"><i class="fas fa-envelope"></i></span>
                    <input id="email" class="form-control" type="email" name="email" 
                           value="{{ old('email', $request->email) }}" required autofocus 
                           placeholder="Votre adresse email">
                </div>
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Nouveau mot de passe</label>
                <div class="input-group">
                    <span class="input-icon"><i class="fas fa-lock"></i></span>
                    <input id="password" class="form-control" type="password" name="password" 
                           required placeholder="Nouveau mot de passe">
                </div>
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="password_confirmation">Confirmer le mot de passe</label>
                <div class="input-group">
                    <span class="input-icon"><i class="fas fa-lock"></i></span>
                    <input id="password_confirmation" class="form-control" type="password" 
                           name="password_confirmation" required placeholder="Confirmer le mot de passe">
                </div>
            </div>

            <button type="submit" class="btn-reset">
                <i class="fas fa-key me-2"></i> Réinitialiser le mot de passe
            </button>

            <div class="back-to-login">
                <a href="{{ route('login') }}">
                    <i class="fas fa-arrow-left me-1"></i> Retour à la connexion
                </a>
            </div>
        </form>
    </div>
</body>
</html>
