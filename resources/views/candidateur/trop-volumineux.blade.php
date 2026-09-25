@extends('candidateur.layout.index')
@section('title', __('Fichiers trop volumineux'))

@section('content')
<div class="thanks-card text-center mx-auto" style="max-width: 560px; border-top: 4px solid #b7791f;">
    <span class="material-symbols-rounded" style="font-size: 48px; color: #b7791f;">cloud_off</span>
    <h1 class="h4 fw-bold mt-2">{{ __('Fichiers trop volumineux') }}</h1>
    <p class="text-muted">{{ __("Les documents envoyés dépassent la taille autorisée (10 Mo par fichier). Réduisez-les (scan en PDF, photo moins lourde) puis réessayez : les informations déjà saisies sont conservées.") }}</p>
    <a href="javascript:history.back()" class="btn btn-brand"><span class="material-symbols-rounded flip">arrow_back</span> {{ __('Revenir au formulaire') }}</a>
</div>
@endsection
