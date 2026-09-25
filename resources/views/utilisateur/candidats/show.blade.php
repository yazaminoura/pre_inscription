@extends('utilisateur.layouts.app')
@section('title', 'Dossier candidat')

@php
  $doc = fn ($path) => $path ? route('documents.voir', $path) : null;
  $documents = array_filter([
      ['CV', 'description', $candidat->CV],
      ['Demande', 'draft', $candidat->demande],
      ['Pièce d\'identité', 'badge', $candidat->scan_cartid],
      ['Baccalauréat', 'workspace_premium', $candidat->scan_bac],
  ], fn ($d) => !empty($d[2]));
  $diplome = $candidat->diplomes->first();
@endphp

@section('content')
{{-- Navigation : retour à la liste + dossier précédent / suivant (flèches ← → du clavier) --}}
<div class="d-flex flex-wrap align-items-center gap-2 mb-3">
  <a href="{{ route('candidats.index', $navigation['filtres']) }}" class="text-decoration-none fw-semibold me-auto">
    <span class="material-symbols-rounded">arrow_back</span> Retour aux candidatures
    @if (!empty($navigation['filtres']['statut']))
      <span class="text-muted fw-normal small">({{ \App\Models\Inscription::STATUTS[$navigation['filtres']['statut']][0] }})</span>
    @endif
  </a>
  @if ($navigation['position'])
    <span class="text-muted small">Dossier {{ $navigation['position'] }} sur {{ $navigation['total'] }}</span>
    <div class="btn-group">
      <a id="dossier-precedent" class="btn btn-sm btn-light border {{ $navigation['precedent'] ? '' : 'disabled' }}"
         href="{{ $navigation['precedent'] ? route('candidats.show', ['candidat' => $navigation['precedent']] + $navigation['filtres']) : '#' }}" title="Dossier précédent (←)">
        <span class="material-symbols-rounded">chevron_left</span> Précédent
      </a>
      <a id="dossier-suivant" class="btn btn-sm btn-light border {{ $navigation['suivant'] ? '' : 'disabled' }}"
         href="{{ $navigation['suivant'] ? route('candidats.show', ['candidat' => $navigation['suivant']] + $navigation['filtres']) : '#' }}" title="Dossier suivant (→)">
        Suivant <span class="material-symbols-rounded">chevron_right</span>
      </a>
    </div>
  @endif
</div>

{{-- En-tête du dossier --}}
<div class="panel mb-4">
  <div class="panel-body d-flex flex-wrap align-items-center gap-3">
    @include('utilisateur.partials.avatar', ['candidat' => $candidat, 'size' => 72])
    <div class="flex-grow-1">
      <h2 class="h4 fw-bold mb-1">{{ $candidat->nom }} {{ $candidat->prenom }}
        @if ($candidat->nom_ar || $candidat->prenom_ar)
          <span class="text-muted fw-normal ms-2" dir="rtl">{{ $candidat->nom_ar }} {{ $candidat->prenom_ar }}</span>
        @endif
      </h2>
      <div class="d-flex flex-wrap gap-3 text-muted small">
        <span><span class="material-symbols-rounded">mail</span> <a href="mailto:{{ $candidat->email }}">{{ $candidat->email }}</a></span>
        <span><span class="material-symbols-rounded">call</span> <a href="tel:{{ $candidat->telephone_mob }}">{{ $candidat->telephone_mob }}</a></span>
        <span><span class="material-symbols-rounded">location_on</span> {{ $candidat->ville }}, {{ $candidat->pays }}</span>
      </div>
    </div>
    <form action="{{ route('candidats.destroy', $candidat) }}" method="POST">
      @csrf
      @method('DELETE')
      <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDelete({{ $candidat->id }}, this, 'ce dossier')">
        <span class="material-symbols-rounded">delete</span> Supprimer
      </button>
    </form>
  </div>
</div>

