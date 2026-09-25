@extends('utilisateur.layouts.app')
@section('title', 'Candidatures')

@section('content')
<div class="page-head">
  <div>
    <h2>Candidatures</h2>
    <p>{{ $compteurs->sum() }} dossier(s){{ $formationId ? ' pour cette formation' : '' }} · cliquez sur une ligne pour ouvrir le dossier.</p>
  </div>
  <form method="GET" class="d-flex gap-2 align-items-center">
    @if ($statut)<input type="hidden" name="statut" value="{{ $statut }}">@endif
    <select name="formation" class="form-select" style="min-width: 260px;" onchange="this.form.submit()">
      <option value="">Toutes les formations</option>
      @foreach ($formations as $f)
        <option value="{{ $f->id }}" @selected($formationId == $f->id)>{{ $f->type_formation }} · {{ $f->titre }}</option>
      @endforeach
    </select>
    @if ($formationId)
      <a href="{{ route('export.candidats', $formationId) }}" class="btn btn-soft text-nowrap">
        <span class="material-symbols-rounded">download</span> Excel
      </a>
    @endif
  </form>
</div>

<div class="filter-pills mb-3">
  <a href="{{ route('candidats.index', array_filter(['formation' => $formationId])) }}" class="{{ $statut ? '' : 'active' }}">
    Toutes <span class="n">{{ $compteurs->sum() }}</span>
  </a>
  @foreach (\App\Models\Inscription::STATUTS as $cle => [$libelle, $couleur])
    <a href="{{ route('candidats.index', array_filter(['statut' => $cle, 'formation' => $formationId])) }}" class="{{ $statut === $cle ? 'active' : '' }}">
      {{ $libelle }} <span class="n">{{ $compteurs[$cle] ?? 0 }}</span>
    </a>
  @endforeach
</div>

<div class="panel">
  @if ($inscriptions->isEmpty())
    <div class="empty-state">
      <span class="material-symbols-rounded">inbox</span>
      Aucune candidature pour ce filtre.
    </div>
  @else
    <div class="table-responsive">
      <table class="table table-clean js-datatable">
        <thead>
          <tr>
            <th>Candidat</th>
            <th>Formation</th>
            <th>Référence</th>
            <th>Déposée le</th>
            <th>Statut</th>
            <th class="no-sort text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($inscriptions as $inscription)
            @php $candidat = $inscription->candidat; @endphp
            <tr class="row-link" data-href="{{ route('candidats.show', ['candidat' => $candidat] + $filtres) }}">
              <td>
                <div class="person">
                  @include('utilisateur.partials.avatar', ['candidat' => $candidat])
                  <div>
                    <div class="person-name">{{ $candidat->nom }} {{ $candidat->prenom }}</div>
                    <div class="person-sub">{{ $candidat->email }}</div>
                  </div>
                </div>
              </td>
              <td>
                <div class="fw-semibold">{{ $inscription->formation->titre ?? '—' }}</div>
                <div class="person-sub">{{ $inscription->formation->type_formation ?? '' }}</div>
              </td>
              <td class="text-nowrap"><code class="text-body">{{ $inscription->reference }}</code></td>
              <td data-order="{{ $inscription->created_at?->timestamp }}">{{ $inscription->created_at?->format('d/m/Y') }}</td>
              <td>@include('utilisateur.partials.statut', ['inscription' => $inscription])</td>
              <td class="text-end text-nowrap">
                <a href="{{ route('candidats.show', ['candidat' => $candidat] + $filtres) }}" class="btn btn-soft btn-sm">
                  <span class="material-symbols-rounded">folder_open</span> Dossier
                </a>
                <form action="{{ route('candidats.destroy', $candidat) }}" method="POST" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button type="button" class="btn btn-sm btn-icon btn-outline-danger" title="Supprimer"
                          onclick="confirmDelete({{ $candidat->id }}, this, 'le dossier de {{ addslashes($candidat->prenom . ' ' . $candidat->nom) }}')">
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
