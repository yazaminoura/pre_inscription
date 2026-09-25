@extends('candidateur.layout.index')
@section('title', $etapes[$step])

@php
    $annees = collect(range(now()->year, 1990))->mapWithKeys(fn ($a) => [$a => $a])->all();
    $d = $data;
    // Fichier déjà envoyé : on l'indique et le champ devient facultatif
    $dejaEnvoye = fn ($cle) => !empty($d[$cle]);
    $titres = [
        1 => ['Quelle formation ?', 'Choisissez la formation à laquelle vous souhaitez vous préinscrire.'],
        2 => ['Identité', 'Tels qu\'ils figurent sur votre pièce d\'identité.'],
        3 => ['Coordonnées', 'Pour vous contacter au sujet de votre dossier.'],
        4 => ['Parcours académique', 'Votre baccalauréat et vos diplômes après le bac.'],
        5 => ['Expérience', 'Stages, expériences professionnelles et attestations. Tout est facultatif.'],
        6 => ['Documents & envoi', 'Joignez vos pièces, vérifiez le récapitulatif puis envoyez.'],
    ];
    $listes = [
        'stages' => ['Stages', 'Ajouter un stage', 'stages'],
        'experiences' => ['Expériences professionnelles', 'Ajouter une expérience', 'experiences'],
        'attestations' => ['Attestations (langues, certifications, bénévolat…)', 'Ajouter une attestation', 'attestations'],
    ];
@endphp

