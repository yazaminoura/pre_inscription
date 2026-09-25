@extends('utilisateur.layouts.app')
@section('title', 'Nouvel administrateur')

@section('content')
<div class="mb-3">
  <a href="{{ route('administrateurs.index') }}" class="text-decoration-none fw-semibold">
    <span class="material-symbols-rounded">arrow_back</span> Administrateurs
  </a>
</div>

<div class="panel" style="max-width: 880px;">
  <div class="panel-head"><h3><span class="material-symbols-rounded">person_add</span> Nouvel administrateur</h3></div>
  <div class="panel-body">
    <form action="{{ route('administrateurs.store') }}" method="POST">
      @csrf
      <div class="row g-3">
        <div class="col-md-6">
          <label for="name" class="form-label">Nom et prénom <span class="text-danger">*</span></label>
          <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
          @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
          <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
          <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
          @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
          <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
          <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="8 caractères minimum" required>
          @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
          <label for="password_confirmation" class="form-label">Confirmation <span class="text-danger">*</span></label>
          <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
        </div>
      </div>
      <div class="d-flex gap-2 justify-content-end mt-4">
        <a href="{{ route('administrateurs.index') }}" class="btn btn-light">Annuler</a>
        <button type="submit" class="btn btn-brand"><span class="material-symbols-rounded">save</span> Créer le compte</button>
      </div>
    </form>
  </div>
</div>
@endsection
