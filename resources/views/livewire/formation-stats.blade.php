<div>
  @section('title', 'Statistiques & exports')

  <div class="page-head">
    <div>
      <h2>Statistiques & exports</h2>
      <p>Répartition des candidatures par formation et par statut. L'export Excel contient tout le dossier de chaque candidat.</p>
    </div>
  </div>

  <div class="d-flex flex-wrap gap-3 mb-3 small">
    @foreach (\App\Models\Inscription::STATUTS as $cle => [$libelle, $couleur])
      <span class="d-inline-flex align-items-center gap-1"><span style="width: 10px; height: 10px; border-radius: 3px; background: {{ $couleur }};"></span> {{ $libelle }}</span>
    @endforeach
  </div>

  <div class="row g-3">
    @forelse ($formations as $formation)
      @php $repartition = $parStatut[$formation->id] ?? collect(); $total = $formation->inscriptions_count; @endphp
      <div class="col-lg-6">
        <div class="panel h-100">
          <div class="panel-body">
            <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
              <div style="min-width: 0;">
                <div class="person-sub">{{ $formation->type_formation }} · {{ \Carbon\Carbon::parse($formation->date_debut)->format('Y') }}/{{ \Carbon\Carbon::parse($formation->date_fin)->addYear()->format('Y') }}</div>
                <div class="fw-bold text-truncate">{{ $formation->titre }}</div>
              </div>
              <div class="text-end">
                <div class="stat-value">{{ $total }}</div>
                <div class="stat-label">candidature(s)</div>
              </div>
            </div>

            <div class="progress progress-thin mb-3" style="height: 12px;">
              @foreach (\App\Models\Inscription::STATUTS as $cle => [$libelle, $couleur])
                @php $n = $repartition[$cle] ?? 0; @endphp
                @if ($n)
                  <div class="progress-bar" style="width: {{ $n * 100 / $total }}%; background: {{ $couleur }}; border-radius: 0;" title="{{ $libelle }} : {{ $n }}"></div>
                @endif
              @endforeach
            </div>

            <div class="d-flex flex-wrap gap-3 small mb-3">
              @foreach (\App\Models\Inscription::STATUTS as $cle => [$libelle, $couleur])
                <span><strong style="color: {{ $couleur }};">{{ $repartition[$cle] ?? 0 }}</strong> {{ mb_strtolower($libelle) }}</span>
              @endforeach
            </div>

            <div class="d-flex gap-2">
              <a href="{{ route('candidats.index', ['formation' => $formation->id]) }}" class="btn btn-sm btn-light">
                <span class="material-symbols-rounded">list</span> Candidatures
              </a>
              @if ($total)
                <a href="{{ route('export.candidats', $formation->id) }}" class="btn btn-sm btn-soft">
                  <span class="material-symbols-rounded">download</span> Export Excel
                </a>
              @endif
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12">
        <div class="panel"><div class="empty-state"><span class="material-symbols-rounded">insights</span>Aucune formation pour le moment.</div></div>
      </div>
    @endforelse
  </div>
</div>
