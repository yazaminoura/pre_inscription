@extends('auth.layout')
@section('title', 'Mot de passe oublié')

@section('content')
<h2>Mot de passe oublié</h2>
<p class="lead-muted">Entrez votre email : nous vous enverrons un lien pour choisir un nouveau mot de passe.</p>

@if (session('status'))
    <div class="alert alert-success py-2 small">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf
    <div class="mb-4">
        <label for="email" class="form-label">Adresse email</label>
        <div class="input-icon">
            <span class="material-symbols-rounded">mail</span>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus placeholder="nom@exemple.ma">
        </div>
        @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>

    <button type="submit" class="btn btn-brand w-100 justify-content-center py-2">
        <span class="material-symbols-rounded">send</span> Envoyer le lien
    </button>
</form>

<p class="text-center small mt-4 mb-0">
    <a href="{{ route('login') }}" class="fw-semibold text-decoration-none"><span class="material-symbols-rounded">arrow_back</span> Retour à la connexion</a>
</p>
@endsection
