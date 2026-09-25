@extends('candidateur.layout.index')
@section('title', __($etapes[$step]))

@php
    $annees = collect(range(now()->year, 1990))->mapWithKeys(fn ($a) => [$a => $a])->all();
    $d = $data;
    // Fichier déjà envoyé : on l'indique et le champ devient facultatif
    $dejaEnvoye = fn ($cle) => !empty($d[$cle]);
    $titres = [
        1 => [__('Quelle formation ?'), __('Choisissez la formation à laquelle vous souhaitez vous préinscrire.')],
        2 => [__('Identité'), __("Tels qu'ils figurent sur votre pièce d'identité.")],
        3 => [__('Coordonnées'), __('Pour vous contacter au sujet de votre dossier.')],
        4 => [__('Parcours académique'), __('Votre baccalauréat et vos diplômes après le bac.')],
        5 => [__('Expérience'), __('Stages, expériences professionnelles et attestations. Tout est facultatif.')],
        6 => [__('Documents & envoi'), __('Joignez vos pièces, vérifiez le récapitulatif puis envoyez.')],
    ];
    $listes = [
        'stages' => [__('Stages'), __('Ajouter un stage')],
        'experiences' => [__('Expériences professionnelles'), __('Ajouter une expérience')],
        'attestations' => [__('Attestations (langues, certifications, bénévolat…)'), __('Ajouter une attestation')],
    ];
    $formatsFichier = __('PDF, JPG ou PNG · 10 Mo max.');
    $dejaEnvoyeTexte = __('Fichier déjà envoyé, vous pouvez le remplacer');
    $anneeObtention = __("Année d'obtention");
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
                    <span class="stepper-label d-block">{{ __($etape) }}</span>
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
            <div>{{ trans_choice('Merci de corriger le champ signalé ci-dessous.|Merci de corriger les :n champs signalés ci-dessous.', $errors->count(), ['n' => $errors->count()]) }}</div>
        </div>
    @endif

    @if ($step > 1 && $formationChoisie)
        <div class="d-flex align-items-center gap-2 mb-3 small text-muted">
            <span class="material-symbols-rounded" style="color: var(--brand);">school</span>
            {{ __('Préinscription') }} : <strong class="text-body">{{ __($formationChoisie->type_formation) }} · {{ $formationChoisie->tr('titre') }}</strong>
            <a href="{{ route('candidat.form', ['step' => 1]) }}" class="ms-1">{{ __('modifier') }}</a>
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
                <div class="fieldset-title">{{ __($type) }}</div>
                <div class="choice-grid mb-2">
                    @foreach ($liste as $f)
                        <label class="choice">
                            <input type="radio" name="titre_id" value="{{ $f->id }}" class="form-check-input" @checked((int) old('titre_id', $d['titre_id'] ?? 0) === $f->id) required>
                            <span class="choice-title">{{ $f->tr('titre') }}</span>
                            <span class="choice-sub">{{ __('Clôture le :date', ['date' => \Carbon\Carbon::parse($f->date_fin)->format('d/m/Y')]) }}</span>
                        </label>
                    @endforeach
                </div>
            @empty
                <div class="empty-state"><span class="material-symbols-rounded">event_busy</span>{{ __("Aucune formation n'est ouverte pour le moment.") }}</div>
            @endforelse
            @error('titre_id')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
        @endif

        {{-- 2. Identité --}}
        @if ($step === 2)
            <div class="row g-3">
                <x-champ name="nom" :label="__('Nom')" :value="$d['nom'] ?? ''" required autocomplete="family-name" />
                <x-champ name="prenom" :label="__('Prénom')" :value="$d['prenom'] ?? ''" required autocomplete="given-name" />
                <x-champ name="nom_ar" label="الاسم العائلي" :value="$d['nom_ar'] ?? ''" dir="rtl" :aide="__('En arabe · facultatif')" />
                <x-champ name="prenom_ar" label="الاسم الشخصي" :value="$d['prenom_ar'] ?? ''" dir="rtl" :aide="__('En arabe · facultatif')" />
                <x-champ name="CNE" :label="__('CNE / Code Massar')" :value="$d['CNE'] ?? ''" required />
                <x-champ name="CIN" :label="__('CIN ou n° de passeport')" :value="$d['CIN'] ?? ''" required />
                <x-champ name="date_naissance" :label="__('Date de naissance')" type="date" :value="$d['date_naissance'] ?? ''" required />
                <x-champ name="sex" :label="__('Sexe')" :value="$d['sex'] ?? ''" :options="['Homme' => __('Homme'), 'Femme' => __('Femme')]" :placeholder="__('Choisir…')" required />
                <x-champ name="ville_naissance" :label="__('Ville de naissance')" :value="$d['ville_naissance'] ?? ''" required />
                <x-champ name="pay_naissance" :label="__('Pays de naissance')" :value="$d['pay_naissance'] ?? ''" required />
                <x-champ name="nationalite" :label="__('Nationalité')" :value="$d['nationalite'] ?? ''" required />
                <x-champ name="ville_naissance_ar" label="مدينة الازدياد" :value="$d['ville_naissance_ar'] ?? ''" dir="rtl" :aide="__('En arabe · facultatif')" />
            </div>
        @endif

        {{-- 3. Coordonnées --}}
        @if ($step === 3)
            <div class="row g-3">
                <x-champ name="email" :label="__('Email')" type="email" :value="$d['email'] ?? ''" required autocomplete="email" :aide="__('Votre référence et les nouvelles de votre dossier arriveront ici.')" />
                <x-champ name="telephone_mob" :label="__('Téléphone mobile')" type="tel" :value="$d['telephone_mob'] ?? ''" required placeholder="+212 6 12 34 56 78" autocomplete="tel" dir="ltr" />
                <x-champ name="adresse" :label="__('Adresse')" :value="$d['adresse'] ?? ''" required col="col-12" autocomplete="street-address" />
                <x-champ name="ville" :label="__('Ville')" :value="$d['ville'] ?? ''" required />
                <x-champ name="province" :label="__('Province / région')" :value="$d['province'] ?? ''" required />
                <x-champ name="pays" :label="__('Pays de résidence')" :value="$d['pays'] ?? ''" required />
                <x-champ name="telephone_fix" :label="__('Téléphone fixe')" type="tel" :value="$d['telephone_fix'] ?? ''" :aide="__('Facultatif')" dir="ltr" />
            </div>
        @endif

        {{-- 4. Parcours --}}
        @if ($step === 4)
            <div class="fieldset-title">{{ __('Baccalauréat') }}</div>
            <div class="row g-3">
                <x-champ name="serie_bac" :label="__('Série')" :value="$d['serie_bac'] ?? ''" required :placeholder="__('Ex. : Sciences Mathématiques A')" />
                <x-champ name="annee_bac" :label="$anneeObtention" :value="$d['annee_bac'] ?? ''" :options="$annees" :placeholder="__('Choisir…')" required />
                <x-champ name="scan_bac" :label="__('Scan du baccalauréat')" type="file" accept=".pdf,.jpg,.jpeg,.png" :required="!$dejaEnvoye('scan_bac')" col="col-12" :aide="$formatsFichier">
                    @if ($dejaEnvoye('scan_bac'))<span class="file-kept"><span class="material-symbols-rounded">check_circle</span> {{ $dejaEnvoyeTexte }}</span>@endif
                </x-champ>
            </div>

            {{-- Diplômes après le bac : selon le niveau d'accès de la formation (aucun, Bac+2, Bac+2 et Bac+3) --}}
            @if ($diplomes[2] === 'non' && $diplomes[3] === 'non')
                <div class="d-flex gap-2 align-items-start mt-4 p-3 rounded-3" style="background: var(--brand-50);">
                    <span class="material-symbols-rounded" style="color: var(--brand);">info</span>
                    <span class="small">{{ __('Cette formation recrute après le baccalauréat : aucun diplôme supérieur n\'est demandé.') }}</span>
                </div>
            @endif
            @foreach ([2 => 'DEUG, DEUST, DUT, BTS, DTS…', 3 => __('Licence fondamentale, Licence pro…')] as $n => $exemple)
                @continue($diplomes[$n] === 'non')
                @php $requis = $diplomes[$n] === 'requis'; @endphp
                <div class="fieldset-title">{{ $n === 2 ? __('Diplôme Bac+2') : __('Diplôme Bac+3') }}
                    @unless ($requis)<span class="optional">· {{ __('facultatif') }}</span>@endunless
                </div>
                <div class="row g-3">
                    <x-champ :name="'type_diplome_bac_' . $n" :label="__('Type de diplôme')" :value="$d['type_diplome_bac_' . $n] ?? ''" :required="$requis" :placeholder="$exemple" />
                    <x-champ :name="'filiere_diplome_bac_' . $n" :label="__('Filière')" :value="$d['filiere_diplome_bac_' . $n] ?? ''" :required="$requis" :placeholder="__('Ex. : MIP')" />
                    <x-champ :name="'etablissement_bac_' . $n" :label="__('Établissement')" :value="$d['etablissement_bac_' . $n] ?? ''" :required="$requis" />
                    <x-champ :name="'annee_diplome_bac_' . $n" :label="$anneeObtention" :value="$d['annee_diplome_bac_' . $n] ?? ''" :options="$annees" :placeholder="__('Choisir…')" :required="$requis" />
                    <x-champ :name="'scan_bac_' . $n" :label="$n === 2 ? __('Scan du diplôme Bac+2') : __('Scan du diplôme Bac+3')" type="file" accept=".pdf,.jpg,.jpeg,.png" :required="$requis && !$dejaEnvoye('scan_bac_' . $n)" col="col-12" :aide="$formatsFichier">
                        @if ($dejaEnvoye('scan_bac_' . $n))<span class="file-kept"><span class="material-symbols-rounded">check_circle</span> {{ $dejaEnvoyeTexte }}</span>@endif
                    </x-champ>
                </div>
            @endforeach
        @endif

        {{-- 5. Expérience --}}
        @if ($step === 5)
            @foreach ($listes as $liste => [$titreListe, $bouton])
                @php $entrees = old($liste, $d[$liste] ?? []); @endphp
                <div class="fieldset-title">{{ $titreListe }} <span class="optional">· {{ __('3 maximum') }}</span></div>
                <div class="repeat-list" data-liste="{{ $liste }}">
                    @foreach ($entrees as $i => $e)
                        @include('candidateur.candidat._entree', ['liste' => $liste, 'i' => $i, 'e' => $e])
                    @endforeach
                </div>
                <p class="repeat-empty" @if (count($entrees)) hidden @endif>{{ __('Aucun élément ajouté.') }}</p>
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
                    'CV' => [__('Curriculum vitae (CV)'), '.pdf,.jpg,.jpeg,.png', $formatsFichier],
                    'demande' => [__('Lettre de demande'), '.pdf,.jpg,.jpeg,.png', $formatsFichier],
                    'scan_cartid' => [__("Pièce d'identité (CIN ou passeport)"), '.pdf,.jpg,.jpeg,.png', __('Recto-verso · PDF, JPG ou PNG.')],
                    'photo' => [__("Photo d'identité"), '.jpg,.jpeg,.png', __('JPG ou PNG · 5 Mo max.')],
                ] as $champ => [$libelle, $accept, $aide])
                    <x-champ :name="$champ" :label="$libelle" type="file" :accept="$accept" :required="!$dejaEnvoye($champ)" :aide="$aide">
                        @if ($dejaEnvoye($champ))<span class="file-kept"><span class="material-symbols-rounded">check_circle</span> {{ __('Déjà envoyé') }}</span>@endif
                    </x-champ>
                @endforeach
            </div>

            <div class="fieldset-title mt-4">{{ __('Récapitulatif') }}</div>
            <div class="recap">
                <div class="recap-box">
                    <h4>{{ __('Formation') }} <a href="{{ route('candidat.form', ['step' => 1]) }}">{{ __('Modifier') }}</a></h4>
                    <p><strong>{{ $formationChoisie?->tr('titre') ?? '—' }}</strong><br>{{ __($formationChoisie->type_formation ?? '') }}</p>
                </div>
                <div class="recap-box">
                    <h4>{{ __('Identité') }} <a href="{{ route('candidat.form', ['step' => 2]) }}">{{ __('Modifier') }}</a></h4>
                    <p><strong>{{ $d['nom'] ?? '' }} {{ $d['prenom'] ?? '' }}</strong><br>CNE {{ $d['CNE'] ?? '' }} · CIN {{ $d['CIN'] ?? '' }}<br>{{ !empty($d['date_naissance']) ? \Carbon\Carbon::parse($d['date_naissance'])->format('d/m/Y') : '' }}</p>
                </div>
                <div class="recap-box">
                    <h4>{{ __('Coordonnées') }} <a href="{{ route('candidat.form', ['step' => 3]) }}">{{ __('Modifier') }}</a></h4>
                    <p>{{ $d['email'] ?? '' }}<br><span dir="ltr">{{ $d['telephone_mob'] ?? '' }}</span><br>{{ $d['ville'] ?? '' }}, {{ $d['pays'] ?? '' }}</p>
                </div>
                <div class="recap-box">
                    <h4>{{ __('Parcours') }} <a href="{{ route('candidat.form', ['step' => 4]) }}">{{ __('Modifier') }}</a></h4>
                    <p>{{ __('Bac') }} {{ $d['serie_bac'] ?? '' }} ({{ $d['annee_bac'] ?? '' }})<br>{{ $d['type_diplome_bac_2'] ?? '' }} {{ $d['filiere_diplome_bac_2'] ?? '' }}
                        @if (!empty($d['type_diplome_bac_3']))<br>{{ $d['type_diplome_bac_3'] }} {{ $d['filiere_diplome_bac_3'] ?? '' }}@endif</p>
                </div>
                <div class="recap-box">
                    <h4>{{ __('Expérience') }} <a href="{{ route('candidat.form', ['step' => 5]) }}">{{ __('Modifier') }}</a></h4>
                    <p>{{ __(':s stage(s) · :e expérience(s) · :a attestation(s)', ['s' => count($d['stages'] ?? []), 'e' => count($d['experiences'] ?? []), 'a' => count($d['attestations'] ?? [])]) }}</p>
                </div>
            </div>

            <div class="form-check p-3 rounded-3" style="background: var(--bg); padding-inline-start: 2.6rem !important;">
                <input class="form-check-input @error('certifie') is-invalid @enderror" type="checkbox" name="certifie" id="certifie" value="1" required>
                <label class="form-check-label" for="certifie">
                    {{ __("Je certifie sur l'honneur l'exactitude des informations et des documents fournis.") }}
                </label>
                @error('certifie')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
        @endif
        </div>

        <div class="form-card-foot">
            @if ($step > 1)
                <a href="{{ route('candidat.form', ['step' => $step - 1]) }}" class="btn btn-light">
                    <span class="material-symbols-rounded flip">arrow_back</span> {{ __('Précédent') }}
                </a>
            @else
                <a href="{{ route('accueil') }}" class="btn btn-light">
                    <span class="material-symbols-rounded flip">arrow_back</span> {{ __('Formations') }}
                </a>
            @endif

            @if ($step < 6)
                <button type="submit" class="btn btn-brand">{{ __('Suivant') }} <span class="material-symbols-rounded flip">arrow_forward</span></button>
            @else
                <button type="submit" class="btn btn-brand" style="background: var(--ok); border-color: var(--ok);">
                    <span class="material-symbols-rounded flip">send</span> {{ __('Envoyer ma préinscription') }}
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
