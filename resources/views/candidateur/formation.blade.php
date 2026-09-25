@extends('candidateur.layout.index')
@section('title', $formation->tr('titre'))

@php
    // Texte saisi « une idée par ligne » -> liste
    $lignes = fn ($texte) => collect(preg_split('/\r\n|\r|\n/', (string) $texte))->map(fn ($l) => trim($l, " \t-•*"))->filter()->values();
    $debut = \Carbon\Carbon::parse($formation->date_debut);
    $fin = \Carbon\Carbon::parse($formation->date_fin);
    $jours = (int) today()->diffInDays($fin, false);
    $conditions = $lignes($formation->tr('conditions_acces'));
    $modalites = $lignes($formation->tr('modalites_selection'));
    $debouches = $lignes($formation->tr('debouches'));
@endphp

@section('content')
<a href="{{ route('accueil') }}" class="d-inline-flex align-items-center gap-1 text-decoration-none fw-semibold mb-3">
    <span class="material-symbols-rounded flip">arrow_back</span> {{ __('Toutes les formations') }}
</a>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="fiche-head mb-4">
            <span class="formation-type">{{ __($formation->type_formation) }}</span>
            <h1>{{ $formation->tr('titre') }}</h1>
            <div class="fiche-facts">
                @if ($formation->tr('duree'))
                    <span><span class="material-symbols-rounded">schedule</span> {{ $formation->tr('duree') }}</span>
                @endif
                @if ($formation->places)
                    <span><span class="material-symbols-rounded">groups</span> {{ trans_choice(':n place|:n places', $formation->places, ['n' => $formation->places]) }}</span>
                @endif
                @if (config('etablissement.ville'))
                    <span><span class="material-symbols-rounded">location_on</span> {{ config('etablissement.ville') }}</span>
                @endif
                <span><span class="material-symbols-rounded">event</span> {{ __('Préinscriptions du :debut au :fin', ['debut' => $debut->format('d/m/Y'), 'fin' => $fin->format('d/m/Y')]) }}</span>
            </div>
        </div>

        <div class="panel mb-4">
            <div class="panel-head"><h3><span class="material-symbols-rounded">info</span> {{ __('Présentation') }}</h3></div>
            <div class="panel-body fiche-texte">
                @if ($formation->tr('description'))
                    {!! nl2br(e($formation->tr('description'))) !!}
                @else
                    <span class="text-muted">{{ __('La présentation détaillée de cette formation sera bientôt disponible.') }}</span>
                @endif
            </div>
        </div>

        <div class="row g-4">
            @if ($conditions->isNotEmpty())
                <div class="col-md-6">
                    <div class="panel h-100">
                        <div class="panel-head"><h3><span class="material-symbols-rounded">rule</span> {{ __("Conditions d'accès") }}</h3></div>
                        <div class="panel-body">
                            <ul class="fiche-liste">
                                @foreach ($conditions as $c)<li>{{ $c }}</li>@endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            @if ($modalites->isNotEmpty())
                <div class="col-md-6">
                    <div class="panel h-100">
                        <div class="panel-head"><h3><span class="material-symbols-rounded">how_to_reg</span> {{ __('Sélection') }}</h3></div>
                        <div class="panel-body">
                            <ol class="fiche-etapes">
                                @foreach ($modalites as $m)<li>{{ $m }}</li>@endforeach
                            </ol>
                        </div>
                    </div>
                </div>
            @endif
            @if ($debouches->isNotEmpty())
                <div class="col-12">
                    <div class="panel">
                        <div class="panel-head"><h3><span class="material-symbols-rounded">work</span> {{ __('Débouchés') }}</h3></div>
                        <div class="panel-body d-flex flex-wrap gap-2">
                            @foreach ($debouches as $d)<span class="chip">{{ $d }}</span>@endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="col-lg-4">
        <div class="panel fiche-cta mb-4">
            <div class="panel-body">
                @if ($ouverte)
                    <div class="fiche-compte {{ $jours <= 7 ? 'urgent' : '' }}">
                        @if ($jours === 0)
                            <span class="n">!</span><span>{{ __('Dernier jour pour postuler') }}</span>
                        @else
                            <span class="n">{{ $jours }}</span><span>{{ trans_choice('jour pour postuler|jours pour postuler', $jours) }}</span>
                        @endif
                    </div>
                    <a href="{{ route('candidat.form', ['formation' => $formation->id]) }}" class="btn btn-brand w-100 justify-content-center py-2 mb-2">
                        {{ __('Postuler à cette formation') }} <span class="material-symbols-rounded flip">arrow_forward</span>
                    </a>
                    <p class="text-muted small mb-0">{{ __("Environ 10 minutes. Préparez : pièce d'identité, CV, lettre de demande, photo, scans du bac et des diplômes.") }}</p>
                @else
                    <div class="fiche-compte">
                        <span class="material-symbols-rounded" style="font-size: 32px;">event_upcoming</span>
                        <span>{{ __('Ouverture des préinscriptions le :date', ['date' => $debut->translatedFormat('d F Y')]) }}</span>
                    </div>
                @endif
            </div>
        </div>

        @if (config('etablissement.email') || config('etablissement.telephone') || config('etablissement.adresse'))
            <div class="panel mb-4">
                <div class="panel-head"><h3><span class="material-symbols-rounded">support_agent</span> {{ __('Une question ?') }}</h3></div>
                <div class="panel-body small d-grid gap-2">
                    @if (config('etablissement.email'))<a href="mailto:{{ config('etablissement.email') }}"><span class="material-symbols-rounded">mail</span> {{ config('etablissement.email') }}</a>@endif
                    @if (config('etablissement.telephone'))<a href="tel:{{ config('etablissement.telephone') }}" dir="ltr"><span class="material-symbols-rounded">call</span> {{ config('etablissement.telephone') }}</a>@endif
                    @if (config('etablissement.adresse'))<span><span class="material-symbols-rounded">location_on</span> {{ config('etablissement.adresse') }}</span>@endif
                </div>
            </div>
        @endif

        @if ($autres->isNotEmpty())
            <div class="panel">
                <div class="panel-head"><h3><span class="material-symbols-rounded">school</span> {{ __('Autres formations') }}</h3></div>
                <div class="panel-body d-grid gap-2">
                    @foreach ($autres as $a)
                        <a href="{{ route('formation.public', $a) }}" class="doc-link">
                            <span class="material-symbols-rounded flip">chevron_right</span>
                            <span><span class="d-block">{{ $a->tr('titre') }}</span><span class="person-sub">{{ __($a->type_formation) }}</span></span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
