@extends('utilisateur.Layouts.app')
@section('title', 'Ajouter un utilisateur')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Ajouter un nouvel utilisateur</h6>
                            <a href="{{ route('administrateurs.index') }}" class="btn btn-sm btn-outline-light me-3">
                                <i class="material-symbols-rounded">arrow_back</i> Retour
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mb-4">
                            <strong>Oops !</strong> Veuillez vérifier les champs du formulaire pour corriger les erreurs.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('administrateurs.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        
                        <div class="row g-4">
                            <div class="col-12">
                                <h5 class="section-title">Détails de l'utilisateur</h5>
                            </div>
                            <div class="col-12 form-group">
                                <label for="name" class="form-label">Nom et Prénom <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Entrez le nom et prénom" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez entrer un nom et prénom valides.</div>
                                @enderror
                            </div>

                            <div class="col-12 form-group">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Entrez l'email" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez entrer un email valide.</div>
                                @enderror
                                @if($errors->has('email_exists'))
    <div class="alert alert-danger mt-2">
        {{ $errors->first('email_exists') }}
    </div>
@endif
                            </div>

                            <div class="col-12 form-group">
                                <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Entrez le mot de passe (min 8 caractères)" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Le mot de passe doit contenir au moins 8 caractères.</div>
                                @enderror
                            </div>

                            <div class="col-12 form-group">
                                <label for="password_confirmation" class="form-label">Confirmation du mot de passe <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password') is-invalid @enderror" placeholder="Confirmez le mot de passe" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Les mots de passe doivent correspondre.</div>
                                @enderror
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="material-symbols-rounded me-2">save</i> Enregistrer
                            </button>
                            <a href="{{ route('administrateurs.index') }}" class="btn btn-outline-secondary ms-2">
                                <i class="material-symbols-rounded me-2">cancel</i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection