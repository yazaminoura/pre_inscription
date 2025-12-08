@extends('utilisateur.Layouts.app')
@section('title', 'Ajouter une formation')

@section('content')

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Ajouter une nouvelle formation</h6>
                            <a href="{{ route('formations.index') }}" class="btn btn-sm btn-outline-light me-3">
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

                    <form action="{{ route('formations.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                        
                        <div class="row g-4">
                            <div class="col-12">
                                <h5 class="section-title">Détails de la formation</h5>
                            </div>
                            <div class="col-12 form-group">
                                <label for="type_formation" class="form-label">Type de formation <span class="text-danger">*</span></label>
                                <select name="type_formation" id="type_formation" class="form-select @error('type_formation') is-invalid @enderror" required>
                                    <option value="">Choisir...</option>
                                    <option value="Licence" @if(old('type_formation') == 'Licence') selected @endif>Licence</option>
                                    <option value="Master" @if(old('type_formation') == 'Master') selected @endif>Master</option>
                                </select>
                                @error('type_formation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez sélectionner un type de formation.</div>
                                @enderror
                            </div>

                            <div class="col-12 form-group">
                                <label for="titre" class="form-label">Titre <span class="text-danger">*</span></label>
                                <input type="text" name="titre" id="titre" class="form-control @error('titre') is-invalid @enderror" value="{{ old('titre') }}" placeholder="Entrez le titre" required>
                                @error('titre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez entrer un titre valide.</div>
                                @enderror
                            </div>

                            <div class="col-12 form-group">
                                <label for="date_debut" class="form-label">Date début de Pre Insciption<span class="text-danger">*</span></label>
                                <input type="date" name="date_debut" id="date_debut" class="form-control @error('date_debut') is-invalid @enderror" value="{{ old('date_debut') }}" required>
                                @error('date_debut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez entrer une date de début valide.</div>
                                @enderror
                            </div>

                            <div class="col-12 form-group">
                                <label for="date_fin" class="form-label">Date fin de Pre Insciption<span class="text-danger">*</span></label>
                                <input type="date" name="date_fin" id="date_fin" class="form-control @error('date_fin') is-invalid @enderror" value="{{ old('date_fin') }}" required>
                                @error('date_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez entrer une date de fin valide.</div>
                                @enderror
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="material-symbols-rounded me-2">save</i> Enregistrer
                            </button>
                            <a href="{{ route('formations.index') }}" class="btn btn-outline-secondary ms-2">
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
