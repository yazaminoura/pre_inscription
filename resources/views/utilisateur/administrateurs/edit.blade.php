@extends('utilisateur.Layouts.app')
@section('title', 'Modifier utilisateur')

@section('content')
<link rel="stylesheet" href="{{ asset('dist/assets/css/form-styles.css') }}">

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Modifier l'utilisateur</h6>
                            <a href="{{ route('administrateurs.index') }}" class="btn btn-sm btn-outline-light me-3">
                                <i class="material-symbols-rounded">arrow_back</i> Retour
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-4 pb-2">
                    @if ($errors->any())
                        <div class="alert alert-danger text-white mb-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('administrateurs.update', $user->id) }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        <!-- Name Field -->
                        <div class="form-group">
                            <label for="name" class="form-label">Nom et Prénom <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div class="form-group">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Current Password (Always Required) -->
                        <div class="form-group mt-4">
                            <label for="current_password" class="form-label">Mot de passe actuel <span class="text-danger">*</span></label>
                            <input type="password" name="current_password" id="current_password" 
                                   class="form-control @error('current_password') is-invalid @enderror" 
                                   placeholder="Entrez votre mot de passe actuel" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Requis pour confirmer toute modification</small>
                        </div>

                        <!-- Optional Password Change Section -->
                        <div class="form-group mt-4">
                            <button type="button" id="togglePasswordChange" class="btn btn-outline-primary">
                                <i class="material-symbols-rounded me-2">lock</i> Changer le mot de passe
                            </button>
                        </div>

                        <div id="passwordFields" style="display: none;" class="border-top pt-3 mt-3">
                            <div class="form-group">
                                <label for="password" class="form-label">Nouveau mot de passe</label>
                                <input type="password" name="password" id="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       placeholder="Entrez le nouveau mot de passe">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" 
                                       class="form-control" placeholder="Confirmez le nouveau mot de passe">
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="material-symbols-rounded me-2">save</i> Mettre à jour
                            </button>
                            <a href="{{ route('administrateurs.index') }}" class="btn btn-outline-secondary btn-lg ms-2">
                                <i class="material-symbols-rounded me-2">cancel</i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('togglePasswordChange');
        const passwordFields = document.getElementById('passwordFields');
        
        toggleBtn.addEventListener('click', function() {
            if (passwordFields.style.display === 'none') {
                passwordFields.style.display = 'block';
                toggleBtn.innerHTML = '<i class="material-symbols-rounded me-2">lock_open</i> Masquer le changement';
                toggleBtn.classList.remove('btn-outline-primary');
                toggleBtn.classList.add('btn-outline-secondary');
            } else {
                passwordFields.style.display = 'none';
                toggleBtn.innerHTML = '<i class="material-symbols-rounded me-2">lock</i> Changer le mot de passe';
                toggleBtn.classList.remove('btn-outline-secondary');
                toggleBtn.classList.add('btn-outline-primary');
                // Clear password fields when hiding
                document.getElementById('password').value = '';
                document.getElementById('password_confirmation').value = '';
            }
        });
        
        // Show fields if there are password errors
        @if($errors->has('password'))
            passwordFields.style.display = 'block';
            toggleBtn.innerHTML = '<i class="material-symbols-rounded me-2">lock_open</i> Masquer le changement';
            toggleBtn.classList.remove('btn-outline-primary');
            toggleBtn.classList.add('btn-outline-secondary');
        @endif
    });
</script>
@endpush
@endsection