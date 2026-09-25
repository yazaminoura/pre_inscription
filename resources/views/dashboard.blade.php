@extends('utilisateur.layouts.app')
@section('title', 'Tableau de bord')

@section('content')
<div class="page-head">
  <div>
    <h2>Bonjour {{ strtok(Auth::user()->name, ' ') }} 👋</h2>
    <p>Voici l'état des préinscriptions au {{ now()->translatedFormat('d F Y') }}.</p>
  </div>
  <a href="{{ route('candidats.index', ['statut' => 'en_attente']) }}" class="btn btn-brand">
    <span class="material-symbols-rounded">pending_actions</span> Traiter les dossiers en attente
  </a>
</div>

{{-- Chiffres clés --}}
<div class="row g-3 mb-4">
  @php
    $cartes = [
        ['Candidatures', $stats['total'], 'folder_shared', 'var(--brand)', route('candidats.index')],
        ['À traiter', $stats['a_traiter'], 'pending_actions', '#b7791f', route('candidats.index', ['statut' => 'en_attente'])],
        ['Cette semaine', $stats['semaine'], 'trending_up', '#1a73e8', null],
        ['Formations ouvertes', $stats['formations_ouvertes'], 'school', '#2e7d32', route('formations.index')],
    ];
  @endphp
  @foreach ($cartes as [$label, $valeur, $icone, $couleur, $lien])
    <div class="col-6 col-xl-3">
      <a @if ($lien) href="{{ $lien }}" @endif class="stat-card">
        <span class="stat-icon" style="background: color-mix(in srgb, {{ $couleur }} 12%, white); color: {{ $couleur }};">
          <span class="material-symbols-rounded">{{ $icone }}</span>
        </span>
        <span>
          <span class="stat-value d-block">{{ $valeur }}</span>
          <span class="stat-label">{{ $label }}</span>
        </span>
      </a>
    </div>
  @endforeach
</div>

<div class="row g-4 mb-4">
  {{-- Évolution --}}
  <div class="col-xl-8">
    <div class="panel h-100">
      <div class="panel-head">
        <h3><span class="material-symbols-rounded">show_chart</span> Candidatures sur 14 jours</h3>
        <span class="text-muted small">{{ $stats['aujourdhui'] }} aujourd'hui</span>
      </div>
      <div class="panel-body">
        <canvas id="courbe" height="110"></canvas>
      </div>
    </div>
  </div>

  {{-- Répartition par statut --}}
  <div class="col-xl-4">
    <div class="panel h-100">
      <div class="panel-head"><h3><span class="material-symbols-rounded">donut_small</span> Par statut</h3></div>
      <div class="panel-body">
        @foreach (\App\Models\Inscription::STATUTS as $cle => [$libelle, $couleur])
          @php $n = $parStatut[$cle] ?? 0; $pct = $stats['total'] ? round($n * 100 / $stats['total']) : 0; @endphp
          <a href="{{ route('candidats.index', ['statut' => $cle]) }}" class="d-block text-decoration-none text-body mb-3">
            <div class="d-flex justify-content-between small mb-1">
              <span class="fw-semibold">{{ $libelle }}</span>
              <span class="text-muted">{{ $n }} · {{ $pct }} %</span>
            </div>
            <div class="progress progress-thin">
              <div class="progress-bar" style="width: {{ $pct }}%; background: {{ $couleur }};"></div>
            </div>
          </a>
        @endforeach
      </div>
    </div>
  </div>
</div>

<div class="row g-4">
  {{-- Dernières candidatures --}}
  <div class="col-xl-7">
    <div class="panel h-100">
      <div class="panel-head">
        <h3><span class="material-symbols-rounded">schedule</span> Dernières candidatures</h3>
        <a href="{{ route('candidats.index') }}" class="small fw-semibold text-decoration-none">Tout voir</a>
      </div>
      @if ($recentes->isEmpty())
        <div class="empty-state"><span class="material-symbols-rounded">inbox</span>Aucune candidature pour le moment.</div>
      @else
        <div class="table-responsive">
          <table class="table table-clean">
            <tbody>
              @foreach ($recentes as $inscription)
                <tr class="row-link" data-href="{{ route('candidats.show', $inscription->candidat) }}">
                  <td>
                    <div class="person">
                      @include('utilisateur.partials.avatar', ['candidat' => $inscription->candidat, 'size' => 36])
                      <div>
                        <div class="person-name">{{ $inscription->candidat->nom }} {{ $inscription->candidat->prenom }}</div>
                        <div class="person-sub">{{ $inscription->formation->titre ?? '' }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="text-muted small text-nowrap">{{ $inscription->created_at?->diffForHumans() }}</td>
                  <td class="text-end">@include('utilisateur.partials.statut', ['inscription' => $inscription])</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>

  {{-- Par formation --}}
  <div class="col-xl-5">
    <div class="panel h-100">
      <div class="panel-head">
        <h3><span class="material-symbols-rounded">school</span> Par formation</h3>
        <a href="{{ route('formation-stats') }}" class="small fw-semibold text-decoration-none">Statistiques</a>
      </div>
      <div class="panel-body">
        @forelse ($formations as $f)
          <a href="{{ route('candidats.index', ['formation' => $f->id]) }}" class="d-flex align-items-center gap-3 text-decoration-none text-body mb-3">
            <span class="stat-icon" style="width: 38px; height: 38px; background: var(--brand-50); color: var(--brand); font-weight: 800; font-size: .8rem;">{{ mb_strtoupper(mb_substr($f->type_formation, 0, 1)) }}</span>
            <span class="flex-grow-1" style="min-width: 0;">
              <span class="d-block fw-semibold text-truncate">{{ $f->titre }}</span>
              <span class="person-sub">{{ $f->acceptees_count }} acceptée(s) · {{ $f->en_attente_count }} à traiter</span>
            </span>
            <span class="fw-bold">{{ $f->inscriptions_count }}</span>
          </a>
        @empty
          <div class="text-muted small">Aucune formation. <a href="{{ route('formations.create') }}">Créer une formation</a></div>
        @endforelse
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
  (function () {
    const brand = getComputedStyle(document.documentElement).getPropertyValue('--brand').trim() || '#096a9b';
    const data = @json($courbe);
    new Chart(document.getElementById('courbe'), {
      type: 'bar',
      data: {
        labels: data.map(d => d.label),
        datasets: [{ data: data.map(d => d.total), backgroundColor: brand, borderRadius: 6, maxBarThickness: 28 }]
      },
      options: {
        plugins: { legend: { display: false } },
        scales: {
          y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#eef2f5' } },
          x: { grid: { display: false } }
        }
      }
    });
  })();
</script>
@endpush
