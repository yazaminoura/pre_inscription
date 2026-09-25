@extends('candidateur.layout.index')
@section('title', __('Formations'))

@php
    $toutes = $formations->flatten(1);
    $prochaineCloture = $toutes->min('date_fin');
    $joursCloture = $prochaineCloture ? (int) today()->diffInDays(\Carbon\Carbon::parse($prochaineCloture), false) : null;
    $etapesAccueil = [
        ['school', __('Choisir une formation'), __('Consultez les fiches et vérifiez la condition d\'accès.')],
        ['edit_note', __('Remplir le formulaire'), __('Identité, coordonnées, parcours et expérience, en 6 étapes.')],
        ['upload_file', __('Joindre les documents'), __('CV, lettre, pièce d\'identité, photo et diplômes.')],
        ['confirmation_number', __('Recevoir la référence'), __('Un récapitulatif PDF et un email de confirmation.')],
    ];
@endphp

@section('content')
{{-- Accueil --}}
<section class="home-hero">
    <div class="home-hero-text">
        <span class="home-hero-kicker"><span class="material-symbols-rounded">campaign</span> {{ __('Préinscriptions :annee ouvertes', ['annee' => date('Y')]) }}</span>
        <h1>{{ config('etablissement.nom') }}</h1>
        <p>{{ config('etablissement.slogan') ?: __('Préinscriptions :annee : choisissez une formation puis déposez votre dossier en ligne, en quelques minutes.', ['annee' => date('Y')]) }}</p>
        <div class="d-flex flex-wrap gap-2">
            <a href="#formations" class="btn btn-light btn-lg fw-bold">
                <span class="material-symbols-rounded">school</span> {{ __('Voir les formations') }}
            </a>
            <a href="{{ route('suivi') }}" class="btn btn-outline-light btn-lg">
                <span class="material-symbols-rounded">travel_explore</span> {{ __('Suivre mon dossier') }}
            </a>
        </div>
    </div>
    <div class="home-hero-stats">
        <div class="stat">
            <span class="n">{{ $toutes->count() }}</span>
            <span class="l">{{ trans_choice('formation ouverte|formations ouvertes', $toutes->count()) }}</span>
        </div>
        @if ($joursCloture !== null)
            <div class="stat">
                <span class="n">{{ max(0, $joursCloture) }}</span>
                <span class="l">{{ __('jours avant la prochaine clôture') }}</span>
            </div>
        @endif
        <div class="stat">
            <span class="n">100 %</span>
            <span class="l">{{ __('en ligne, sans déplacement') }}</span>
        </div>
    </div>
</section>

@if (session('form_data._etape', 1) > 1)
    <div class="resume-banner">
        <span class="material-symbols-rounded">edit_note</span>
        <span class="flex-grow-1"><strong>{{ __('Vous avez une préinscription en cours.') }}</strong></span>
        <a href="{{ route('candidat.form', ['step' => session('form_data._etape')]) }}" class="btn btn-brand btn-sm">{{ __('Continuer') }} <span class="material-symbols-rounded flip">arrow_forward</span></a>
        <form action="{{ route('candidat.recommencer') }}" method="POST" class="m-0">
            @csrf
            <button class="btn btn-light btn-sm">{{ __('Tout effacer') }}</button>
        </form>
    </div>
@endif

{{-- Comment ça marche --}}
<section class="how">
    @foreach ($etapesAccueil as $i => [$icone, $titre, $texte])
        <div class="how-step">
            <span class="how-icon"><span class="material-symbols-rounded">{{ $icone }}</span><b>{{ $i + 1 }}</b></span>
            <div>
                <h3>{{ $titre }}</h3>
                <p>{{ $texte }}</p>
            </div>
        </div>
    @endforeach
</section>