<div class="row g-4">
  {{-- Colonne principale --}}
  <div class="col-xl-8">

    <div class="panel mb-4">
      <div class="panel-head"><h3><span class="material-symbols-rounded">person</span> Identité</h3></div>
      <div class="panel-body">
        <dl class="info-grid mb-0">
          <div><dt>CNE</dt><dd>{{ $candidat->CNE }}</dd></div>
          <div><dt>CIN / Passeport</dt><dd>{{ $candidat->CIN }}</dd></div>
          <div><dt>Sexe</dt><dd>{{ $candidat->sexe === 'F' ? 'Femme' : 'Homme' }}</dd></div>
          <div><dt>Date de naissance</dt><dd>{{ \Carbon\Carbon::parse($candidat->date_naissance)->format('d/m/Y') }}</dd></div>
          <div><dt>Lieu de naissance</dt><dd>{{ $candidat->ville_naissance }}, {{ $candidat->pay_naissance }}</dd></div>
          <div><dt>Nationalité</dt><dd>{{ $candidat->nationalite }}</dd></div>
          <div><dt>Province</dt><dd>{{ $candidat->province }}</dd></div>
          <div><dt>Téléphone fixe</dt><dd>{{ $candidat->telephone_fix ?: '—' }}</dd></div>
          <div style="grid-column: 1 / -1;"><dt>Adresse</dt><dd>{{ $candidat->adresse }}</dd></div>
        </dl>
      </div>
    </div>

    <div class="panel mb-4">
      <div class="panel-head"><h3><span class="material-symbols-rounded">school</span> Parcours académique</h3></div>
      <div class="panel-body">
        <div class="timeline-item">
          <h4>Baccalauréat · {{ $candidat->serie_bac }}</h4>
          <div class="text-muted small">Obtenu en {{ $candidat->annee_bac }}</div>
        </div>
        @if ($diplome)
          <div class="timeline-item">
            <h4>Bac+2 · {{ $diplome->type_diplome_bac_2 }} — {{ $diplome->filiere_diplome_bac_2 }}</h4>
            <div class="text-muted small">{{ $diplome->etablissement_bac_2 }} · {{ $diplome->annee_diplome_bac_2 }}
              @if ($diplome->scan_bac_2) · <a href="{{ $doc($diplome->scan_bac_2) }}" target="_blank">voir le diplôme</a>@endif
            </div>
          </div>
          @if ($diplome->type_diplome_bac_3)
            <div class="timeline-item">
              <h4>Bac+3 · {{ $diplome->type_diplome_bac_3 }} — {{ $diplome->filiere_diplome_bac_3 }}</h4>
              <div class="text-muted small">{{ $diplome->etablissement_bac_3 }} · {{ $diplome->annee_diplome_bac_3 }}
                @if ($diplome->scan_bac_3) · <a href="{{ $doc($diplome->scan_bac_3) }}" target="_blank">voir le diplôme</a>@endif
              </div>
            </div>
          @endif
        @endif
      </div>
    </div>

    <div class="row g-4">
      <div class="col-md-6">
        <div class="panel h-100">
          <div class="panel-head"><h3><span class="material-symbols-rounded">business_center</span> Stages</h3><span class="text-muted small">{{ $candidat->stages->count() }}</span></div>
          <div class="panel-body">
            @forelse ($candidat->stages as $stage)
              <div class="timeline-item">
                <h4>{{ $stage->fonction ?: 'Stage' }}</h4>
                <div class="text-muted small">{{ $stage->etablissement }}@if($stage->periode) · {{ $stage->periode }}@endif</div>
                @if ($stage->description)<div class="small mt-1">{{ $stage->description }}</div>@endif
                @if ($stage->attestation)<a href="{{ $doc($stage->attestation) }}" target="_blank" class="small">Attestation</a>@endif
              </div>
            @empty
              <div class="text-muted small">Aucun stage déclaré.</div>
            @endforelse
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="panel h-100">
          <div class="panel-head"><h3><span class="material-symbols-rounded">work</span> Expériences</h3><span class="text-muted small">{{ $candidat->experiences->count() }}</span></div>
          <div class="panel-body">
            @forelse ($candidat->experiences as $exp)
              <div class="timeline-item">
                <h4>{{ $exp->fonction ?: 'Expérience' }}</h4>
                <div class="text-muted small">{{ $exp->etablissement }}@if($exp->periode) · {{ $exp->periode }}@endif</div>
                @if ($exp->description)<div class="small mt-1">{{ $exp->description }}</div>@endif
                @if ($exp->attestation)<a href="{{ $doc($exp->attestation) }}" target="_blank" class="small">Attestation</a>@endif
              </div>
            @empty
              <div class="text-muted small">Aucune expérience déclarée.</div>
            @endforelse
          </div>
        </div>
      </div>
    </div>

    @if ($candidat->attestations->isNotEmpty())
      <div class="panel mt-4">
        <div class="panel-head"><h3><span class="material-symbols-rounded">verified</span> Attestations</h3></div>
        <div class="panel-body">
          @foreach ($candidat->attestations as $att)
            <div class="timeline-item">
              <h4>{{ $att->type_attestation ?: 'Attestation' }}</h4>
              @if ($att->description)<div class="small">{{ $att->description }}</div>@endif
              @if ($att->attestation)<a href="{{ $doc($att->attestation) }}" target="_blank" class="small">Voir le document</a>@endif
            </div>
          @endforeach
        </div>
      </div>
    @endif
  </div>

  {{-- Colonne décision + documents --}}
  <div class="col-xl-4">
    @foreach ($candidat->inscriptions as $inscription)
      <div class="panel mb-4" style="border-top: 4px solid {{ $inscription->statut_color }};">
        <div class="panel-head">
          <h3><span class="material-symbols-rounded">gavel</span> Décision</h3>
          @include('utilisateur.partials.statut', ['inscription' => $inscription])
        </div>
        <div class="panel-body">
          <div class="mb-3">
            <div class="fw-bold">{{ $inscription->formation->titre ?? '—' }}</div>
            <div class="text-muted small">{{ $inscription->formation->type_formation ?? '' }} · Réf. <code class="text-body">{{ $inscription->reference }}</code></div>
            <div class="text-muted small">Déposée le {{ $inscription->created_at?->format('d/m/Y à H:i') }}</div>
            @if ($inscription->statut_at)
              <div class="text-muted small">Dernière décision le {{ $inscription->statut_at->format('d/m/Y à H:i') }}</div>
            @endif
          </div>

          <form action="{{ route('inscriptions.statut', $inscription) }}" method="POST">
            @csrf
            @method('PATCH')
            <label class="form-label">Nouveau statut</label>
            <div class="d-grid gap-2 mb-3">
              @foreach (\App\Models\Inscription::STATUTS as $cle => [$libelle, $couleur])
                <label class="statut-option d-flex align-items-center gap-2 border rounded-3 px-3 py-2 m-0" style="--c: {{ $couleur }};">
                  <input type="radio" name="statut" value="{{ $cle }}" class="form-check-input m-0" @checked($inscription->statut === $cle)>
                  <span class="fw-semibold" style="color: {{ $couleur }};">{{ $libelle }}</span>
                </label>
              @endforeach
            </div>
            <label class="form-label" for="motif-{{ $inscription->id }}">Motif / précision pour le candidat</label>
            <textarea name="motif" id="motif-{{ $inscription->id }}" rows="2" class="form-control mb-2" placeholder="Ex. : relevé de notes manquant (optionnel)">{{ $inscription->motif }}</textarea>
            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" name="notifier" value="1" id="notifier-{{ $inscription->id }}" checked>
              <label class="form-check-label small" for="notifier-{{ $inscription->id }}">Prévenir le candidat par email</label>
            </div>
            <button type="submit" class="btn btn-brand w-100 justify-content-center">
              <span class="material-symbols-rounded">check</span> Enregistrer la décision
            </button>
          </form>

          @if ($inscription->historique->isNotEmpty())
            <div class="fieldset-title mt-4">Historique</div>
            @foreach ($inscription->historique->reverse() as $h)
              <div class="timeline-item">
                {{-- La plus ancienne ligne (affichée en dernier) est le dépôt du dossier --}}
                <h4 style="color: {{ $h->statut_color }};">{{ $loop->last ? 'Dossier déposé' : $h->statut_label }}</h4>
                <div class="text-muted small">
                  {{ $h->created_at?->format('d/m/Y à H:i') }}
                  @if ($h->user) · {{ $h->user->name }}@endif
                  @if ($h->notifie) · <span title="Email envoyé au candidat"><span class="material-symbols-rounded" style="font-size: 15px;">mark_email_read</span> prévenu</span>@endif
                </div>
                @if ($h->motif)<div class="small mt-1">« {{ $h->motif }} »</div>@endif
              </div>
            @endforeach
          @endif
        </div>
      </div>
    @endforeach

    <div class="panel">
      <div class="panel-head"><h3><span class="material-symbols-rounded">folder</span> Documents</h3></div>
      <div class="panel-body d-grid gap-2">
        @forelse ($documents as [$label, $icon, $path])
          <a href="{{ $doc($path) }}" target="_blank" class="doc-link">
            <span class="material-symbols-rounded">{{ $icon }}</span>
            {{ $label }}
            <span class="material-symbols-rounded ms-auto text-muted">open_in_new</span>
          </a>
        @empty
          <div class="text-muted small">Aucun document.</div>
        @endforelse
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // ← / → pour passer au dossier précédent / suivant (sauf pendant la saisie d'un motif)
  document.addEventListener('keydown', function (e) {
    if (e.target.closest('input, textarea, select') || e.altKey || e.ctrlKey || e.metaKey) return;
    const lien = document.getElementById(e.key === 'ArrowLeft' ? 'dossier-precedent' : e.key === 'ArrowRight' ? 'dossier-suivant' : '');
    if (lien && !lien.classList.contains('disabled')) window.location = lien.href;
  });
</script>
@endpush
