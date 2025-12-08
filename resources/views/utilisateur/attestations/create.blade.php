@extends('utilisateur.Layouts.app')
@section('title', 'Ajouter une attestation')

@section('content')

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Ajouter une attestation</h6>
                            <a href="{{ route('attestations.index') }}" class="btn btn-sm btn-outline-light me-3">
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

                    <form action="{{ route('attestations.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        
                        <div class="row g-4">
                            <div class="col-12">
                                <h5 class="section-title">Détails de l'attestation</h5>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="type_attestation" class="form-label">Type d'attestation <span class="text-danger">*</span></label>
                                <input type="text" name="type_attestation" id="type_attestation" class="form-control @error('type_attestation') is-invalid @enderror" value="{{ old('type_attestation') }}" placeholder="Entrez le type d'attestation" required>
                                @error('type_attestation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez entrer un type d'attestation valide.</div>
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

                            <div class="col-12 form-group">
                                <label for="discription" class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea name="discription" id="discription" class="form-control @error('discription') is-invalid @enderror" rows="3" placeholder="Entrez la description" required>{{ old('discription') }}</textarea>
                                @error('discription')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez entrer une description valide.</div>
                                @enderror
                            </div>

                            <div class="col-12 form-group">
                                <label for="attestation" class="form-label">Fichier (PDF, JPG, PNG) <span class="text-danger">*</span></label>
                                <input type="file" name="attestation" id="attestation" class="form-control @error('attestation') is-invalid @enderror" accept="application/pdf,image/jpeg,image/png" required>
                                @error('attestation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez sélectionner un fichier valide (PDF, JPG, PNG).</div>
                                @enderror
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="material-symbols-rounded me-2">save</i> Enregistrer
                            </button>
                            <a href="{{ route('attestations.index') }}" class="btn btn-outline-secondary ms-2">
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
