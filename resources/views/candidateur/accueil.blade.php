@extends('candidateur.layout.index')
@section('title', __('Formations'))

@section('content')
<section class="hero">
    <h1>{{ config('etablissement.nom') }}</h1>
    <p>{{ config('etablissement.slogan') ?: __('Préinscriptions :annee : choisissez une formation puis déposez votre dossier en ligne, en quelques minutes.', ['annee' => date('Y')]) }}</p>
    <div class="hero-steps">
        <span><b>1</b> {{ __('Choisir une formation') }}</span>
        <span><b>2</b> {{ __('Remplir le formulaire') }}</span>
        <span><b>3</b> {{ __('Joindre les documents') }}</span>
        <span><b>4</b> {{ __('Recevoir la référence') }}</span>
    </div>
</section>

@if (session('form_data._etape', 1) > 1)
    <div class="alert d-flex flex-wrap align-items-center gap-2 border-0 mb-4" style="background: var(--brand-50); border-radius: 12px;">
        <span class="material-symbols-rounded" style="color: var(--brand);">edit_note</span>
        <span class="flex-grow-1">{{ __('Vous avez une préinscription en cours.') }}</span>
        <a href="{{ route('candidat.form', ['step' => session('form_data._etape')]) }}" class="btn btn-brand btn-sm">{{ __('Continuer') }}</a>
        <form action="{{ route('candidat.recommencer') }}" method="POST" class="m-0">
            @csrf
            <button class="btn btn-light btn-sm">{{ __('Tout effacer') }}</button>
        </form>
    </div>
@endif

@forelse ($formations as $type => $liste)
    <h2 class="section-heading">{{ __($type) }} <span class="count">{{ $liste->count() }}</span></h2>
    <div class="row g-3 mb-4">
        @foreach ($liste as $formation)
            @php $jours = (int) today()->diffInDays(\Carbon\Carbon::parse($formation->date_fin), false); @endphp
            <div class="col-md-6 col-lg-4">
                <div class="formation-card">
                    <span class="formation-type">{{ __($formation->type_formation) }}</span>
                    <h3><a href="{{ route('formation.public', $formation) }}" class="text-reset text-decoration-none">{{ $formation->titre }}</a></h3>
                    @if ($formation->description)
                        <p>{{ $formation->description }}</p>
                    @endif
                    @if ($formation->duree || $formation->places)
                        <div class="facts">
                            @if ($formation->duree)<span><span class="material-symbols-rounded">schedule</span> {{ $formation->duree }}</span>@endif
                            @if ($formation->places)<span><span class="material-symbols-rounded">groups</span> {{ trans_choice(':n place|:n places', $formation->places, ['n' => $formation->places]) }}</span>@endif
                        </div>
                    @endif
                    <div class="formation-meta">
                        <span>
                            <span class="material-symbols-rounded">event</span>
                            {{ __("Jusqu'au :date", ['date' => \Carbon\Carbon::parse($formation->date_fin)->translatedFormat('d M Y')]) }}
                        </span>
                        <span class="{{ $jours <= 7 ? 'urgent' : '' }}">
                            {{ $jours === 0 ? __('Dernier jour') : trans_choice(':n jour restant|:n jours restants', $jours, ['n' => $jours]) }}
                        </span>
                    </div>
                    <div class="actions">
                        <a href="{{ route('formation.public', $formation) }}" class="btn btn-soft">{{ __('Détails') }}</a>
                        <a href="{{ route('candidat.form', ['formation' => $formation->id]) }}" class="btn btn-brand">
                            {{ __('Postuler') }} <span class="material-symbols-rounded flip">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@empty
    <div class="panel mb-4">
        <div class="empty-state">
            <span class="material-symbols-rounded">event_busy</span>
            {{ __("Aucune formation n'est ouverte aux préinscriptions pour le moment.") }}
        </div>
    </div>
@endforelse

@if ($aVenir->isNotEmpty())
    <h2 class="section-heading mt-2">{{ __('Bientôt ouvertes') }}</h2>
    <div class="panel mb-4">
        <div class="table-responsive">
            <table class="table table-clean">
                <tbody>
                    @foreach ($aVenir as $formation)
                        <tr>
                            <td><span class="formation-type">{{ __($formation->type_formation) }}</span></td>
                            <td class="fw-semibold"><a href="{{ route('formation.public', $formation) }}" class="text-reset">{{ $formation->titre }}</a></td>
                            <td class="text-muted text-end text-nowrap">{{ __('Ouverture le :date', ['date' => \Carbon\Carbon::parse($formation->date_debut)->translatedFormat('d M Y')]) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
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
    <h2 class="section-heading mt-2" id="etablissement">{{ __("L'établissement") }}</h2>
    <div class="about">
        <div class="panel">
            <div class="panel-body about-text">{{ config('etablissement.presentation') ?: config('etablissement.nom') }}</div>
        </div>
        @if ($contacts)
            <div class="panel">
                <div class="panel-head"><h3><span class="material-symbols-rounded">contact_support</span> {{ __('Contact') }}</h3></div>
                <div class="panel-body contact-list">
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
    </div>
@endif
@endsection
