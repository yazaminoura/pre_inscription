@extends('candidateur.layout.index')
@section('title', __('Préinscription enregistrée'))

@php
    $reference = $inscription->reference ?? session('inscription_ok');
    $suite = [
        ['download_done', __('Récapitulatif téléchargé'), __('Gardez le PDF : il contient toutes les informations de votre dossier.'), 'fait'],
        ['mark_email_read', __('Email de confirmation'), __('Envoyé à :email. Pensez à vérifier les courriers indésirables.', ['email' => $inscription->candidat->email ?? '']), 'fait'],
        ['fact_check', __('Étude du dossier'), __("L'établissement examine votre candidature."), 'encours'],
        ['campaign', __('Décision'), __('Vous serez prévenu(e) par email, et la décision sera visible sur « Suivre mon dossier ».'), ''],
    ];
@endphp

@section('content')
<div class="thanks">
    <section class="thanks-hero">
        <div class="thanks-check">
            <svg viewBox="0 0 52 52" aria-hidden="true"><circle cx="26" cy="26" r="24"/><path d="M15 27l7 7 15-16"/></svg>
        </div>
        <h1>{{ __('Préinscription enregistrée') }}</h1>
        @if ($inscription)
            <p>{{ __('Merci :prenom ! Votre dossier pour :formation a bien été reçu.', ['prenom' => $inscription->candidat->prenom, 'formation' => $inscription->formation->tr('titre')]) }}</p>
        @endif

        <div class="ticket">
            <span class="ticket-label">{{ __('Votre numéro de référence') }}</span>
            <span class="ticket-ref" dir="ltr" id="reference">{{ $reference }}</span>
            <button type="button" class="btn btn-brand ticket-copy" id="copier-reference" data-ok="{{ __('Copié !') }}">
                <span class="material-symbols-rounded">content_copy</span> <span class="texte">{{ __('Copier') }}</span>
            </button>
        </div>
        <p class="thanks-hint">{{ __("Rappelez cette référence pour tout échange avec l'établissement.") }}</p>
        <div class="copy-toast" id="toast-copie" role="status" aria-live="polite"><span class="material-symbols-rounded">check_circle</span> {{ __('Référence copiée dans le presse-papiers') }}</div>
    </section>

    <div class="thanks-grid">
        <section class="thanks-card">
            <h2><span class="material-symbols-rounded">route</span> {{ __('Et maintenant ?') }}</h2>
            <ol class="next-steps">
                @foreach ($suite as [$icone, $titre, $texte, $etat])
                    <li class="{{ $etat }}">
                        <span class="dot"><span class="material-symbols-rounded">{{ $etat === 'fait' ? 'check' : $icone }}</span></span>
                        <div>
                            <h3>{{ $titre }}</h3>
                            <p>{{ $texte }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </section>

        <aside class="thanks-card thanks-side">
            @if ($inscription)
                <span class="fcard-type">{{ __($inscription->formation->type_formation) }}</span>
                <h2 class="mt-2">{{ $inscription->formation->tr('titre') }}</h2>
                <dl class="thanks-facts">
                    <div><dt>{{ __('Candidat') }}</dt><dd>{{ $inscription->candidat->prenom }} {{ $inscription->candidat->nom }}</dd></div>
                    <div><dt>{{ __('Déposée le') }}</dt><dd>{{ $inscription->created_at?->translatedFormat('d F Y, H:i') }}</dd></div>
                    <div><dt>{{ __('Statut') }}</dt><dd><span class="status-pill" style="{{ $inscription->statut_pastille }}">{{ __($inscription->statut_label) }}</span></dd></div>
                </dl>
                <div class="d-grid gap-2">
                    <a href="{{ route('candidat.recu', $inscription->reference) }}" class="btn btn-brand justify-content-center py-2" id="lien-recu">
                        <span class="material-symbols-rounded">download</span> {{ __('Télécharger mon récapitulatif (PDF)') }}
                    </a>
                    <a href="{{ route('suivi', ['reference' => $reference]) }}" class="btn btn-outline-brand justify-content-center">
                        <span class="material-symbols-rounded">travel_explore</span> {{ __('Suivre mon dossier') }}
                    </a>
                    <a href="{{ route('accueil') }}" class="btn btn-light justify-content-center">
                        <span class="material-symbols-rounded flip">arrow_back</span> {{ __('Retour aux formations') }}
                    </a>
                </div>
                <p class="small text-muted mt-3 mb-0"><span class="material-symbols-rounded" style="font-size: 16px;">info</span> {{ __('Le téléchargement de votre récapitulatif démarre automatiquement.') }}</p>
            @endif
        </aside>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Copier la référence
    document.getElementById('copier-reference')?.addEventListener('click', function () {
        const bouton = this;
        const reference = document.getElementById('reference').textContent.trim();

        function confirmer() {
            const texte = bouton.querySelector('.texte');
            const avant = texte.textContent;
            texte.textContent = bouton.dataset.ok;
            bouton.classList.add('ok');
            const toast = document.getElementById('toast-copie');
            toast.classList.add('visible');
            setTimeout(function () { texte.textContent = avant; bouton.classList.remove('ok'); toast.classList.remove('visible'); }, 2200);
        }

        // Méthode de secours quand le presse-papiers moderne est refusé (page non HTTPS, navigateur ancien)
        function copieDeSecours() {
            const zone = document.createElement('textarea');
            zone.value = reference;
            zone.setAttribute('readonly', '');
            zone.style.position = 'fixed';
            zone.style.opacity = '0';
            document.body.appendChild(zone);
            zone.select();
            try { document.execCommand('copy'); } catch (e) {}
            zone.remove();
            confirmer();
        }

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(reference).then(confirmer, copieDeSecours);
        } else {
            copieDeSecours();
        }
    });

    @if ($inscription)
    // Téléchargement automatique du récapitulatif, une seule fois (pas à chaque rechargement)
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
    @endif
</script>
@endpush
