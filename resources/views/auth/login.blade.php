@extends('auth.layout')
@section('title', 'Connexion')

@section('content')
<h2>Connexion</h2>
<p class="lead-muted">Accédez à l'espace d'administration.</p>

@if (session('status'))
    <div class="alert alert-success py-2 small">{{ session('status') }}</div>
@endif
@if ($errors->any())
    <div class="alert alert-danger py-2 small d-flex align-items-center gap-2">
        <span class="material-symbols-rounded">error</span> {{ $errors->first() }}
    </div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="mb-3">
        <label for="email" class="form-label">Adresse email</label>
        <div class="input-icon">
            <span class="material-symbols-rounded">mail</span>
            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nom@exemple.ma">
        </div>
    </div>

    <div class="mb-2">
        <label for="password" class="form-label">Mot de passe</label>
        <div class="input-icon">
            <span class="material-symbols-rounded">lock</span>
            <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password" placeholder="Votre mot de passe">
            <button type="button" class="toggle-pass" onclick="togglePassword(this)" aria-label="Afficher le mot de passe">
                <span class="material-symbols-rounded">visibility</span>
            </button>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check m-0">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label small" for="remember">Se souvenir de moi</label>
        </div>
        <a href="{{ route('password.request') }}" class="small fw-semibold text-decoration-none">Mot de passe oublié ?</a>
    </div>

    <button type="submit" class="btn btn-brand w-100 justify-content-center py-2">
        Se connecter <span class="material-symbols-rounded">arrow_forward</span>
    </button>
</form>

<p class="text-center small text-muted mt-4 mb-0">
    Vous êtes candidat ? <a href="{{ route('candidat.form') }}" class="fw-semibold text-decoration-none">Déposer une préinscription</a>
</p>
@endsection
