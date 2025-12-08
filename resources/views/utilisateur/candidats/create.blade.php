@extends('utilisateur.Layouts.app')

@section('title', 'Ajouter un candidat')

@section('content')

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Ajouter un nouveau candidat</h6>
                            <a href="{{ route('candidats.index') }}" class="btn btn-sm btn-outline-light me-3">
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

                    <form action="{{ route('candidats.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        
                        <!-- Formation Section -->
                        <div class="row g-4">
                            <div class="col-12">
                                <h5 class="section-title">Information de formation</h5>
                            </div>
                            <div class="col-md-12 form-group">
    <label for="formation_id" class="form-label">Type de formation <span class="text-danger">*</span></label>
    <select name="formation_id" id="formation_id" class="form-select @error('formation_id') is-invalid @enderror" required>
        <option value="">-- Choisir --</option>
        @forelse ($formations as $formation)
            <option value="{{ $formation->id }}" @if(old('formation_id') == $formation->id) selected @endif>
                {{ ucfirst($formation->type_formation) }} ({{ $formation->titre }})
            </option>
        @empty
            <option value="" disabled>Aucun type de formation active disponible</option>
        @endforelse
    </select>
    @error('formation_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @else
        <div class="invalid-feedback">Veuillez sélectionner un type de formation.</div>
    @enderror
</div>
                        </div>

                        <!-- Personal Information Section -->
                        <div class="row g-4">
                            <div class="col-12">
                                <h5 class="section-title">Information personnelle</h5>
                            </div>
                            
                            <div class="col-md-6 form-group">
                                <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" name="nom" id="nom" class="form-control @error('nom') is-invalid @enderror" value="{{ old('nom') }}" placeholder="Entrez votre nom" required>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez entrer un nom valide.</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                                <input type="text" name="prenom" id="prenom" class="form-control @error('prenom') is-invalid @enderror" value="{{ old('prenom') }}" placeholder="Entrez votre prénom" required>
                                @error('prenom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez entrer un prénom valide.</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="nom_ar" class="form-label">الاسم العائلي <span class="text-danger">*</span></label>
                                <input type="text" name="nom_ar" id="nom_ar" class="form-control text-end @error('nom_ar') is-invalid @enderror" dir="rtl" value="{{ old('nom_ar') }}" placeholder="أدخل اسمك العائلي بالعربية" required>
                                @error('nom_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">يرجى إدخال الاسم العائلي بالعربية.</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="prenom_ar" class="form-label">الاسم الشخصي <span class="text-danger">*</span></label>
                                <input type="text" name="prenom_ar" id="prenom_ar" class="form-control text-end @error('prenom_ar') is-invalid @enderror" dir="rtl" value="{{ old('prenom_ar') }}" placeholder="أدخل اسمك الشخصي بالعربية" required>
                                @error('prenom_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">يرجى إدخال الاسم الشخصي بالعربية.</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="CNE" class="form-label">CNE <span class="text-danger">*</span></label>
                                <input type="text" name="CNE" id="CNE" class="form-control @error('CNE') is-invalid @enderror" value="{{ old('CNE') }}" placeholder="Entrez votre CNE" required>
                                @error('CNE')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez entrer un CNE valide.</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="CIN" class="form-label">CIN <span class="text-danger">*</span></label>
                                <input type="text" name="CIN" id="CIN" class="form-control @error('CIN') is-invalid @enderror" value="{{ old('CIN') }}" placeholder="Entrez votre CIN" required>
                                @error('CIN')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez entrer un CIN valide.</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Entrez votre email" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez entrer un email valide.</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="date_naissance" class="form-label">Date de naissance <span class="text-danger">*</span></label>
                                <input type="date" name="date_naissance" id="date_naissance" class="form-control @error('date_naissance') is-invalid @enderror" value="{{ old('date_naissance') }}" required>
                                @error('date_naissance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez entrer une date de naissance valide.</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="ville_naissance" class="form-label">Ville naissance <span class="text-danger">*</span></label>
                                <input type="text" name="ville_naissance" id="ville_naissance" class="form-control @error('ville_naissance') is-invalid @enderror" value="{{ old('ville_naissance') }}" placeholder="Entrez la ville de naissance" required>
                                @error('ville_naissance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez entrer une ville de naissance valide.</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="ville_naissance_ar" class="form-label">مدينة الولادة <span class="text-danger">*</span></label>
                                <input type="text" name="ville_naissance_ar" id="ville_naissance_ar" class="form-control text-end @error('ville_naissance_ar') is-invalid @enderror" dir="rtl" value="{{ old('ville_naissance_ar') }}" placeholder="أدخل مدينة الولادة بالعربية" required>
                                @error('ville_naissance_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">يرجى إدخال مدينة الولادة بالعربية.</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="province" class="form-label">Province <span class="text-danger">*</span></label>
                                <input type="text" name="province" id="province" class="form-control @error('province') is-invalid @enderror" value="{{ old('province') }}" placeholder="Entrez votre province" required>
                                @error('province')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez entrer une province valide.</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="pay_naissance" class="form-label">Pays naissance <span class="text-danger">*</span></label>
                                <input type="text" name="pay_naissance" id="pay_naissance" class="form-control @error('pay_naissance') is-invalid @enderror" value="{{ old('pay_naissance') }}" placeholder="Entrez votre pays de naissance" required>
                                @error('pay_naissance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez entrer un pays de naissance valide.</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="nationalite" class="form-label">Nationalité <span class="text-danger">*</span></label>
                                <input type="text" name="nationalite" id="nationalite" class="form-control @error('nationalite') is-invalid @enderror" value="{{ old('nationalite') }}" placeholder="Entrez votre nationalité" required>
                                @error('nationalite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez entrer une nationalité valide.</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="sexe" class="form-label">Sexe <span class="text-danger">*</span></label>
                                <select name="sexe" id="sexe" class="form-select @error('sexe') is-invalid @enderror" required>
                                    <option value="M" @if(old('sexe') == 'M') selected @endif>Masculin</option>
                                    <option value="F" @if(old('sexe') == 'F') selected @endif>Féminin</option>
                                </select>
                                @error('sexe')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez sélectionner un sexe.</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <div class="row g-4">
                            <div class="col-12">
                                <h5 class="section-title">Information de contact</h5>
                            </div>
                            
                            <div class="col-md-6 form-group">
                                <label for="telephone_mob" class="form-label">Téléphone mobile <span class="text-danger">*</span> <small class="text-muted">(ex: +212612345678 ou 0612345678)</small></label>
                                <input type="text" name="telephone_mob" id="telephone_mob" class="form-control @error('telephone_mob') is-invalid @enderror" value="{{ old('telephone_mob') }}" placeholder="Entrez votre téléphone mobile" required>
                                @error('telephone_mob')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez entrer un numéro de téléphone mobile valide.</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="telephone_fix" class="form-label">Téléphone fixe</label>
                                <input type="text" name="telephone_fix" id="telephone_fix" class="form-control @error('telephone_fix') is-invalid @enderror" value="{{ old('telephone_fix') }}" placeholder="Entrez votre téléphone fixe">
                                @error('telephone_fix')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="ville" class="form-label">Ville <span class="text-danger">*</span></label>
                                <input type="text" name="ville" id="ville" class="form-control @error('ville') is-invalid @enderror" value="{{ old('ville') }}" placeholder="Entrez votre ville" required>
                                @error('ville')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez entrer une ville valide.</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="pays" class="form-label">Pays <span class="text-danger">*</span></label>
                                <input type="text" name="pays" id="pays" class="form-control @error('pays') is-invalid @enderror" value="{{ old('pays') }}" placeholder="Entrez votre pays" required>
                                @error('pays')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez entrer un pays valide.</div>
                                @enderror
                            </div>

                            <div class="col-12 form-group">
                                <label for="adresse" class="form-label">Adresse <span class="text-danger">*</span></label>
                                <textarea name="adresse" id="adresse" class="form-control @error('adresse') is-invalid @enderror" rows="3" placeholder="Entrez votre adresse" required>{{ old('adresse') }}</textarea>
                                @error('adresse')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez entrer une adresse valide.</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Bac Information Section -->
                        <div class="row g-4">
                            <div class="col-12">
                                <h5 class="section-title">Information du Bac</h5>
                            </div>
                            
                            <div class="col-md-6 form-group">
                                <label for="serie_bac" class="form-label">Série Bac <span class="text-danger">*</span></label>
                                <input type="text" name="serie_bac" id="serie_bac" class="form-control @error('serie_bac') is-invalid @enderror" value="{{ old('serie_bac') }}" placeholder="Entrez la série du Bac" required>
                                @error('serie_bac')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez entrer la série du Bac.</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="annee_bac" class="form-label">Année Bac <span class="text-danger">*</span></label>
                                <select name="annee_bac" id="annee_bac" class="form-select @error('annee_bac') is-invalid @enderror" required>
                                    <option value="">-- Sélectionnez --</option>
                                    @for ($i = now()->year; $i >= 2000; $i--)
                                        <option value="{{ ($i-1) . '/' . $i }}" @if(old('annee_bac') == ($i-1).'/'.$i) selected @endif>{{ ($i-1) . '/' . $i }}</option>
                                    @endfor
                                </select>
                                @error('annee_bac')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez sélectionner l'année du Bac.</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="scan_bac" class="form-label">Scan Bac <span class="text-danger">*</span></label>
                                <input type="file" name="scan_bac" id="scan_bac" class="form-control @error('scan_bac') is-invalid @enderror" accept="application/pdf,image/*" required>
                                @error('scan_bac')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez sélectionner un fichier valide pour le scan du Bac.</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Documents Section -->
                        <div class="row g-4">
                            <div class="col-12">
                                <h5 class="section-title">Documents requis</h5>
                            </div>
                            
                            <div class="col-md-6 form-group">
                                <label for="CV" class="form-label">CV (PDF) <span class="text-danger">*</span></label>
                                <input type="file" name="CV" id="CV" class="form-control @error('CV') is-invalid @enderror" accept="application/pdf" required>
                                @error('CV')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez sélectionner un fichier PDF pour le CV.</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="demande" class="form-label">Demande (PDF) <span class="text-danger">*</span></label>
                                <input type="file" name="demande" id="demande" class="form-control @error('demande') is-invalid @enderror" accept="application/pdf" required>
                                @error('demande')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez sélectionner un fichier PDF pour la demande.</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="scan_cartid" class="form-label">Carte d'identité (scan) <span class="text-danger">*</span></label>
                                <input type="file" name="scan_cartid" id="scan_cartid" class="form-control @error('scan_cartid') is-invalid @enderror" accept="application/pdf,image/*" required>
                                @error('scan_cartid')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez sélectionner un fichier valide pour la carte d'identité.</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="photo" class="form-label">Photo <span class="text-danger">*</span></label>
                                <input type="file" name="photo" id="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*" required>
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="invalid-feedback">Veuillez sélectionner une image pour la photo.</div>
                                @enderror
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary" style="background-color: #1a4b8c">
                                <i class="material-symbols-rounded me-2">save</i> Enregistrer
                            </button>
                            <a href="{{ route('candidats.index') }}" class="btn btn-outline-secondary ms-2">
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
