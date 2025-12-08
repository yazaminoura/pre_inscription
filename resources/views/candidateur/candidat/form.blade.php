@extends('candidateur.layout.index')

@section('content')


<div class="container inscription-form">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-section">
        <form action="{{ route('candidat.submit') }}" method="POST" enctype="multipart/form-data" id="candidat-form">
            @csrf
            <input type="hidden" name="step" value="{{ $step }}">

            <!-- Step 1: Informations Personnelles -->
            @if ($step == 1)
                <h2 class="section-title">Informations Personnelles</h2>
                <div class="row">
                    <!-- Type de Formation Dropdown -->
                    <div class="col-md-12 mb-3">
                        <label for="type_formation">Type de Formation</label>
                        <select name="type_formation" class="form-control" id="type_formation" required>
                            <option value="">Sélectionner un type de formation</option>
                            @forelse($types_formation as $type)
                                <option value="{{ $type }}" {{ old('type_formation', $data['type_formation'] ?? '') == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @empty
                                <option value="" disabled>Aucun type de formation active disponible</option>
                            @endforelse
                        </select>
                        @error('type_formation') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <!-- Titre de Formation Dropdown -->
                    <div class="col-md-12 mb-3">
                        <label for="titre_id">Titre de Formation</label>
                        <select name="titre_id" class="form-control" id="titre_id" required>
                            <option value="">Sélectionner un titre de formation</option>
                            @forelse($titres as $titre)
                                <option value="{{ $titre->id }}"
                                        data-type="{{ $titre->type_formation }}"
                                        {{ old('titre_id', $data['titre_id'] ?? '') == $titre->id ? 'selected' : '' }}>
                                    {{ $titre->titre }}
                                    @if($titre->datedebut && $titre->datefin)
                                        ({{ $titre->datedebut->format('d/m/Y') }} - {{ $titre->datefin->format('d/m/Y') }})
                                    
                                    @endif
                                </option>
                            @empty
                                <option value="" disabled>Aucune formation active disponible</option>
                            @endforelse
                        </select>
                        @error('titre_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="nom">Nom</label>
                        <input type="text" name="nom" class="form-control" id="nom" placeholder="Entrez votre nom" value="{{ old('nom', $data['nom'] ?? '') }}" required>
                        @error('nom') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="prenom">Prénom</label>
                        <input type="text" name="prenom" class="form-control" id="prenom" placeholder="Entrez votre prénom" value="{{ old('prenom', $data['prenom'] ?? '') }}" required>
                        @error('prenom') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-12 mb-3" dir="rtl">
                        <label for="nom_ar">الاسم العائلي بالعربية</label>
                        <input type="text" name="nom_ar" class="form-control" id="nom_ar" placeholder="الاسم العائلي بالعربية" value="{{ old('nom_ar', $data['nom_ar'] ?? '') }}" required>
                        @error('nom_ar') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-12 mb-3" dir="rtl">
                        <label for="prenom_ar">الاسم الشخصي بالعربية</label>
                        <input type="text" name="prenom_ar" class="form-control" id="prenom_ar" placeholder="الاسم الشخصي بالعربية" value="{{ old('prenom_ar', $data['prenom_ar'] ?? '') }}" required>
                        @error('prenom_ar') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="CNE">CNE</label>
                        <input type="text" name="CNE" class="form-control" id="CNE" placeholder="Entrez votre CNE" value="{{ old('CNE', $data['CNE'] ?? '') }}" required>
                        @error('CNE') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="CIN">CIN</label>
                        <input type="text" name="CIN" class="form-control" id="CIN" placeholder="Entrez votre CIN" value="{{ old('CIN', $data['CIN'] ?? '') }}" required>
                        @error('CIN') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="date_naissance">Date de naissance</label>
                        <input type="date" name="date_naissance" class="form-control" id="date_naissance" value="{{ old('date_naissance', $data['date_naissance'] ?? '') }}" required>
                        @error('date_naissance') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="ville_naissance">Ville de naissance</label>
                        <input type="text" name="ville_naissance" class="form-control" Tasmania id="ville_naissance" placeholder="Entrez votre ville de naissance" value="{{ old('ville_naissance', $data['ville_naissance'] ?? '') }}" required>
                        @error('ville_naissance') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-12 mb-3" dir="rtl">
                        <label for="ville_naissance_ar">مدينة الازدياد بالعربية</label>
                        <input type="text" name="ville_naissance_ar" class="form-control" id="ville_naissance_ar" placeholder="مدينة الازدياد بالعربية" value="{{ old('ville_naissance_ar', $data['ville_naissance_ar'] ?? '') }}" required>
                        @error('ville_naissance_ar') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="province">Province</label>
                        <input type="text" name="province" class="form-control" id="province" placeholder="Entrez votre province" value="{{ old('province', $data['province'] ?? '') }}" required>
                        @error('province') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="pay_naissance">Pays de naissance</label>
                        <input type="text" name="pay_naissance" class="form-control" id="pay_naissance" placeholder="Entrez votre pays de naissance" value="{{ old('pay_naissance', $data['pay_naissance'] ?? '') }}" required>
                        @error('pay_naissance') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="nationalite">Nationalité</label>
                        <input type="text" name="nationalite" class="form-control" id="nationalite" placeholder="Entrez votre nationalité" value="{{ old('nationalite', $data['nationalite'] ?? '') }}" required>
                        @error('nationalite') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="sex">Sexe</label>
                        <select name="sex" class="form-control" id="sex" required>
                            <option value="">Sélectionner un sexe</option>
                            <option value="Homme" {{ old('sex', $data['sex'] ?? '') == 'Homme' ? 'selected' : '' }}>Homme</option>
                            <option value="Femme" {{ old('sex', $data['sex'] ?? '') == 'Femme' ? 'selected' : '' }}>Femme</option>
                        </select>
                        @error('sex') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="telephone_mob">Téléphone mobile (ex:+212620041123)</label>
                        <input type="text" name="telephone_mob" class="form-control" id="telephone_mob" placeholder="Entrez votre téléphone mobile" value="{{ old('telephone_mob', $data['telephone_mob'] ?? '') }}" required>
                        @error('telephone_mob') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="telephone_fix">Téléphone fixe</label>
                        <input type="text" name="telephone_fix" class="form-control" id="telephone_fix" placeholder="Entrez votre téléphone fixe" value="{{ old('telephone_fix', $data['telephone_fix'] ?? '') }}">
                        @error('telephone_fix') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="Entrez votre email" value="{{ old('email', $data['email'] ?? '') }}" required>
                        <div id="email-validation-status" class="mt-2" style="display: none;">
                            <div id="email-loading" class="text-info">
                                <i class="fas fa-spinner fa-spin"></i> Vérification de l'email...
                            </div>
                            <div id="email-valid" class="text-success" style="display: none;">
                                <i class="fas fa-check-circle"></i> Email valide
                            </div>
                            <div id="email-invalid" class="text-danger" style="display: none;">
                                <i class="fas fa-times-circle"></i> Email invalide ou inexistant
                            </div>
                        </div>
                        @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="ville">Ville actuelle</label>
                        <input type="text" name="ville" class="form-control" id="ville" placeholder="Entrez votre ville actuelle" value="{{ old('ville', $data['ville'] ?? '') }}" required>
                        @error('ville') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="pays">Pays actuel</label>
                        <input type="text" name="pays" class="form-control" id="pays" placeholder="Entrez votre pays actuel" value="{{ old('pays', $data['pays'] ?? '') }}" required>
                        @error('pays') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="adresse">Adresse</label>
                        <input type="text" name="adresse" class="form-control" id="adresse" placeholder="Entrez votre adresse" value="{{ old('adresse', $data['adresse'] ?? '') }}" required>
                        @error('adresse') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <br><br>
                    <hr>
                    <h2 class="section-title">Pièces jointes</h2>

                    <div class="col-md-12 mb-3">
                        <label for="CV">Curriculum Vitae (CV) (pdf,png)</label>
                        <input type="file" name="CV" class="form-control" id="CV" accept=".pdf,.doc,.docx" required>
                        @error('CV') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="demande">Demande de candidature (pdf,png)</label>
                        <input type="file" name="demande" class="form-control" id="demande" accept=".pdf,.doc,.docx" required>
                        @error('demande') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="scan_cartid">Carte d'identité national / passeport (pdf,png)</label>
                        <input type="file" name="scan_cartid" class="form-control" id="scan_cartid" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,." required>
                        @error('scan_cartid') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="photo">Photo d'identité (png)</label>
                        <input type="file" name="photo" class="form-control" id="photo" accept=".jpg,.jpeg,.png" required>
                        @error('photo') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mt-3 text-center">
                    <button type="submit" class="btn btn-primary">Suivant</button>
                </div>
            @endif

            <!-- Step 2: Bac -->
            @if ($step == 2)
                <hr>
                <h2 class="section-title">Informations sur le Baccalauréat</h2>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="serie_bac">Série du baccalauréat</label>
                        <input type="text" name="serie_bac" class="form-control" id="serie_bac" placeholder="Ex: Sciences Physiques" value="{{ old('serie_bac', $data['serie_bac'] ?? '') }}" required>
                        @error('serie_bac') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="annee_bac">Année d'obtention du baccalauréat</label>
                        <select name="annee_bac" class="form-control" id="annee_bac" required>
                            <option value="">Veuillez sélectionner</option>
                            @for ($i = now()->year; $i >= 2000; $i--)
                                <option value="{{ ($i-1) . '/' . $i }}" {{ old('annee_bac', $data['annee_bac'] ?? '') == ($i-1) . '/' . $i ? 'selected' : '' }}>
                                    {{ ($i-1) . '/' . $i }}
                                </option>
                            @endfor
                        </select>
                        @error('annee_bac') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="scan_bac">Scan du baccalauréat (pdf,png)</label>
                        <input type="file" name="scan_bac" class="form-control" id="scan_bac" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,." required>
                        @error('scan_bac') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mt-3 text-center">
                    <a href="{{ route('candidat.form', ['step' => $step - 1]) }}" class="btn btn-secondary mr-2">Précédent</a>
                    <button type="submit" class="btn btn-primary">Suivant</button>
                </div>
            @endif

            <!-- Step 3: Diplômes -->
          @if ($step == 3)
    
        <hr>
        <h2 class="section-title">Diplômes</h2>
        <div class="row">
            <!-- Diplôme Bac+2 -->
            <div class="col-md-12 mb-3">
                <h3>Diplôme Bac+2</h3>
            </div>
            <div class="col-md-12 mb-3">
                <label for="diplomes_0_type_diplome_bac_2">Type du diplôme BAC+2 (Ex: DTS, DUT, BTS, DEUG, DEUST, DEUP, ...)</label>
                <input type="text" name="diplomes[0][type_diplome_bac_2]" class="form-control" id="diplomes_0_type_diplome_bac_2" value="{{ old('diplomes.0.type_diplome_bac_2', $data['diplomes'][0]['type_diplome_bac_2'] ?? '') }}" required>
                @error('diplomes.0.type_diplome_bac_2') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-12 mb-3">
                <label for="diplomes_0_annee_diplome_bac_2">Année d'obtention (BAC+2)</label>
                <select name="diplomes[0][annee_diplome_bac_2]" class="form-control" id="diplomes_0_annee_diplome_bac_2" required>
                    <option value="">Veuillez sélectionner</option>
                    @for ($i = now()->year; $i >= 2000; $i--)
                        <option value="{{ ($i-1) . '/' . $i }}" {{ old('diplomes.0.annee_diplome_bac_2', $data['diplomes'][0]['annee_diplome_bac_2'] ?? '') == ($i-1) . '/' . $i ? 'selected' : '' }}>
                            {{ ($i-1) . '/' . $i }}
                        </option>
                    @endfor
                </select>
                @error('diplomes.0.annee_diplome_bac_2') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-12 mb-3">
                <label for="diplomes_0_filiere_diplome_bac_2">Intitulé de la filière (BAC+2)</label>
                <input type="text" name="diplomes[0][filiere_diplome_bac_2]" class="form-control" id="diplomes_0_filiere_diplome_bac_2" value="{{ old('diplomes.0.filiere_diplome_bac_2', $data['diplomes'][0]['filiere_diplome_bac_2'] ?? '') }}" required>
                @error('diplomes.0.filiere_diplome_bac_2') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-12 mb-3">
                <label for="diplomes_0_etablissement_bac_2">Nom de l'établissement (BAC+2)</label>
                <input type="text" name="diplomes[0][etablissement_bac_2]" class="form-control" id="diplomes_0_etablissement_bac_2" value="{{ old('diplomes.0.etablissement_bac_2', $data['diplomes'][0]['etablissement_bac_2'] ?? '') }}" required>
                @error('diplomes.0.etablissement_bac_2') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-12 mb-3">
                <label for="diplomes_0_scan_bac_2">Scan du diplôme (PDF, JPG, PNG)</label>
                <input type="file" name="diplomes[0][scan_bac_2]" class="form-control" id="diplomes_0_scan_bac_2" accept=".pdf,.jpg,.jpeg,.png" {{ session('candidat_data.diplomes.0.scan_bac_2_path') ? '' : 'required' }}>
                @if (session('candidat_data.diplomes.0.scan_bac_2_path'))
                    <small class="text-success">Fichier téléversé : {{ basename(session('candidat_data.diplomes.0.scan_bac_2_path')) }}</small>
                @endif
                @error('diplomes.0.scan_bac_2') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <!-- Diplôme Bac+3 -->
            <div class="col-md-12 mb-3">
                <h3>Diplôme Bac+3</h3>
            </div>
            <div class="col-md-12 mb-3">
                <label for="diplomes_1_type_diplome_bac_3">Type du diplôme BAC+3 (Ex: LF, LP, ...)</label>
                <input type="text" name="diplomes[1][type_diplome_bac_3]" class="form-control" id="diplomes_1_type_diplome_bac_3" value="{{ old('diplomes.1.type_diplome_bac_3', $data['diplomes'][1]['type_diplome_bac_3'] ?? '') }}">
                @error('diplomes.1.type_diplome_bac_3') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-12 mb-3">
                <label for="diplomes_1_annee_diplome_bac_3">Année d'obtention (BAC+3)</label>
                <select name="diplomes[1][annee_diplome_bac_3]" class="form-control" id="diplomes_1_annee_diplome_bac_3">
                    <option value="">Veuillez sélectionner</option>
                    @for ($i = now()->year; $i >= 2000; $i--)
                        <option value="{{ ($i-1) . '/' . $i }}" {{ old('diplomes.1.annee_diplome_bac_3', $data['diplomes'][1]['annee_diplome_bac_3'] ?? '') == ($i-1) . '/' . $i ? 'selected' : '' }}>
                            {{ ($i-1) . '/' . $i }}
                        </option>
                    @endfor
                </select>
                @error('diplomes.1.annee_diplome_bac_3') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-12 mb-3">
                <label for="diplomes_1_filiere_diplome_bac_3">Intitulé de la filière (BAC+3)</label>
                <input type="text" name="diplomes[1][filiere_diplome_bac_3]" class="form-control" id="diplomes_1_filiere_diplome_bac_3" value="{{ old('diplomes.1.filiere_diplome_bac_3', $data['diplomes'][1]['filiere_diplome_bac_3'] ?? '') }}">
                @error('diplomes.1.filiere_diplome_bac_3') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-12 mb-3">
                <label for="diplomes_1_etablissement_bac_3">Nom de l'établissement (BAC+3)</label>
                <input type="text" name="diplomes[1][etablissement_bac_3]" class="form-control" id="diplomes_1_etablissement_bac_3" value="{{ old('diplomes.1.etablissement_bac_3', $data['diplomes'][1]['etablissement_bac_3'] ?? '') }}">
                @error('diplomes.1.etablissement_bac_3') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-12 mb-3">
                <label for="diplomes_1_scan_bac_3">Scan du diplôme (PDF, JPG, PNG)</label>
                <input type="file" name="diplomes[1][scan_bac_3]" class="form-control" id="diplomes_1_scan_bac_3" accept=".pdf,.jpg,.jpeg,.png">
                @if (session('candidat_data.diplomes.1.scan_bac_3_path'))
                    <small class="text-success">Fichier téléversé : {{ basename(session('candidat_data.diplomes.1.scan_bac_3_path')) }}</small>
                @endif
                @error('diplomes.1.scan_bac_3') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="mt-3 text-center">
            <a href="{{ route('candidat.form', ['step' => $step - 1]) }}" class="btn btn-secondary mr-2">Précédent</a>
            <button type="submit" class="btn btn-primary">Suivant</button>
        </div>
    </form>
@endif

            <!-- Step 4: Stages -->
            @if ($step == 4)
                <hr>
                <h2 class="section-title">Stages</h2>
                <div id="stages">
                    @foreach ($data['stages'] ?? [] as $index => $stage)
                        <div class="stage-form mb-4 p-3 border rounded">
                            <div clas="row">
                                <div class="col-md-12 mb-3">
                                    <label for="stages_{{ $index }}_fonction">Fonction</label>
                                    <input type="text" name="stages[{{ $index }}][fonction]" class="form-control" id="stages_{{ $index }}_fonction" placeholder="Fonction" value="{{ old('stages.' . $index . '.fonction', $stage['fonction'] ?? '') }}">
                                    @error('stages.' . $index . '.fonction') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="stages_{{ $index }}_secteur_activite">Secteur d'activité</label>
                                    <input type="text" name="stages[{{ $index }}][secteur_activite]" class="form-control" id="stages_{{ $index }}_secteur_activite" placeholder="Secteur d'activité" value="{{ old('stages.' . $index . '.secteur_activite', $stage['secteur_activite'] ?? '') }}">
                                    @error('stages.' . $index . '.secteur_activite') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="stages_{{ $index }}_etablissement">Nom de l'établissement </label>
                                    <input type="text" name="stages[{{ $index }}][etablissement]" class="form-control" id="stages_{{ $index }}_etablissement" placeholder="Établissement" value="{{ old('stages.' . $index . '.etablissement', $stage['etablissement'] ?? '') }}">
                                    @error('stages.' . $index . '.etablissement') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="stages_{{ $index }}_periode">Période</label>
                                    <input type="text" name="stages[{{ $index }}][periode]" class="form-control" id="stages_{{ $index }}_periode" placeholder="Période" value="{{ old('stages.' . $index . '.periode', $stage['periode'] ?? '') }}">
                                    @error('stages.' . $index . '.periode') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="stages_{{ $index }}_attestation">Attestation de Stage (pdf,png)</label>
                                    <input type="file" name="stages[{{ $index }}][attestation]" class="form-control" id="stages_{{ $index }}_attestation" accept=".pdf,.doc,.docx">
                                    @error('stages.' . $index . '.attestation') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                
                                
                                <div class="col-md-12 mb-3">
                                    <label for="stages_{{ $index }}_description">Description</label>
                                    <textarea name="stages[{{ $index }}][description]" class="form-control" id="stages_{{ $index }}_description" placeholder="Description">{{ old('stages.' . $index . '.description', $stage['description'] ?? '') }}</textarea>
                                    @error('stages.' . $index . '.description') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <button type="button" class="btn btn-danger btn-sm" onclick="removeEntry(this, 'stages')">Supprimer</button>
                        </div>
                    @endforeach
                </div>
                <button type="button" id="add-stage" onclick="addStage()" class="btn btn-secondary mb-3">Ajouter un stage</button>
                <div class="mt-3 text-center">
                    <a href="{{ route('candidat.form', ['step' => $step - 1]) }}" class="btn btn-secondary mr-2">Précédent</a>
                    <button type="submit" class="btn btn-primary">Suivant</button>
                </div>
            @endif

            <!-- Step 5: Attestations -->
            @if ($step == 5)
                <hr>
                <h2 class="section-title">Attestations </h2>
                <div id="attestations">
                    @foreach ($data['attestations'] ?? [] as $index => $attestation)
                        <div class="attestation-form mb-4 p-3 border rounded">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="attestations_{{ $index }}_type_attestation">Type d'attestation</label>
                                    <input type="text" name="attestations[{{ $index }}][type_attestation]" class="form-control" id="attestations_{{ $index }}_type_attestation" placeholder="Type d'attestation" value="{{ old('attestations.' . $index . '.type_attestation', $attestation['type_attestation'] ?? '') }}">
                                    @error('attestations.' . $index . '.type_attestation') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="attestations_{{ $index }}_attestation">Attestation</label>
                                    <input type="file" name="attestations[{{ $index }}][attestation]" class="form-control" id="attestations_{{ $index }}_attestation" accept=".pdf,.doc,.docx">
                                    @error('attestations.' . $index . '.attestation') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="col-md-12 mb-3">
                                    <label for="attestations_{{ $index }}_description">Description</label>
                                    <textarea name="attestations[{{ $index }}][description]" class="form-control" id="attestations_{{ $index }}_description" placeholder="Description">{{ old('attestations.' . $index . '.description', $attestation['description'] ?? '') }}</textarea>
                                    @error('attestations.' . $index . '.description') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <button type="button" class="btn btn-danger btn-sm" onclick="removeEntry(this, 'attestations')">Supprimer</button>
                        </div>
                    @endforeach
                </div>
                <button type="button" id="add-attestation" onclick="addAttestation()" class="btn btn-secondary mb-3">Ajouter une attestation</button>
                <div class="mt-3 text-center">
                    <a href="{{ route('candidat.form', ['step' => $step - 1]) }}" class="btn btn-secondary mr-2">Précédent</a>
                    <button type="submit" class="btn btn-primary">Suivant</button>
                </div>
            @endif

            <!-- Step 6: Expériences -->
            @if ($step == 6)
                <hr>
                <h2 class="section-title">Expériences professionnelles</h2>
                <div id="experiences">
                    @foreach ($data['experiences'] ?? [] as $index => $experience)
                        <div class="experience-form mb-4 p-3 border rounded">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="experiences_{{ $index }}_fonction">Fonction</label>
                                    <input type="text" name="experiences[{{ $index }}][fonction]" class="form-control" id="experiences_{{ $index }}_fonction" placeholder="Fonction" value="{{ old('experiences.' . $index . '.fonction', $experience['fonction'] ?? '') }}">
                                    @error('experiences.' . $index . '.fonction') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="experiences_{{ $index }}_secteur_activite">Secteur d'activité</label>
                                    <input type="text" name="experiences[{{ $index }}][secteur_activite]" class="form-control" id="experiences_{{ $index }}_secteur_activite" placeholder="Secteur d'activité" value="{{ old('experiences.' . $index . '.secteur_activite', $experience['secteur_activite'] ?? '') }}">
                                    @error('experiences.' . $index . '.secteur_activite') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="experiences_{{ $index }}_periode">Période</label>
                                    <input type="text" name="experiences[{{ $index }}][periode]" class="form-control" id="experiences_{{ $index }}_periode" placeholder="Période" value="{{ old('experiences.' . $index . '.periode', $experience['periode'] ?? '') }}">
                                    @error('experiences.' . $index . '.periode') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="experiences_{{ $index }}_etablissement">Nom de l'établissement </label>
                                    <input type="text" name="experiences[{{ $index }}][etablissement]" class="form-control" id="experiences_{{ $index }}_etablissement" placeholder="Établissement" value="{{ old('experiences.' . $index . '.etablissement', $experience['etablissement'] ?? '') }}">
                                    @error('experiences.' . $index . '.etablissement') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="experiences_{{ $index }}_attestation">Attestation de travail actuel (pdf,png)</label>
                                    <input type="file" name="experiences[{{ $index }}][attestation]" class="form-control" id="experiences_{{ $index }}_attestation" accept=".jpg,.jpeg,.png,.pdf">
                                    @error('experiences.' . $index . '.attestation') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                
                                
                                <div class="col-md-12 mb-3">
                                    <label for="experiences_{{ $index }}_description">Description</label>
                                    <textarea name="experiences[{{ $index }}][description]" class="form-control" id="experiences_{{ $index }}_description" placeholder="Description">{{ old('experiences.' . $index . '.description', $experience['description'] ?? '') }}</textarea>
                                    @error('experiences.' . $index . '.description') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <button type="button" class="btn btn-danger btn-sm" onclick="removeEntry(this, 'experiences')">Supprimer</button>
                        </div>
                    @endforeach
                </div>
                <button type="button" id="add-experience" onclick="addExperience()" class="btn btn-secondary mb-3">Ajouter une expérience</button>
                <div class="mt-3 text-center">
                    <a href="{{ route('candidat.form', ['step' => $step - 1]) }}" class="btn btn-secondary mr-2">Précédent</a>
                    <button type="submit" class="btn btn-success">Valider</button>
                </div>
            @endif
        </form>
    </div>
</div>
<style>
    body {
        background-color: #f4f7fa;
        color: #333;
        font-family: 'Arial', sans-serif;
    }

    .inscription-form {
        max-width: 900px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .form-section {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .section-title {
        color: #004aad;
        margin-bottom: 1.5rem;
        text-align: center;
        font-size: 1.8rem;
    }

    .form-group label {
        font-weight: bold;
        color: #333;
    }

    .form-control {
        border-radius: 5px;
        transition: border-color 0.3s;
    }

    .form-control:focus {
        border-color: #004aad;
        box-shadow: 0 0 5px rgba(0, 74, 173, 0.2);
    }

    .btn-primary {
        background: #004aad;
        border: none;
        border-radius: 5px;
        padding: 0.75rem 1.5rem;
        font-size: 1.1rem;
        transition: background 0.3s;
    }

    .btn-primary:hover {
        background: #003780;
    }

    .btn-secondary {
        background: #6c757d;
        border-radius: 5px;
    }

    .btn-danger {
        border-radius: 5px;
    }

    .btn-success {
        border-radius: 5px;
    }

    .alert {
        border-radius: 5px;
        margin-bottom: 1.5rem;
    }

    .form-group[dir="rtl"] input {
        text-align: right;
    }

    hr {
        border-top: 1px solid #ccc;
        margin: 2rem 0;
    }

    @media (max-width: 768px) {
        .section-title {
            font-size: 1.5rem;
        }

        .form-section {
            padding: 1.5rem;
        }
    }
</style>
<script>
    let stageCount = {{ count($data['stages'] ?? []) }};
    let attestationCount = {{ count($data['attestations'] ?? []) }};
    let experienceCount = {{ count($data['experiences'] ?? []) }};

    // Filtrage dynamique des titres de formation en fonction du type de formation
    document.getElementById('type_formation').addEventListener('change', function() {
        const selectedType = this.value;
        const titreSelect = document.getElementById('titre_id');
        const options = titreSelect.querySelectorAll('option[data-type]');

        options.forEach(option => {
            if (option.getAttribute('data-type') === selectedType || option.value === '') {
                option.style.display = '';
            } else {
                option.style.display = 'none';
            }
        });

        // Réinitialiser la sélection si l'option actuelle n'est pas compatible
        const selectedOption = titreSelect.querySelector(`option[value="${titreSelect.value}"]`);
        if (selectedOption && selectedOption.style.display === 'none') {
            titreSelect.value = '';
        }
    });

    // Déclencher le filtrage au chargement de la page pour gérer les données pré-remplies
    window.addEventListener('load', function() {
        const typeFormation = document.getElementById('type_formation').value;
        if (typeFormation) {
            const event = new Event('change');
            document.getElementById('type_formation').dispatchEvent(event);
        }
    });

    function addStage() {
        if (stageCount >= 3) {
            alert('Vous ne pouvez ajouter que 3 stages maximum.');
            return;
        }
        const stageDiv = document.createElement('div');
        stageDiv.className = 'stage-form mb-4 p-3 border rounded';
        stageDiv.innerHTML = `
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="stages_${stageCount}_fonction">Fonction</label>
                    <input type="text" name="stages[${stageCount}][fonction]" class="form-control" id="stages_${stageCount}_fonction" placeholder="Fonction">
                </div>
                <div class="col-md-12 mb-3">
                    <label for="stages_${stageCount}_periode">Période</label>
                    <input type="text" name="stages[${stageCount}][periode]" class="form-control" id="stages_${stageCount}_periode" placeholder="Période">
                </div>
                <div class="col-md-12 mb-3">
                    <label for="stages_${stageCount}_attestation">Attestation</label>
                    <input type="file" name="stages[${stageCount}][attestation]" class="form-control" id="stages_${stageCount}_attestation" accept=".pdf,.doc,.docx">
                </div>
                <div class="col-md-12 mb-3">
                    <label for="stages_${stageCount}_etablissement">Établissement</label>
                    <input type="text" name="stages[${stageCount}][etablissement]" class="form-control" id="stages_${stageCount}_etablissement" placeholder="Établissement">
                </div>
                <div class="col-md-12 mb-3">
                    <label for="stages_${stageCount}_secteur_activite">Secteur d'activité</label>
                    <input type="text" name="stages[${stageCount}][secteur_activite]" class="form-control" id="stages_${stageCount}_secteur_activite" placeholder="Secteur d'activité">
                </div>
                <div class="col-md-12 mb-3">
                    <label for="stages_${stageCount}_description">Description</label>
                    <textarea name="stages[${stageCount}][description]" class="form-control" id="stages_${stageCount}_description" placeholder="Description"></textarea>
                </div>
            </div>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeEntry(this, 'stages')">Supprimer</button>
        `;
        document.getElementById('stages').appendChild(stageDiv);
        stageCount++;
        updateAddButtonState('stage', stageCount);
    }

    function addAttestation() {
        if (attestationCount >= 3) {
            alert('Vous ne pouvez ajouter que 3 attestations maximum.');
            return;
        }
        const attestationDiv = document.createElement('div');
        attestationDiv.className = 'attestation-form mb-4 p-3 border rounded';
        attestationDiv.innerHTML = `
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="attestations_${attestationCount}_attestation">Attestation</label>
                    <input type="file" name="attestations[${attestationCount}][attestation]" class="form-control" id="attestations_${attestationCount}_attestation" accept=".pdf,.doc,.docx">
                </div>
                <div class="col-md-12 mb-3">
                    <label for="attestations_${attestationCount}_type_attestation">Type d'attestation</label>
                    <input type="text" name="attestations[${attestationCount}][type_attestation]" class="form-control" id="attestations_${attestationCount}_type_attestation" placeholder="Type d'attestation">
                </div>
                <div class="col-md-12 mb-3">
                    <label for="attestations_${attestationCount}_description">Description</label>
                    <textarea name="attestations[${attestationCount}][description]" class="form-control" id="attestations_${attestationCount}_description" placeholder="Description"></textarea>
                </div>
            </div>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeEntry(this, 'attestations')">Supprimer</button>
        `;
        document.getElementById('attestations').appendChild(attestationDiv);
        attestationCount++;
        updateAddButtonState('attestation', attestationCount);
    }

    function addExperience() {
        if (experienceCount >= 3) {
            alert('Vous ne pouvez ajouter que 3 expériences maximum.');
            return;
        }
        const experienceDiv = document.createElement('div');
        experienceDiv.className = 'experience-form mb-4 p-3 border rounded';
        experienceDiv.innerHTML = `
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="experiences_${experienceCount}_fonction">Fonction</label>
                    <input type="text" name="experiences[${experienceCount}][fonction]" class="form-control" id="experiences_${experienceCount}_fonction" placeholder="Fonction">
                </div>
                <div class="col-md-12 mb-3">
                    <label for="experiences_${experienceCount}_periode">Période</label>
                    <input type="text" name="experiences[${experienceCount}][periode]" class="form-control" id="experiences_${experienceCount}_periode" placeholder="Période">
                </div>
                <div class="col-md-12 mb-3">
                    <label for="experiences_${experienceCount}_attestation">Attestation</label>
                    <input type="file" name="experiences[${experienceCount}][attestation]" class="form-control" id="experiences_${experienceCount}_attestation" accept=".jpg,.jpeg,.png,.pdf">
                </div>
                <div class="col-md-12 mb-3">
                    <label for="experiences_${experienceCount}_etablissement">Établissement</label>
                    <input type="text" name="experiences[${experienceCount}][etablissement]" class="form-control" id="experiences_${experienceCount}_etablissement" placeholder="Établissement">
                </div>
                <div class="col-md-12 mb-3">
                    <label for="experiences_${experienceCount}_secteur_activite">Secteur d'activité</label>
                    <input type="text" name="experiences[${experienceCount}][secteur_activite]" class="form-control" id="experiences_${experienceCount}_secteur_activite" placeholder="Secteur d'activité">
                </div>
                <div class="col-md-12 mb-3">
                    <label for="experiences_${experienceCount}_description">Description</label>
                    <textarea name="experiences[${experienceCount}][description]" class="form-control" id="experiences_${experienceCount}_description" placeholder="Description"></textarea>
                </div>
            </div>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeEntry(this, 'experiences')">Supprimer</button>
        `;
        document.getElementById('experiences').appendChild(experienceDiv);
        experienceCount++;
        updateAddButtonState('experience', experienceCount);
    }

    function removeEntry(element, type) {
        element.parentElement.remove();
        if (type === 'stages') stageCount--;
        else if (type === 'attestations') attestationCount--;
        else if (type === 'experiences') experienceCount--;
        updateAddButtonState(type, window[type + 'Count']);
    }

    function updateAddButtonState(type, count) {
        const button = document.getElementById(`add-${type}`);
        if (count >= 3) {
            button.disabled = true;
            button.style.opacity = '0.5';
            button.style.cursor = 'not-allowed';
        } else {
            button.disabled = false;
            button.style.opacity = '1';
            button.style.cursor = 'pointer';
        }
    }

    window.onload = function() {
        updateAddButtonState('stage', stageCount);
        updateAddButtonState('attestation', attestationCount);
        updateAddButtonState('experience', experienceCount);
        
        // Initialize email validation
        initializeEmailValidation();
    };

    // Email validation functionality
    function initializeEmailValidation() {
        const emailInput = document.getElementById('email');
        
        if (emailInput) {
            // Check initial value
            const initialEmail = emailInput.value.trim();
            if (initialEmail !== '') {
                validateEmail(initialEmail);
            }
            
            emailInput.addEventListener('input', function() {
                const email = this.value.trim();
                
                // Hide validation status initially
                hideEmailValidationStatus();
                
                if (email === '') {
                    updateSubmitButtonState(true); // Allow empty email (backend will validate)
                    return;
                }
                
                // Validate email immediately
                validateEmail(email);
            });
        }
    }

    function isValidEmailFormat(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function validateEmail(email) {
        // Check email format first
        if (!isValidEmailFormat(email)) {
            showEmailValidationStatus('invalid', 'Format d\'email invalide');
            updateSubmitButtonState(false);
            return;
        }
        
        // Extract domain from email
        const domain = email.split('@')[1];
        
        // List of common disposable email domains
        const disposableDomains = [
            '10minutemail.com', 'tempmail.org', 'guerrillamail.com', 'mailinator.com',
            'yopmail.com', 'throwaway.email', 'temp-mail.org', 'fakeinbox.com',
            'sharklasers.com', 'grr.la', 'guerrillamailblock.com', 'pokemail.net',
            'spam4.me', 'bccto.me', 'chacuo.net', 'dispostable.com', 'mailnesia.com',
            'maildrop.cc', 'mailmetrash.com', 'trashmail.com', 'getairmail.com',
            'mailcatch.com', 'inboxalias.com', 'mailnull.com', 'getnada.com',
            'mailinator.net', 'tempr.email', 'tmpeml.com', 'tmpmail.org',
            'test.com', 'example.com', 'test.org', 'example.org'
        ];
        
        // Check if it's a disposable email
        if (disposableDomains.includes(domain.toLowerCase())) {
            showEmailValidationStatus('invalid', 'Les emails temporaires ne sont pas acceptés');
            updateSubmitButtonState(false);
            return;
        }
        
        // If format is valid and not disposable, accept
        showEmailValidationStatus('valid', 'Email valide');
        updateSubmitButtonState(true);
    }

    function showEmailValidationStatus(status, message = '') {
        const statusDiv = document.getElementById('email-validation-status');
        const loadingDiv = document.getElementById('email-loading');
        const validDiv = document.getElementById('email-valid');
        const invalidDiv = document.getElementById('email-invalid');
        
        if (statusDiv) {
            statusDiv.style.display = 'block';
            if (loadingDiv) loadingDiv.style.display = 'none';
            if (validDiv) validDiv.style.display = 'none';
            if (invalidDiv) invalidDiv.style.display = 'none';
            
            switch (status) {
                case 'loading':
                    if (loadingDiv) loadingDiv.style.display = 'block';
                    break;
                case 'valid':
                    if (validDiv) {
                        validDiv.style.display = 'block';
                        if (message) {
                            validDiv.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
                        }
                    }
                    break;
                case 'invalid':
                    if (invalidDiv) {
                        invalidDiv.style.display = 'block';
                        if (message) {
                            invalidDiv.innerHTML = `<i class="fas fa-times-circle"></i> ${message}`;
                        }
                    }
                    break;
            }
        }
    }

    function hideEmailValidationStatus() {
        const statusDiv = document.getElementById('email-validation-status');
        if (statusDiv) {
            statusDiv.style.display = 'none';
        }
    }

    function updateSubmitButtonState(isValid) {
        const submitButton = document.querySelector('button[type="submit"]');
        if (submitButton) {
            if (isValid) {
                submitButton.disabled = false;
                submitButton.style.opacity = '1';
                submitButton.style.cursor = 'pointer';
            } else {
                submitButton.disabled = true;
                submitButton.style.opacity = '0.5';
                submitButton.style.cursor = 'not-allowed';
            }
        }
    }

    // Override form submission to check email validity
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('candidat-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const emailInput = document.getElementById('email');
                const email = emailInput ? emailInput.value.trim() : '';
                
                // If email field exists and has a value, check validation
                if (emailInput && email !== '') {
                    // Check format
                    if (!isValidEmailFormat(email)) {
                        e.preventDefault();
                        alert('Veuillez entrer un email valide avant de continuer.');
                        emailInput.focus();
                        return false;
                    }
                    
                    // Check disposable domains
                    const domain = email.split('@')[1];
                    const disposableDomains = [
                        '10minutemail.com', 'tempmail.org', 'guerrillamail.com', 'mailinator.com',
                        'yopmail.com', 'throwaway.email', 'temp-mail.org', 'fakeinbox.com',
                        'sharklasers.com', 'grr.la', 'guerrillamailblock.com', 'pokemail.net',
                        'spam4.me', 'bccto.me', 'chacuo.net', 'dispostable.com', 'mailnesia.com',
                        'maildrop.cc', 'mailmetrash.com', 'trashmail.com', 'getairmail.com',
                        'mailcatch.com', 'inboxalias.com', 'mailnull.com', 'getnada.com',
                        'mailinator.net', 'tempr.email', 'tmpeml.com', 'tmpmail.org',
                        'test.com', 'example.com', 'test.org', 'example.org'
                    ];
                    
                    if (disposableDomains.includes(domain.toLowerCase())) {
                        e.preventDefault();
                        alert('Les emails temporaires ne sont pas acceptés.');
                        emailInput.focus();
                        return false;
                    }
                }
                // Allow submission for valid emails or empty email (backend will handle validation)
            });
        }
    });
</script>


@endsection