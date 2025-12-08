<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('dist/assets/img/logo-fsdm-fes.png') }}">
    <title>FST Fès - Mot de passe oublié</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #1a2a6c; /* Darker blue */
            --secondary-color: #eab9b9; /* Darker red/purple */
            --accent-color: #4CAF50; /* A contrasting green for accent */
            --button-hover: #e0e0e0; /* Lighter grey for button hover */
            --text-light: rgba(255, 255, 255, 0.9);
            --text-muted: rgba(255, 255, 255, 0.6); /* Slightly more muted */
            --glass-background: rgba(0, 0, 0, 0.3); /* Darker glass effect */
            --glass-border: rgba(255, 255, 255, 0.1); /* Subtler border */
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
            position: relative; /* Needed for absolute positioning of icons */
            overflow: hidden; /* Hide overflowing animations */
        }

        @keyframes gradientAnimation {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        /* Generic container styling for guest layout, similar to login-container */
        .guest-layout-container {
            width: 100%;
            max-width: 420px; /* Slightly wider for the text */
            background: var(--glass-background); /* Darker glass background */
            backdrop-filter: blur(10px); /* Slightly more blur */
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3); /* Darker shadow */
            border: 1px solid var(--glass-border); /* Subtler border */
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            z-index: 10; /* Ensure container is above background icons */
            position: relative;
            color: var(--text-light); /* Default text color */
        }

        .guest-layout-container:hover {
            transform: scale(1.02);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.4);
        }

        /* Styling for the introductory text */
        .guest-layout-container .mb-4 {
            margin-bottom: 1.5rem; /* Equivalent to mb-4 in Tailwind */
        }

        .guest-layout-container .text-sm {
            font-size: 0.875rem; /* Equivalent to text-sm */
        }

        .guest-layout-container .text-gray-600 {
            color: var(--text-muted); /* Match muted text color */
            line-height: 1.5;
        }

        /* Styling for session status messages (Laravel Fortify/Breeze) */
        .guest-layout-container .alert {
            background-color: rgba(76, 175, 80, 0.2); /* Accent color, subtle */
            color: var(--text-light);
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 1rem;
            border: 1px solid var(--accent-color);
        }
        /* Laravel Breeze/Fortify specific styles for status messages */
        .guest-layout-container [class*="text-green-"] {
            color: var(--accent-color); /* For success messages */
        }
        .guest-layout-container [class*="text-red-"] {
            color: #ef4444; /* For error messages */
        }


        /* Input Label Styling */
        .guest-layout-container label {
            color: var(--text-light);
            font-size: 13px;
            margin-bottom: 6px;
            display: block;
            font-weight: 500;
            transition: color 0.2s ease-in-out;
        }

        /* Input Group (for icon) */
        .input-group {
            display: flex;
            align-items: center;
            border-radius: 6px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.15); /* Subtler border */
            transition: border-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .input-group:focus-within {
            border-color: var(--accent-color);
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.4);
        }

        .input-icon {
            padding: 10px 12px;
            background: rgba(255, 255, 255, 0.08); /* Subtler background for icon */
            color: var(--text-muted);
            font-size: 14px;
            transition: color 0.3s ease-in-out, background-color 0.3s ease-in-out;
        }

        /* Text Input Styling (similar to form-control) */
        .guest-layout-container input[type="email"],
        .form-control { /* Added .form-control to ensure general input styling applies */
            width: 100%;
            padding: 10px 12px;
            background: transparent;
            border: none; /* Border is now on input-group */
            border-radius: 6px;
            color: white;
            font-size: 13px;
            transition: border-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            margin-top: 0.25rem; /* Equivalent to mt-1 */
        }

        .guest-layout-container input[type="email"]::placeholder,
        .form-control::placeholder {
            color: var(--text-muted);
        }

        .guest-layout-container input[type="email"]:focus,
        .form-control:focus {
            outline: none;
            /* Border and shadow are handled by .input-group:focus-within */
        }

        /* Input Error Styling */
        .guest-layout-container .mt-2 {
            margin-top: 0.5rem; /* Equivalent to mt-2 */
        }
        .guest-layout-container [class*="text-red-"] {
            color: #ef4444; /* Tailwind red-500 */
            font-size: 0.75rem; /* Tailwind text-xs, or similar */
        }


        /* Button Styling (similar to btn-login) */
        .guest-layout-container button[type="submit"],
        .btn-login { /* Added .btn-login to ensure general button styling applies */
            width: 100%; /* Make it full width */
            padding: 10px;
            background: white;
            border: none;
            border-radius: 6px;
            color: var(--primary-color);
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out, color 0.3s ease-in-out, transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            margin-top: 1rem; /* Equivalent to mt-4 */
        }

        .guest-layout-container button[type="submit"]:hover,
        .btn-login:hover {
            background: var(--accent-color);
            color: white;
            transform: scale(1.02); /* Slightly less scale than login button */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .guest-layout-container button[type="submit"]:active,
        .btn-login:active {
            transform: scale(1);
        }

        /* Flex container for button alignment */
        .guest-layout-container .flex.items-center.justify-end.mt-4 {
            display: flex;
            align-items: center;
            justify-content: flex-end; /* Align to end */
            margin-top: 1rem;
        }

        /* Back to login link styling */
        .guest-layout-container .back-to-login {
            display: block; /* Make it take full width */
            text-align: center;
            margin-top: 20px; /* Space from the button */
            transition: transform 0.2s ease-in-out;
        }

        .guest-layout-container .back-to-login:hover {
            transform: translateY(-1px);
        }

        .guest-layout-container .back-to-login a {
            color: var(--text-muted); /* Match muted text color */
            font-size: 13px;
            text-decoration: none;
            transition: color 0.3s ease-in-out;
        }

        .guest-layout-container .back-to-login a:hover {
            text-decoration: underline;
            color: var(--accent-color); /* Highlight on hover */
        }


        
    </style>
</head>
<body>
   

    <div class="guest-layout-container">
        <div class="login-header">
            {{-- You might want to add a logo here as well if it fits the design --}}
            {{-- <img src="{{ asset('dist/assets/img/logo-fsdm-fes.png') }}" alt="FST Fès Logo" class="institution-logo"> --}}
            <h1>Mot de passe oublié</h1>
            <p>Veuillez entrer votre adresse email.</p>
        </div>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Mot de passe oublié ? Pas de problème. Indiquez-nous simplement votre adresse e-mail et nous vous enverrons un lien de réinitialisation de mot de passe qui vous permettra d\'en choisir un nouveau.') }}
        </div>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600 alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div>
                <label for="email" class="form-label">{{ __('Adresse Email') }}</label>
                <div class="input-group">
                    <span class="input-icon"><i class="fas fa-envelope"></i></span>
                    <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Votre adresse email" />
                </div>
                @error('email')
                    <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex items-center justify-end mt-4">
                <button type="submit" class="btn-login">
                    {{ __('Envoyer le lien de réinitialisation') }}
                </button>
            </div>
            <div class="back-to-login"> {{-- Added this wrapper class for styling --}}
                <a href="{{ route('login') }}">
                    {{ __('Retour à la connexion') }}
                </a>
            </div>
        </form>
    </div>
</body>
</html>