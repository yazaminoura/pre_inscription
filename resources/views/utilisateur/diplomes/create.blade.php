@extends('utilisateur.Layouts.app')
@section('title', 'Ajouter un diplôme')

@section('content')
<link rel="stylesheet" href="{{ asset('css/form-styles.css') }}">

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Ajouter un nouveau diplôme</h6>
                            <a href="{{ route('diplomes.index') }}" class="btn btn-sm btn-outline-light me-3">
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

                    <form action="{{ route('diplomes.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        
                        <div class="row g-4">
                            <div class="col-12">
                                <h5 class="section-title">Diplôme Bac+2</h5>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="type_diplome_bac_2" class="form-label">Type de diplôme Bac+2</label>
                                <input type="text" name="type_diplome_bac_2" id="type_diplome_bac_2" class="form-control @error('type_diplome_bac_2') is-invalid @enderror" value="{{ old('type_diplome_bac_2') }}" placeholder="Entrez le type de diplôme Bac+2">
                                @error('type_diplome_bac_2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez entrer un type de diplôme valide.</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="annee_diplome_bac_2" class="form-label">Année Bac+2</label>
                                <input type="date" name="annee_diplome_bac_2" id="annee_diplome_bac_2" class="form-control @error('annee_diplome_bac_2') is-invalid @enderror" value="{{ old('annee_diplome_bac_2') }}">
                                @error('annee_diplome_bac_2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez entrer une année valide.</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="filiere_diplome_bac_2" class="form-label">Filière Bac+2</label>
                                <input type="text" name="filiere_diplome_bac_2" id="filiere_diplome_bac_2" class="form-control @error('filiere_diplome_bac_2') is-invalid @enderror" value="{{ old('filiere_diplome_bac_2') }}" placeholder="Entrez la filière Bac+2">
                                @error('filiere_diplome_bac_2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez entrer une filière valide.</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="etablissement_bac_2" class="form-label">Établissement Bac+2</label>
                                <input type="text" name="etablissement_bac_2" id="etablissement_bac_2" class="form-control @error('etablissement_bac_2') is-invalid @enderror" value="{{ old('etablissement_bac_2') }}" placeholder="Entrez l'établissement Bac+2">
                                @error('etablissement_bac_2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez entrer un établissement valide.</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="scan_bac_2" class="form-label">Scan Bac+2</label>
                                <input type="file" name="scan_bac_2" id="scan_bac_2" class="form-control @error('scan_bac_2') is-invalid @enderror" accept="application/pdf,image/*">
                                @error('scan_bac_2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez sélectionner un fichier valide (PDF, image).</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <h5 class="section-title">Diplôme Bac+3</h5>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="type_diplome_bac_3" class="form-label">Type de diplôme Bac+3</label>
                                <input type="text" name="type_diplome_bac_3" id="type_diplome_bac_3" class="form-control @error('type_diplome_bac_3') is-invalid @enderror" value="{{ old('type_diplome_bac_3') }}" placeholder="Entrez le type de diplôme Bac+3">
                                @error('type_diplome_bac_3')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez entrer un type de diplôme valide.</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="annee_diplome_bac_3" class="form-label">Année Bac+3</label>
                                <input type="date" name="annee_diplome_bac_3" id="annee_diplome_bac_3" class="form-control @error('annee_diplome_bac_3') is-invalid @enderror" value="{{ old('annee_diplome_bac_3') }}">
                                @error('annee_diplome_bac_3')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez entrer une année valide.</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="filiere_diplome_bac_3" class="form-label">Filière Bac+3</label>
                                <input type="text" name="filiere_diplome_bac_3" id="filiere_diplome_bac_3" class="form-control @error('filiere_diplome_bac_3') is-invalid @enderror" value="{{ old('filiere_diplome_bac_3') }}" placeholder="Entrez la filière Bac+3">
                                @error('filiere_diplome_bac_3')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez entrer une filière valide.</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="etablissement_bac_3" class="form-label">Établissement Bac+3</label>
                                <input type="text" name="etablissement_bac_3" id="etablissement_bac_3" class="form-control @error('etablissement_bac_3') is-invalid @enderror" value="{{ old('etablissement_bac_3') }}" placeholder="Entrez l'établissement Bac+3">
                                @error('etablissement_bac_3')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez entrer un établissement valide.</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="scan_bac_3" class="form-label">Scan Bac+3</label>
                                <input type="file" name="scan_bac_3" id="scan_bac_3" class="form-control @error('scan_bac_3') is-invalid @enderror" accept="application/pdf,image/*">
                                @error('scan_bac_3')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez sélectionner un fichier valide (PDF, image).</div>
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
                            <a href="{{ route('diplomes.index') }}" class="btn btn-outline-secondary ms-2">
                                <i class="material-symbols-rounded me-2">cancel</i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Bootstrap client-side validation
    (function () {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>
@endsection