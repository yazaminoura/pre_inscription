<div>
  @section('title', 'Statistiques & exports')

  <div class="page-head">
    <div>
      <h2>Statistiques & exports</h2>
      <p>Répartition des candidatures par formation et par statut. L'export Excel contient tout le dossier de chaque candidat.</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
      <button type="button" class="btn btn-light border" data-bs-toggle="modal" data-bs-target="#import-candidats">
        <span class="material-symbols-rounded">upload</span> Importer
      </button>
      <a href="{{ route('export.tout') }}" class="btn btn-brand">
        <span class="material-symbols-rounded">download</span> Exporter tout
      </a>
    </div>
  </div>

  @include('utilisateur.partials.import', ['formations' => $formations])

  <div class="d-flex flex-wrap gap-3 mb-3 small">
    @foreach (\App\Models\Inscription::STATUTS as $cle => [$libelle, $couleur])
      <span class="d-inline-flex align-items-center gap-1"><span style="width: 10px; height: 10px; border-radius: 3px; background: {{ $couleur }};"></span> {{ $libelle }}</span>
    @endforeach
  </div>

  <div class="row g-3 align-items-start">
    @forelse ($formations as $formation)
      @php $repartition = $parStatut[$formation->id] ?? collect(); $total = $formation->inscriptions_count; @endphp
      <div class="col-lg-6">
        <div class="panel">
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

            <div class="d-flex gap-2 align-items-center">
              <a href="{{ route('candidats.index', ['formation' => $formation->id]) }}" class="btn btn-sm btn-light">
                <span class="material-symbols-rounded">list</span> Candidatures
              </a>
              @if ($total)
                <a href="{{ route('export.candidats', $formation->id) }}" class="btn btn-sm btn-soft">
                  <span class="material-symbols-rounded">download</span> Export Excel
                </a>
              @endif
              {{-- Affiche / masque le détail par statut --}}
              <button type="button" class="btn-detail ms-auto" aria-expanded="false" aria-controls="detail-{{ $formation->id }}"
                      title="Détail par statut" aria-label="Détail par statut"
                      onclick="const ouvert = this.getAttribute('aria-expanded') === 'true'; this.setAttribute('aria-expanded', !ouvert); document.getElementById('detail-{{ $formation->id }}').hidden = ouvert;">
                <span class="material-symbols-rounded">expand_more</span>
              </button>
            </div>

            {{-- Compteurs par statut : une tuile chacun, cliquable vers la liste filtrée --}}
            <div class="statut-grille mt-3" id="detail-{{ $formation->id }}" hidden>
              @foreach (\App\Models\Inscription::STATUTS as $cle => [$libelle, $couleur])
                @php $n = $repartition[$cle] ?? 0; @endphp
                <a href="{{ route('candidats.index', ['formation' => $formation->id, 'statut' => $cle]) }}" class="statut-tuile {{ $n ? '' : 'vide' }}">
                  <span class="statut-tuile-n">{{ $n }}</span>
                  <span class="statut-tuile-libelle"><span class="statut-point" style="background: {{ $couleur }};"></span>{{ $libelle }}</span>
                </a>
              @endforeach
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
