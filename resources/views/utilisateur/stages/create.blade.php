@extends('utilisateur.layouts.app')
@section('title', 'Ajouter un stage')

@section('content')
<link rel="stylesheet" href="{{ asset('dist/assets/css/form-styles.css') }}">

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Ajouter un stage</h6>
                            <a href="{{ route('stages.index') }}" class="btn btn-sm btn-outline-light me-3">
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

                    <form action="{{ route('stages.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        
                        <div class="row g-4">
                            <div class="col-12">
                                <h5 class="section-title">Détails du stage</h5>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="fonction" class="form-label">Fonction <span class="text-danger">*</span></label>
                                <input type="text" name="fonction" id="fonction" class="form-control @error('fonction') is-invalid @enderror" value="{{ old('fonction') }}" placeholder="Entrez la fonction" required>
                                @error('fonction')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez entrer une fonction valide.</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="periode" class="form-label">Période <span class="text-danger">*</span></label>
                                <input type="text" name="periode" id="periode" class="form-control @error('periode') is-invalid @enderror" value="{{ old('periode') }}" placeholder="Ex: Juin 2023 - Août 2023" required>
                                @error('periode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez entrer une période valide.</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="etablissement" class="form-label">Établissement <span class="text-danger">*</span></label>
                                <input type="text" name="etablissement" id="etablissement" class="form-control @error('etablissement') is-invalid @enderror" value="{{ old('etablissement') }}" placeholder="Entrez l'établissement" required>
                                @error('etablissement')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez entrer un établissement valide.</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="secteur_activite" class="form-label">Secteur d'activité <span class="text-danger">*</span></label>
                                <input type="text" name="secteur_activite" id="secteur_activite" class="form-control @error('secteur_activite') is-invalid @enderror" value="{{ old('secteur_activite') }}" placeholder="Entrez le secteur d'activité" required>
                                @error('secteur_activite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez entrer un secteur d'activité valide.</div>
                                @enderror
                            </div>

                            <div class="col-12 form-group">
                                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Entrez la description" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez entrer une description valide.</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="attestation" class="form-label">Attestation (PDF, JPG, PNG)</label>
                                <input type="file" name="attestation" id="attestation" class="form-control @error('attestation') is-invalid @enderror" accept="application/pdf,image/jpeg,image/png">
                                @error('attestation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez sélectionner un fichier valide (PDF, JPG, PNG).</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="candidat_id" class="form-label">Candidat <span class="text-danger">*</span></label>
                                <select name="candidat_id" id="candidat_id" class="form-select @error('candidat_id') is-invalid @enderror" required>
                                    <option value="">-- Choisir un candidat --</option>
                                    @foreach ($candidats as $candidat)
                                        <option value="{{ $candidat->id }}" @if(old('candidat_id') == $candidat->id) selected @endif>{{ $candidat->nom }} {{ $candidat->prenom }}</option>
                                    @endforeach
                                </select>
                                @error('candidat_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez sélectionner un candidat.</div>
                                @enderror
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="material-symbols-rounded me-2">save</i> Enregistrer
                            </button>
                            <a href="{{ route('stages.index') }}" class="btn btn-outline-secondary ms-2">
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