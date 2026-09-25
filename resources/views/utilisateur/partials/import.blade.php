{{-- Import de candidats : résumé du dernier import + fenêtre d'import (ouverte par un bouton data-bs-target="#import-candidats").
     @include('utilisateur.partials.import', ['formations' => $formations, 'formationId' => $formationId ?? null]) --}}
@if ($rapport = session('import_rapport'))
  <div class="alert {{ $rapport['erreurs'] ? 'alert-warning' : 'alert-success' }} alert-dismissible">
    <strong>Import · {{ $rapport['formation'] }} :</strong>
    {{ $rapport['crees'] }} créé(s), {{ $rapport['maj'] }} mis à jour{{ $rapport['erreurs'] ? ', ' . count($rapport['erreurs']) . ' erreur(s)' : '' }}.
    @if ($rapport['erreurs'])
      <ul class="mb-0 mt-2 small">
        @foreach (array_slice($rapport['erreurs'], 0, 20) as $erreur)
          <li>{{ $erreur }}</li>
        @endforeach
        @if (count($rapport['erreurs']) > 20)<li>… et {{ count($rapport['erreurs']) - 20 }} autre(s).</li>@endif
      </ul>
    @endif
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
  </div>
@endif

@if ($errors->hasAny(['fichier', 'formation_id']))
  <div class="alert alert-danger">
    <strong>Import impossible :</strong> {{ $errors->first('fichier') ?: $errors->first('formation_id') }}
  </div>
@endif

<div class="modal fade" id="import-candidats" tabindex="-1" aria-labelledby="import-candidats-titre" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content" action="{{ route('import.candidats') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title d-flex align-items-center gap-2" id="import-candidats-titre">
          <span class="material-symbols-rounded">upload_file</span> Importer des candidats
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <label class="form-label" for="import-formation">Formation <span class="text-muted fw-normal">(facultatif)</span></label>
        <select name="formation_id" id="import-formation" class="form-select mb-1">
          <option value="">Selon le fichier (colonne « Formation »)</option>
          @foreach ($formations as $f)
            <option value="{{ $f->id }}" @selected(($formationId ?? null) == $f->id)>{{ $f->type_formation }} · {{ $f->titre }}</option>
          @endforeach
        </select>
        <div class="form-text-soft mb-3">Choisie ici, elle s'applique à toutes les lignes du fichier.</div>

        <label class="form-label" for="import-fichier">Fichier Excel ou CSV</label>
        <input type="file" name="fichier" id="import-fichier" class="form-control mb-2" accept=".xlsx,.xls,.csv" required>

        <div class="form-text-soft">
          Mêmes colonnes que l'export : un fichier exporté peut être réimporté.
          Un candidat déjà présent (même CNE) est mis à jour, pas dupliqué.
          Les pièces jointes ne s'importent pas.
        </div>
        <a href="{{ route('import.modele') }}" class="btn btn-sm btn-light mt-3">
          <span class="material-symbols-rounded">download</span> Télécharger le modèle
        </a>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" class="btn btn-brand">
          <span class="material-symbols-rounded">upload</span> Importer
        </button>
      </div>
    </form>
  </div>
</div>
