@extends('utilisateur.layouts.app')
@section('title', $user->id === auth()->id() ? 'Mon compte' : 'Modifier un administrateur')

@section('content')
<div class="mb-3">
  <a href="{{ route('administrateurs.index') }}" class="text-decoration-none fw-semibold">
    <span class="material-symbols-rounded">arrow_back</span> Administrateurs
  </a>
</div>

<div class="panel" style="max-width: 880px;">
  <div class="panel-head"><h3><span class="material-symbols-rounded">manage_accounts</span> {{ $user->name }}</h3></div>
  <div class="panel-body">
    <form action="{{ route('administrateurs.update', $user) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="row g-3">
        <div class="col-md-6">
          <label for="name" class="form-label">Nom et prénom <span class="text-danger">*</span></label>
          <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
          @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
          <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
          <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
          @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-12">
          <details @if ($errors->has('password')) open @endif>
            <summary class="fw-semibold" style="cursor: pointer; color: var(--brand);">Changer le mot de passe</summary>
            <div class="row g-3 mt-1">
              <div class="col-md-6">
                <label for="password" class="form-label">Nouveau mot de passe</label>
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="8 caractères minimum">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6">
                <label for="password_confirmation" class="form-label">Confirmation</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
              </div>
            </div>
          </details>
        </div>

        <div class="col-12">
          <div class="p-3 rounded-3" style="background: var(--bg);">
            <label for="current_password" class="form-label">Votre mot de passe actuel <span class="text-danger">*</span></label>
            <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
            @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <div class="form-text">Demandé pour confirmer toute modification.</div>
          </div>
        </div>
      </div>
      <div class="d-flex gap-2 justify-content-end mt-4">
        <a href="{{ route('administrateurs.index') }}" class="btn btn-light">Annuler</a>
        <button type="submit" class="btn btn-brand"><span class="material-symbols-rounded">save</span> Enregistrer</button>
      </div>
    </form>
  </div>
</div>
@endsection