{{-- Formations --}}
<section id="formations" class="mb-5">
    <div class="formations-head">
        <div>
            <h2 class="home-title">{{ __('Nos formations') }}</h2>
            <p class="text-muted m-0">{{ __('Ouvertes aux préinscriptions en ce moment.') }}</p>
        </div>
        @if ($toutes->count() > 3)
            <div class="formations-tools">
                <div class="input-icon">
                    <span class="material-symbols-rounded">search</span>
                    <input type="search" id="recherche-formation" class="form-control" placeholder="{{ __('Rechercher une formation…') }}">
                </div>
                @if ($formations->count() > 1)
                    <div class="type-chips" role="group">
                        <button type="button" class="type-chip active" data-type="">{{ __('Toutes') }} <span>{{ $toutes->count() }}</span></button>
                        @foreach ($formations as $type => $liste)
                            <button type="button" class="type-chip" data-type="{{ $type }}">{{ __($type) }} <span>{{ $liste->count() }}</span></button>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>

    @if ($toutes->isEmpty())
        <div class="panel">
            <div class="empty-state">
                <span class="material-symbols-rounded">event_busy</span>
                {{ __("Aucune formation n'est ouverte aux préinscriptions pour le moment.") }}
            </div>
        </div>
    @else
        <div class="row g-4" id="liste-formations">
            @foreach ($toutes as $formation)
                @php $jours = (int) today()->diffInDays(\Carbon\Carbon::parse($formation->date_fin), false); @endphp
                <div class="col-md-6 col-lg-4 carte-formation" data-type="{{ $formation->type_formation }}"
                     data-texte="{{ mb_strtolower($formation->tr('titre') . ' ' . $formation->titre . ' ' . __($formation->type_formation) . ' ' . $formation->tr('description')) }}">
                    <article class="fcard">
                        <div class="fcard-top">
                            <span class="fcard-type">{{ __($formation->type_formation) }}</span>
                            <span class="fcard-days {{ $jours <= 7 ? 'urgent' : '' }}">
                                <span class="material-symbols-rounded">schedule</span>
                                {{ $jours === 0 ? __('Dernier jour') : trans_choice(':n jour restant|:n jours restants', $jours, ['n' => $jours]) }}
                            </span>
                        </div>
                        <h3><a href="{{ route('formation.public', $formation) }}">{{ $formation->tr('titre') }}</a></h3>
                        @if ($formation->tr('description'))
                            <p class="fcard-desc">{{ $formation->tr('description') }}</p>
                        @endif
                        <ul class="fcard-facts">
                            <li><span class="material-symbols-rounded">workspace_premium</span> {{ $formation->conditionAcces() }}</li>
                            @if ($formation->tr('duree'))<li><span class="material-symbols-rounded">hourglass_top</span> {{ $formation->tr('duree') }}</li>@endif
                            @if ($formation->places)<li><span class="material-symbols-rounded">groups</span> {{ trans_choice(':n place|:n places', $formation->places, ['n' => $formation->places]) }}</li>@endif
                            <li><span class="material-symbols-rounded">event</span> {{ __("Jusqu'au :date", ['date' => \Carbon\Carbon::parse($formation->date_fin)->translatedFormat('d M Y')]) }}</li>
                        </ul>
                        <div class="fcard-actions">
                            <a href="{{ route('formation.public', $formation) }}" class="btn btn-soft">{{ __('Détails') }}</a>
                            <a href="{{ route('candidat.form', ['formation' => $formation->id]) }}" class="btn btn-brand">
                                {{ __('Postuler') }} <span class="material-symbols-rounded flip">arrow_forward</span>
                            </a>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
        <div class="empty-state d-none" id="aucun-resultat">
            <span class="material-symbols-rounded">search_off</span>
            {{ __('Aucune formation ne correspond à votre recherche.') }}
        </div>
    @endif
</section>

