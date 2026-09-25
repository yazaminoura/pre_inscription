@extends('candidateur.layout.index')
@section('title', __('Suivre mon dossier'))

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">

        @if (!$inscription)
            <div class="form-card">
                <div class="form-card-head">
                    <h2>{{ __('Suivre mon dossier') }}</h2>
                    <p>{{ __("Entrez la référence reçue à la fin de votre préinscription et l'email utilisé.") }}</p>
                </div>
                <form action="{{ route('suivi.consulter') }}" method="POST">
                    @csrf
                    <div class="form-card-body">
                        @error('reference')
                            <div class="alert alert-danger d-flex gap-2 align-items-center"><span class="material-symbols-rounded">error</span> {{ $message }}</div>
                        @enderror
                        <div class="row g-3">
                            <x-champ name="reference" :label="__('Référence')" :value="$reference" required placeholder="PI{{ date('Y') }}-00012" autocomplete="off" dir="ltr" />
                            <x-champ name="email" :label="__('Email')" type="email" required autocomplete="email" />
                        </div>
                    </div>
                    <div class="form-card-foot">
                        <a href="{{ route('accueil') }}" class="btn btn-light"><span class="material-symbols-rounded flip">arrow_back</span> {{ __('Formations') }}</a>
                        <button type="submit" class="btn btn-brand"><span class="material-symbols-rounded">search</span> {{ __('Voir mon dossier') }}</button>
                    </div>
                </form>
            </div>
        @else
            <div class="form-card" style="border-top: 5px solid {{ $inscription->statut_color }};">
                <div class="form-card-head d-flex flex-wrap justify-content-between align-items-start gap-2">
                    <div>
                        <p class="m-0">{{ __('Dossier') }} <span dir="ltr">{{ $inscription->reference }}</span></p>
                        <h2>{{ $inscription->formation->tr('titre') }}</h2>
                        <p class="m-0">{{ __($inscription->formation->type_formation) }} · {{ $inscription->candidat->prenom }} {{ $inscription->candidat->nom }}</p>
                    </div>
                    <span class="status-badge" style="font-size: .95rem; color: {{ $inscription->statut_color }}; background: {{ $inscription->statut_color }}1a;">{{ __($inscription->statut_label) }}</span>
                </div>
                <div class="form-card-body">
                    <p class="mb-3" style="font-size: 1.05rem;">{{ __($message) }}</p>
                    @if ($inscription->motif)
                        <div class="p-3 mb-4 rounded-3" style="background: var(--bg); border-inline-start: 4px solid {{ $inscription->statut_color }};">
                            <strong>{{ $inscription->statut === 'incomplet' ? __('Éléments manquants :') : __("Précision de l'établissement :") }}</strong><br>{{ $inscription->motif }}
                        </div>
                    @endif

                    <div class="fieldset-title">{{ __('Étapes de votre dossier') }}</div>
                    @foreach ($inscription->historique->reverse() as $h)
                        <div class="timeline-item">
                            <h4 style="color: {{ $h->statut_color }};">{{ $loop->last ? __('Dossier déposé') : __($h->statut_label) }}</h4>
                            <div class="text-muted small">{{ $h->created_at?->translatedFormat('d F Y, H:i') }}</div>
                        </div>
                    @endforeach
                </div>
                <div class="form-card-foot">
                    <a href="{{ route('suivi') }}" class="btn btn-light"><span class="material-symbols-rounded">search</span> {{ __('Autre dossier') }}</a>
                    <a href="{{ route('candidat.recu', $inscription->reference) }}" class="btn btn-brand"><span class="material-symbols-rounded">download</span> {{ __('Télécharger mon récapitulatif (PDF)') }}</a>
                    @if (config('etablissement.email'))
                        <a href="mailto:{{ config('etablissement.email') }}?subject={{ rawurlencode('Dossier ' . $inscription->reference) }}" class="btn btn-soft">
                            <span class="material-symbols-rounded">mail</span> {{ __("Contacter l'établissement") }}
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
