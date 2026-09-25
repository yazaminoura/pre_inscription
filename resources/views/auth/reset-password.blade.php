@extends('auth.layout')
@section('title', 'Nouveau mot de passe')

@section('content')
<h2>Nouveau mot de passe</h2>
<p class="lead-muted">Choisissez un mot de passe d'au moins 8 caractères.</p>

@if (session('status'))
    <div class="alert alert-success py-2 small">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('password.store') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <div class="mb-3">
        <label for="email" class="form-label">Adresse email</label>
        <div class="input-icon">
            <span class="material-symbols-rounded">mail</span>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $request->email) }}" required autocomplete="username">
        </div>
        @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Nouveau mot de passe</label>
        <div class="input-icon">
            <span class="material-symbols-rounded">lock</span>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
            <button type="button" class="toggle-pass" onclick="togglePassword(this)" aria-label="Afficher le mot de passe">
                <span class="material-symbols-rounded">visibility</span>
            </button>
        </div>
        @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>

    <div class="mb-4">
        <label for="password_confirmation" class="form-label">Confirmation</label>
        <div class="input-icon">
            <span class="material-symbols-rounded">lock</span>
            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
        </div>
    </div>

    <button type="submit" class="btn btn-brand w-100 justify-content-center py-2">
        <span class="material-symbols-rounded">save</span> Enregistrer
    </button>
</form>

<p class="text-center small mt-4 mb-0">
    <a href="{{ route('login') }}" class="fw-semibold text-decoration-none"><span class="material-symbols-rounded">arrow_back</span> Retour à la connexion</a>
</p>
@endsection