@if ($aVenir->isNotEmpty())
    <section class="mb-5">
        <h2 class="home-title">{{ __('Bientôt ouvertes') }}</h2>
        <div class="upcoming">
            @foreach ($aVenir as $formation)
                <a href="{{ route('formation.public', $formation) }}" class="upcoming-item">
                    <span class="upcoming-date">
                        <b>{{ \Carbon\Carbon::parse($formation->date_debut)->format('d') }}</b>
                        {{ \Carbon\Carbon::parse($formation->date_debut)->translatedFormat('M') }}
                    </span>
                    <span class="flex-grow-1">
                        <span class="d-block fw-bold">{{ $formation->tr('titre') }}</span>
                        <span class="person-sub">{{ __($formation->type_formation) }} · {{ __('Ouverture le :date', ['date' => \Carbon\Carbon::parse($formation->date_debut)->translatedFormat('d M Y')]) }}</span>
                    </span>
                    <span class="material-symbols-rounded flip">chevron_right</span>
                </a>
            @endforeach
        </div>
    </section>
@endif

@php
    $contacts = array_filter([
        'location_on' => trim(collect([config('etablissement.adresse'), config('etablissement.ville'), config('etablissement.pays')])->filter()->implode(', ')),
        'call' => config('etablissement.telephone'),
        'mail' => config('etablissement.email'),
        'language' => config('etablissement.site'),
    ]);
@endphp
@if (config('etablissement.presentation') || $contacts)
    <section class="about-block mb-5" id="etablissement">
        <div class="about-main">
            <img src="{{ asset(config('etablissement.logo')) }}" alt="" class="about-logo">
            <h2 class="home-title">{{ __("L'établissement") }}</h2>
            <div class="about-text">{{ config('etablissement.presentation') ?: config('etablissement.nom') }}</div>
        </div>
        @if ($contacts)
            <div class="about-contact">
                <h3>{{ __('Contact') }}</h3>
                <div class="contact-list">
                    @foreach ($contacts as $icone => $valeur)
                        @if ($icone === 'mail')
                            <a href="mailto:{{ $valeur }}"><span class="material-symbols-rounded">{{ $icone }}</span> {{ $valeur }}</a>
                        @elseif ($icone === 'call')
                            <a href="tel:{{ $valeur }}" dir="ltr"><span class="material-symbols-rounded">{{ $icone }}</span> {{ $valeur }}</a>
                        @elseif ($icone === 'language')
                            <a href="{{ $valeur }}" target="_blank" rel="noopener"><span class="material-symbols-rounded">{{ $icone }}</span> {{ parse_url($valeur, PHP_URL_HOST) ?: $valeur }}</a>
                        @else
                            <span><span class="material-symbols-rounded">{{ $icone }}</span> {{ $valeur }}</span>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    </section>
@endif

{{-- Déjà candidat --}}
<section class="cta-band">
    <div>
        <h2>{{ __('Déjà candidat ?') }}</h2>
        <p>{{ __('Consultez le statut de votre dossier avec votre référence et votre email.') }}</p>
    </div>
    <a href="{{ route('suivi') }}" class="btn btn-light btn-lg fw-bold"><span class="material-symbols-rounded">travel_explore</span> {{ __('Suivre mon dossier') }}</a>
</section>
@endsection

@push('scripts')
<script>
    // Filtre des formations : type (boutons) + recherche texte
    (function () {
        const recherche = document.getElementById('recherche-formation');
        const puces = document.querySelectorAll('.type-chip');
        const cartes = document.querySelectorAll('.carte-formation');
        if (!cartes.length) return;
        let type = '';

        function filtrer() {
            const texte = (recherche?.value || '').trim().toLowerCase();
            let visibles = 0;
            cartes.forEach(function (c) {
                const ok = (!type || c.dataset.type === type) && (!texte || c.dataset.texte.includes(texte));
                c.classList.toggle('d-none', !ok);
                if (ok) visibles++;
            });
            document.getElementById('aucun-resultat')?.classList.toggle('d-none', visibles > 0);
        }

        puces.forEach(function (p) {
            p.addEventListener('click', function () {
                puces.forEach(x => x.classList.remove('active'));
                p.classList.add('active');
                type = p.dataset.type;
                filtrer();
            });
        });
        recherche?.addEventListener('input', filtrer);
    })();
</script>
@endpush
