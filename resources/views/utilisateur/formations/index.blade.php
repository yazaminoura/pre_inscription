@extends('utilisateur.layouts.app')
@section('title', 'Formations')

@section('content')
<div class="page-head">
  <div>
    <h2>Formations</h2>
    <p>Une formation est visible sur le formulaire public entre sa date d'ouverture et sa date de clôture.</p>
  </div>
  <a href="{{ route('formations.create') }}" class="btn btn-brand">
    <span class="material-symbols-rounded">add</span> Nouvelle formation
  </a>
</div>

<div class="panel">
  @if ($formations->isEmpty())
    <div class="empty-state">
      <span class="material-symbols-rounded">school</span>
      Aucune formation. <a href="{{ route('formations.create') }}">Créez la première</a> pour ouvrir les préinscriptions.
    </div>
  @else
    <div class="table-responsive">
      <table class="table table-clean js-datatable">
        <thead>
          <tr>
            <th>Formation</th>
            <th>Préinscriptions</th>
            <th>État</th>
            <th>Candidatures</th>
            <th class="no-sort text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($formations as $formation)
            @php
              $debut = \Carbon\Carbon::parse($formation->date_debut);
              $fin = \Carbon\Carbon::parse($formation->date_fin);
              [$etat, $couleur] = today()->lt($debut) ? ['À venir', '#1a73e8'] : (today()->gt($fin) ? ['Clôturée', '#64748b'] : ['Ouverte', '#2e7d32']);
            @endphp
            <tr class="row-link" data-href="{{ route('formations.edit', $formation) }}">
              <td>
                <div class="d-flex align-items-center gap-3">
                  <span class="stat-icon" style="width: 38px; height: 38px; background: var(--brand-50); color: var(--brand); font-weight: 800; font-size: .8rem;">{{ $formation->type_formation === 'Master' ? 'M' : 'L' }}</span>
                  <div>
                    <div class="fw-semibold">{{ $formation->titre }}</div>
                    <div class="person-sub">{{ $formation->type_formation }}</div>
                  </div>
                </div>
              </td>
              <td data-order="{{ $debut->timestamp }}" class="text-nowrap">{{ $debut->format('d/m/Y') }} → {{ $fin->format('d/m/Y') }}</td>
              <td><span class="status-badge" style="color: {{ $couleur }}; background: {{ $couleur }}1a;">{{ $etat }}</span></td>
              <td data-order="{{ $formation->inscriptions_count }}">
                <a href="{{ route('candidats.index', ['formation' => $formation->id]) }}" class="fw-semibold text-decoration-none">
                  {{ $formation->inscriptions_count }} <span class="material-symbols-rounded">chevron_right</span>
                </a>
              </td>
              <td class="text-end text-nowrap">
                <a href="{{ route('formations.edit', $formation) }}" class="btn btn-sm btn-icon btn-soft" title="Modifier">
                  <span class="material-symbols-rounded">edit</span>
                </a>
                <form action="{{ route('formations.destroy', $formation) }}" method="POST" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button type="button" class="btn btn-sm btn-icon btn-outline-danger" title="Supprimer"
                          onclick="confirmDelete({{ $formation->id }}, this, 'cette formation{{ $formation->inscriptions_count ? ' et ses ' . $formation->inscriptions_count . ' candidature(s)' : '' }}')">
                    <span class="material-symbols-rounded">delete</span>
                  </button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif
</div>
@endsection
