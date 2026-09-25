@extends('candidateur.layout.index')
@section('title', 'Formations')

@section('content')
<section class="hero">
    <h1>Préinscriptions {{ date('Y') }}</h1>
    <p>Choisissez une formation ci-dessous puis déposez votre dossier en ligne, en quelques minutes. Vous recevrez un numéro de référence à la fin.</p>
    <div class="hero-steps">
        <span><b>1</b> Choisir une formation</span>
        <span><b>2</b> Remplir le formulaire</span>
        <span><b>3</b> Joindre les documents</span>
        <span><b>4</b> Recevoir la référence</span>
    </div>
</section>

@if (session('form_data._etape', 1) > 1)
    <div class="alert d-flex flex-wrap align-items-center gap-2 border-0 mb-4" style="background: var(--brand-50); border-radius: 12px;">
        <span class="material-symbols-rounded" style="color: var(--brand);">edit_note</span>
        <span class="flex-grow-1">Vous avez une préinscription en cours.</span>
        <a href="{{ route('candidat.form', ['step' => session('form_data._etape')]) }}" class="btn btn-brand btn-sm">Continuer</a>
        <form action="{{ route('candidat.recommencer') }}" method="POST" class="m-0">
            @csrf
            <button class="btn btn-light btn-sm">Tout effacer</button>
        </form>
    </div>
@endif

@forelse ($formations as $type => $liste)
    <h2 class="section-heading">{{ $type }} <span class="count">{{ $liste->count() }}</span></h2>
    <div class="row g-3 mb-4">
        @foreach ($liste as $formation)
            @php $jours = today()->diffInDays(\Carbon\Carbon::parse($formation->date_fin), false); @endphp
            <div class="col-md-6 col-lg-4">
                <div class="formation-card">
                    <span class="formation-type">{{ $formation->type_formation }}</span>
                    <h3>{{ $formation->titre }}</h3>
                    <div class="formation-meta">
                        <span>
                            <span class="material-symbols-rounded">event</span>
                            Jusqu'au {{ \Carbon\Carbon::parse($formation->date_fin)->translatedFormat('d M Y') }}
                        </span>
                        <span class="{{ $jours <= 7 ? 'urgent' : '' }}">
                            {{ $jours === 0 ? 'Dernier jour' : ($jours . ' j restant' . ($jours > 1 ? 's' : '')) }}
                        </span>
                    </div>
                    <a href="{{ route('candidat.form', ['formation' => $formation->id]) }}" class="btn btn-brand justify-content-center">
                        Postuler <span class="material-symbols-rounded">arrow_forward</span>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@empty
    <div class="panel">
        <div class="empty-state">
            <span class="material-symbols-rounded">event_busy</span>
            Aucune formation n'est ouverte aux préinscriptions pour le moment.
        </div>
    </div>
@endforelse

@if ($aVenir->isNotEmpty())
    <h2 class="section-heading mt-2">Bientôt ouvertes</h2>
    <div class="panel">
        <div class="table-responsive">
            <table class="table table-clean">
                <tbody>
                    @foreach ($aVenir as $formation)
                        <tr>
                            <td><span class="formation-type">{{ $formation->type_formation }}</span></td>
                            <td class="fw-semibold">{{ $formation->titre }}</td>
                            <td class="text-muted text-end text-nowrap">Ouverture le {{ \Carbon\Carbon::parse($formation->date_debut)->translatedFormat('d M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection
