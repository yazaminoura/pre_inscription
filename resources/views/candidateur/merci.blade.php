@extends('candidateur.layout.index')
@section('title', __('Préinscription enregistrée'))

@section('content')
<div class="merci-card">
    <div class="merci-icon"><span class="material-symbols-rounded">check</span></div>
    <h1 class="h3 fw-bold" style="color: var(--ok);">{{ __('Préinscription enregistrée') }}</h1>
    @if ($inscription)
        <p class="mb-1">{{ __(':nom, votre dossier pour', ['nom' => $inscription->candidat->prenom . ' ' . $inscription->candidat->nom]) }}</p>
        <p class="fw-bold mb-3">{{ __($inscription->formation->type_formation) }} · {{ $inscription->formation->tr('titre') }}</p>
    @endif
    <p class="text-muted mb-1">{{ __('Conservez votre numéro de référence :') }}</p>
    <div class="merci-ref" dir="ltr">{{ $inscription->reference ?? session('inscription_ok') }}</div>
    <p class="text-muted small">{{ __('Un récapitulatif a été envoyé à :email.', ['email' => $inscription->candidat->email ?? '']) }}
        {{ __("Rappelez cette référence pour tout échange avec l'établissement.") }}</p>
    @if ($inscription)
        <div class="d-grid gap-2 my-3" style="max-width: 360px; margin-inline: auto;">
            <a href="{{ route('candidat.recu', $inscription->reference) }}" class="btn btn-brand justify-content-center py-2" id="lien-recu">
                <span class="material-symbols-rounded">download</span> {{ __('Télécharger mon récapitulatif (PDF)') }}
            </a>
            <span class="small text-muted">{{ __('Le téléchargement de votre récapitulatif démarre automatiquement.') }}</span>
        </div>
    @endif
    <a href="{{ route('suivi', ['reference' => $inscription->reference ?? session('inscription_ok')]) }}" class="btn btn-soft mt-2">
        <span class="material-symbols-rounded">travel_explore</span> {{ __('Suivre mon dossier') }}
    </a>
    <a href="{{ route('accueil') }}" class="btn btn-light mt-2">
        <span class="material-symbols-rounded flip">arrow_back</span> {{ __('Retour aux formations') }}
    </a>
</div>
@endsection

@if ($inscription)
    @push('scripts')
    <script>
        // Téléchargement automatique du récapitulatif, une seule fois (pas à chaque rechargement de la page)
        (function () {
            const cle = 'recu-' + @json($inscription->reference);
            try {
                if (sessionStorage.getItem(cle)) return;
                sessionStorage.setItem(cle, '1');
            } catch (e) {}
            const cadre = document.createElement('iframe');
            cadre.hidden = true;
            cadre.src = document.getElementById('lien-recu').href;
            document.body.appendChild(cadre);
        })();
    </script>
    @endpush
@endif