@section('content')
<div class="form-shell">

    <ol class="stepper">
        @foreach ($etapes as $n => $etape)
            @php $etat = $n < $step ? 'done' : ($n === $step ? 'active' : ''); $accessible = $n <= ($d['_etape'] ?? 1); @endphp
            <li class="{{ $etat }}">
                @if ($accessible && $n !== $step)
                    <a href="{{ route('candidat.form', ['step' => $n]) }}">
                @else
                    <span class="stepper-inner">
                @endif
                    <span class="stepper-dot">@if ($n < $step)<span class="material-symbols-rounded">check</span>@else{{ $n }}@endif</span>
                    <span class="stepper-label d-block">{{ $etape }}</span>
                @if ($accessible && $n !== $step)</a>@else</span>@endif
            </li>
        @endforeach
    </ol>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger d-flex gap-2 align-items-start">
            <span class="material-symbols-rounded">error</span>
            <div>Merci de corriger {{ $errors->count() > 1 ? 'les ' . $errors->count() . ' champs signalés' : 'le champ signalé' }} ci-dessous.</div>
        </div>
    @endif

    @if ($step > 1 && $formationChoisie)
        <div class="d-flex align-items-center gap-2 mb-3 small text-muted">
            <span class="material-symbols-rounded" style="color: var(--brand);">school</span>
            Préinscription : <strong class="text-body">{{ $formationChoisie->type_formation }} · {{ $formationChoisie->titre }}</strong>
            <a href="{{ route('candidat.form', ['step' => 1]) }}" class="ms-1">modifier</a>
        </div>
    @endif

    <form action="{{ route('candidat.submit') }}" method="POST" enctype="multipart/form-data" class="form-card">
        @csrf
        <input type="hidden" name="step" value="{{ $step }}">

        <div class="form-card-head">
            <h2>{{ $titres[$step][0] }}</h2>
            <p>{{ $titres[$step][1] }}</p>
        </div>

        <div class="form-card-body">

        {{-- 1. Formation --}}
        @if ($step === 1)
            @forelse ($formations->groupBy('type_formation') as $type => $liste)
                <div class="fieldset-title">{{ $type }}</div>
                <div class="choice-grid mb-2">
                    @foreach ($liste as $f)
                        <label class="choice">
                            <input type="radio" name="titre_id" value="{{ $f->id }}" class="form-check-input" @checked((int) old('titre_id', $d['titre_id'] ?? 0) === $f->id) required>
                            <span class="choice-title">{{ $f->titre }}</span>
                            <span class="choice-sub">Clôture le {{ \Carbon\Carbon::parse($f->date_fin)->format('d/m/Y') }}</span>
                        </label>
                    @endforeach
                </div>
            @empty
                <div class="empty-state"><span class="material-symbols-rounded">event_busy</span>Aucune formation n'est ouverte pour le moment.</div>
            @endforelse
            @error('titre_id')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
        @endif

        {{-- 2. Identité --}}
        @if ($step === 2)
            <div class="row g-3">
                <x-champ name="nom" label="Nom" :value="$d['nom'] ?? ''" required autocomplete="family-name" />
                <x-champ name="prenom" label="Prénom" :value="$d['prenom'] ?? ''" required autocomplete="given-name" />
                <x-champ name="nom_ar" label="الاسم العائلي" :value="$d['nom_ar'] ?? ''" dir="rtl" aide="بالعربية (اختياري)" />
                <x-champ name="prenom_ar" label="الاسم الشخصي" :value="$d['prenom_ar'] ?? ''" dir="rtl" aide="بالعربية (اختياري)" />
                <x-champ name="CNE" label="CNE / Code Massar" :value="$d['CNE'] ?? ''" required />
                <x-champ name="CIN" label="CIN ou n° de passeport" :value="$d['CIN'] ?? ''" required />
                <x-champ name="date_naissance" label="Date de naissance" type="date" :value="$d['date_naissance'] ?? ''" required />
                <x-champ name="sex" label="Sexe" :value="$d['sex'] ?? ''" :options="['Homme' => 'Homme', 'Femme' => 'Femme']" required />
                <x-champ name="ville_naissance" label="Ville de naissance" :value="$d['ville_naissance'] ?? ''" required />
                <x-champ name="pay_naissance" label="Pays de naissance" :value="$d['pay_naissance'] ?? 'Maroc'" required />
                <x-champ name="nationalite" label="Nationalité" :value="$d['nationalite'] ?? 'Marocaine'" required />
                <x-champ name="ville_naissance_ar" label="مدينة الازدياد" :value="$d['ville_naissance_ar'] ?? ''" dir="rtl" aide="بالعربية (اختياري)" />
            </div>
        @endif

        {{-- 3. Coordonnées --}}
        @if ($step === 3)
            <div class="row g-3">
                <x-champ name="email" label="Email" type="email" :value="$d['email'] ?? ''" required autocomplete="email" aide="Votre référence et les nouvelles de votre dossier arriveront ici." />
                <x-champ name="telephone_mob" label="Téléphone mobile" type="tel" :value="$d['telephone_mob'] ?? ''" required placeholder="+212 6 12 34 56 78" autocomplete="tel" />
                <x-champ name="adresse" label="Adresse" :value="$d['adresse'] ?? ''" required col="col-12" autocomplete="street-address" />
                <x-champ name="ville" label="Ville" :value="$d['ville'] ?? ''" required />
                <x-champ name="province" label="Province / région" :value="$d['province'] ?? ''" required />
                <x-champ name="pays" label="Pays de résidence" :value="$d['pays'] ?? 'Maroc'" required />
                <x-champ name="telephone_fix" label="Téléphone fixe" type="tel" :value="$d['telephone_fix'] ?? ''" aide="Facultatif" />
            </div>
        @endif

        {{-- 4. Parcours --}}
        @if ($step === 4)
            <div class="fieldset-title">Baccalauréat</div>
            <div class="row g-3">
                <x-champ name="serie_bac" label="Série" :value="$d['serie_bac'] ?? ''" required placeholder="Ex. : Sciences Mathématiques A" />
                <x-champ name="annee_bac" label="Année d'obtention" :value="$d['annee_bac'] ?? ''" :options="$annees" required />
                <x-champ name="scan_bac" label="Scan du baccalauréat" type="file" accept=".pdf,.jpg,.jpeg,.png" :required="!$dejaEnvoye('scan_bac')" col="col-12" aide="PDF, JPG ou PNG · 10 Mo max.">
                    @if ($dejaEnvoye('scan_bac'))<span class="file-kept"><span class="material-symbols-rounded">check_circle</span> Fichier déjà envoyé, vous pouvez le remplacer</span>@endif
                </x-champ>
            </div>

            <div class="fieldset-title">Diplôme Bac+2</div>
            <div class="row g-3">
                <x-champ name="type_diplome_bac_2" label="Type de diplôme" :value="$d['type_diplome_bac_2'] ?? ''" required placeholder="DEUG, DEUST, DUT, BTS, DTS…" />
                <x-champ name="filiere_diplome_bac_2" label="Filière" :value="$d['filiere_diplome_bac_2'] ?? ''" required placeholder="Ex. : MIP" />
                <x-champ name="etablissement_bac_2" label="Établissement" :value="$d['etablissement_bac_2'] ?? ''" required />
                <x-champ name="annee_diplome_bac_2" label="Année d'obtention" :value="$d['annee_diplome_bac_2'] ?? ''" :options="$annees" required />
                <x-champ name="scan_bac_2" label="Scan du diplôme Bac+2" type="file" accept=".pdf,.jpg,.jpeg,.png" :required="!$dejaEnvoye('scan_bac_2')" col="col-12" aide="PDF, JPG ou PNG · 10 Mo max.">
                    @if ($dejaEnvoye('scan_bac_2'))<span class="file-kept"><span class="material-symbols-rounded">check_circle</span> Fichier déjà envoyé, vous pouvez le remplacer</span>@endif
                </x-champ>
            </div>

            <div class="fieldset-title">Diplôme Bac+3 <span class="optional">· facultatif, obligatoire pour un Master</span></div>
            <div class="row g-3">
                <x-champ name="type_diplome_bac_3" label="Type de diplôme" :value="$d['type_diplome_bac_3'] ?? ''" placeholder="Licence fondamentale, Licence pro…" />
                <x-champ name="filiere_diplome_bac_3" label="Filière" :value="$d['filiere_diplome_bac_3'] ?? ''" />
                <x-champ name="etablissement_bac_3" label="Établissement" :value="$d['etablissement_bac_3'] ?? ''" />
                <x-champ name="annee_diplome_bac_3" label="Année d'obtention" :value="$d['annee_diplome_bac_3'] ?? ''" :options="$annees" />
                <x-champ name="scan_bac_3" label="Scan du diplôme Bac+3" type="file" accept=".pdf,.jpg,.jpeg,.png" col="col-12" aide="PDF, JPG ou PNG · 10 Mo max.">
                    @if ($dejaEnvoye('scan_bac_3'))<span class="file-kept"><span class="material-symbols-rounded">check_circle</span> Fichier déjà envoyé, vous pouvez le remplacer</span>@endif
                </x-champ>
            </div>
        @endif

        {{-- 5. Expérience --}}
        @if ($step === 5)
            @foreach ($listes as $liste => [$titreListe, $bouton, $dossier])
                @php $entrees = old($liste, $d[$liste] ?? []); @endphp
                <div class="fieldset-title">{{ $titreListe }} <span class="optional">· 3 maximum</span></div>
                <div class="repeat-list" data-liste="{{ $liste }}">
                    @foreach ($entrees as $i => $e)
                        @include('candidateur.candidat._entree', ['liste' => $liste, 'i' => $i, 'e' => $e])
                    @endforeach
                </div>
                <p class="repeat-empty" @if (count($entrees)) hidden @endif>Aucun élément ajouté.</p>
                <button type="button" class="btn btn-soft btn-sm mb-2 add-item" data-liste="{{ $liste }}">
                    <span class="material-symbols-rounded">add</span> {{ $bouton }}
                </button>
                <template id="tpl-{{ $liste }}">
                    @include('candidateur.candidat._entree', ['liste' => $liste, 'i' => '__i__', 'e' => []])
                </template>
            @endforeach
        @endif

        {{-- 6. Documents & envoi --}}
        @if ($step === 6)
            <div class="row g-3">
                @foreach ([
                    'CV' => ['Curriculum vitae (CV)', '.pdf,.jpg,.jpeg,.png', 'PDF, JPG ou PNG · 10 Mo max.'],
                    'demande' => ['Lettre de demande', '.pdf,.jpg,.jpeg,.png', 'PDF, JPG ou PNG · 10 Mo max.'],
                    'scan_cartid' => ['Pièce d\'identité (CIN ou passeport)', '.pdf,.jpg,.jpeg,.png', 'Recto-verso · PDF, JPG ou PNG.'],
                    'photo' => ['Photo d\'identité', '.jpg,.jpeg,.png', 'JPG ou PNG · 5 Mo max.'],
                ] as $champ => [$libelle, $accept, $aide])
                    <x-champ :name="$champ" :label="$libelle" type="file" :accept="$accept" :required="!$dejaEnvoye($champ)" :aide="$aide">
                        @if ($dejaEnvoye($champ))<span class="file-kept"><span class="material-symbols-rounded">check_circle</span> Déjà envoyé</span>@endif
                    </x-champ>
                @endforeach
            </div>

            <div class="fieldset-title mt-4">Récapitulatif</div>
            <div class="recap">
                <div class="recap-box">
                    <h4>Formation <a href="{{ route('candidat.form', ['step' => 1]) }}">Modifier</a></h4>
                    <p><strong>{{ $formationChoisie->titre ?? '—' }}</strong><br>{{ $formationChoisie->type_formation ?? '' }}</p>
                </div>
                <div class="recap-box">
                    <h4>Identité <a href="{{ route('candidat.form', ['step' => 2]) }}">Modifier</a></h4>
                    <p><strong>{{ $d['nom'] ?? '' }} {{ $d['prenom'] ?? '' }}</strong><br>CNE {{ $d['CNE'] ?? '' }} · CIN {{ $d['CIN'] ?? '' }}<br>Né(e) le {{ !empty($d['date_naissance']) ? \Carbon\Carbon::parse($d['date_naissance'])->format('d/m/Y') : '' }}</p>
                </div>
                <div class="recap-box">
                    <h4>Coordonnées <a href="{{ route('candidat.form', ['step' => 3]) }}">Modifier</a></h4>
                    <p>{{ $d['email'] ?? '' }}<br>{{ $d['telephone_mob'] ?? '' }}<br>{{ $d['ville'] ?? '' }}, {{ $d['pays'] ?? '' }}</p>
                </div>
                <div class="recap-box">
                    <h4>Parcours <a href="{{ route('candidat.form', ['step' => 4]) }}">Modifier</a></h4>
                    <p>Bac {{ $d['serie_bac'] ?? '' }} ({{ $d['annee_bac'] ?? '' }})<br>{{ $d['type_diplome_bac_2'] ?? '' }} {{ $d['filiere_diplome_bac_2'] ?? '' }}
                        @if (!empty($d['type_diplome_bac_3']))<br>{{ $d['type_diplome_bac_3'] }} {{ $d['filiere_diplome_bac_3'] ?? '' }}@endif</p>
                </div>
                <div class="recap-box">
                    <h4>Expérience <a href="{{ route('candidat.form', ['step' => 5]) }}">Modifier</a></h4>
                    <p>{{ count($d['stages'] ?? []) }} stage(s) · {{ count($d['experiences'] ?? []) }} expérience(s) · {{ count($d['attestations'] ?? []) }} attestation(s)</p>
                </div>
            </div>

            <div class="form-check p-3 rounded-3" style="background: var(--bg); padding-left: 2.6rem !important;">
                <input class="form-check-input @error('certifie') is-invalid @enderror" type="checkbox" name="certifie" id="certifie" value="1" required>
                <label class="form-check-label" for="certifie">
                    Je certifie sur l'honneur l'exactitude des informations et des documents fournis.
                </label>
                @error('certifie')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
        @endif
        </div>

        <div class="form-card-foot">
            @if ($step > 1)
                <a href="{{ route('candidat.form', ['step' => $step - 1]) }}" class="btn btn-light">
                    <span class="material-symbols-rounded">arrow_back</span> Précédent
                </a>
            @else
                <a href="{{ route('accueil') }}" class="btn btn-light">
                    <span class="material-symbols-rounded">arrow_back</span> Formations
                </a>
            @endif

            @if ($step < 6)
                <button type="submit" class="btn btn-brand">Suivant <span class="material-symbols-rounded">arrow_forward</span></button>
            @else
                <button type="submit" class="btn btn-brand" style="background: var(--ok); border-color: var(--ok);">
                    <span class="material-symbols-rounded">send</span> Envoyer ma préinscription
                </button>
            @endif
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Blocs répétables : ajouter / supprimer, 3 maximum par liste
    (function () {
        const MAX = 3;
        let compteur = 1000;

        function majListe(liste) {
            const conteneur = document.querySelector(`.repeat-list[data-liste="${liste}"]`);
            const n = conteneur.children.length;
            conteneur.nextElementSibling.hidden = n > 0;
            document.querySelector(`.add-item[data-liste="${liste}"]`).disabled = n >= MAX;
        }

        document.querySelectorAll('.add-item').forEach(function (bouton) {
            const liste = bouton.dataset.liste;
            majListe(liste);
            bouton.addEventListener('click', function () {
                const html = document.getElementById('tpl-' + liste).innerHTML.replaceAll('__i__', compteur++);
                document.querySelector(`.repeat-list[data-liste="${liste}"]`).insertAdjacentHTML('beforeend', html);
                majListe(liste);
            });
        });

        document.addEventListener('click', function (e) {
            const bouton = e.target.closest('.remove-item');
            if (!bouton) return;
            const liste = bouton.closest('.repeat-list').dataset.liste;
            bouton.closest('.repeat-item').remove();
            majListe(liste);
        });
    })();
</script>
@endpush
