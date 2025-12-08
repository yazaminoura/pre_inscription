@extends('utilisateur.layouts.app')
@section('title', 'Modifier Formation')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/form-styles.css') }}">

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Modifier la Formation</h6>
                            <a href="{{ route('formations.index') }}" class="btn btn-sm btn-outline-light me-3">
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

                    <form action="{{ route('formations.update', $formation->id) }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                        <div class="form-group">
                            <label for="type_formation" class="form-label">Type de formation <span class="text-danger">*</span></label>
                            <select name="type_formation" id="type_formation" class="form-select @error('type_formation') is-invalid @enderror" required>
                                <option value="">Choisir...</option>
                                <option value="Licence" @if(old('type_formation', $formation->type_formation) == 'Licence') selected @endif>Licence</option>
                                <option value="Master" @if(old('type_formation', $formation->type_formation) == 'Master') selected @endif>Master</option>
                            </select>
                            @error('type_formation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="invalid-feedback">Veuillez sélectionner un type de formation.</div>
                        </div>

                        <div class="form-group">
                            <label for="titre" class="form-label">Titre <span class="text-danger">*</span></label>
                            <input type="text" name="titre" id="titre" class="form-control @error('titre') is-invalid @enderror" value="{{ old('titre', $formation->titre) }}" placeholder="Entrez le titre" required>
                            @error('titre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="invalid-feedback">Veuillez entrer un titre valide.</div>
                        </div>

                        <div class="form-group">
                            <label for="date_debut" class="form-label">Date début de Pre Insciption <span class="text-danger">*</span></label>
                            <input type="date" name="date_debut" id="date_debut" class="form-control @error('date_debut') is-invalid @enderror" value="{{ old('date_debut', $formation->date_debut) }}" required>
                            @error('date_debut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="invalid-feedback">Veuillez entrer une date de début valide.</div>
                        </div>

                        <div class="form-group">
                            <label for="date_fin" class="form-label">Date fin de Pre Insciption<span class="text-danger">*</span></label>
                            <input type="date" name="date_fin" id="date_fin" class="form-control @error('date_fin') is-invalid @enderror" value="{{ old('date_fin', $formation->date_fin) }}" required>
                            @error('date_fin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="invalid-feedback">Veuillez entrer une date de fin valide.</div>
                        </div>

                        <div class="form-group">
                            <label for="user" class="form-label">Responsable</label>
                            <input type="text" id="user" class="form-control" value="{{ $formation->user->name ?? 'Non assigné' }}" readonly>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="material-symbols-rounded me-2">save</i> Mettre à jour
                            </button>
                            <a href="{{ route('formations.index') }}" class="btn btn-outline-secondary btn-lg ms-2">
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