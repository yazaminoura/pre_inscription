@extends('candidateur.layout.index')
@section('title', 'Préinscription enregistrée')

@section('content')
<div class="merci-card">
    <div class="merci-icon"><span class="material-symbols-rounded">check</span></div>
    <h1 class="h3 fw-bold" style="color: var(--ok);">Préinscription enregistrée</h1>
    @if ($inscription)
        <p class="mb-1">{{ $inscription->candidat->prenom }} {{ $inscription->candidat->nom }}, votre dossier pour</p>
        <p class="fw-bold mb-3">{{ $inscription->formation->type_formation }} · {{ $inscription->formation->titre }}</p>
    @endif
    <p class="text-muted mb-1">Conservez votre numéro de référence :</p>
    <div class="merci-ref">{{ $inscription->reference ?? session('inscription_ok') }}</div>
    <p class="text-muted small">Un récapitulatif a été envoyé à <strong>{{ $inscription->candidat->email ?? 'votre adresse email' }}</strong>.
        Rappelez cette référence pour tout échange avec l'établissement.</p>
    <a href="{{ route('accueil') }}" class="btn btn-soft mt-2">
        <span class="material-symbols-rounded">arrow_back</span> Retour aux formations
    </a>
</div>
@endsection
