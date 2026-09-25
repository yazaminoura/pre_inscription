{{-- Formulaire commun création / modification d'une formation --}}
@php $formation = $formation ?? null; @endphp
<input type="hidden" name="user_id" value="{{ $formation->user_id ?? auth()->id() }}">

<div class="row g-3">
  <div class="col-md-6">
    <label for="type_formation" class="form-label">Type <span class="text-danger">*</span></label>
    <select name="type_formation" id="type_formation" class="form-select @error('type_formation') is-invalid @enderror" required>
      <option value="">Choisir…</option>
      @foreach (config('etablissement.types_formation') as $type)
        <option value="{{ $type }}" @selected(old('type_formation', $formation->type_formation ?? '') === $type)>{{ $type }}</option>
      @endforeach
    </select>
    @error('type_formation')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>

  <div class="col-md-6">
    <label for="titre" class="form-label">Intitulé <span class="text-danger">*</span></label>
    <input type="text" name="titre" id="titre" class="form-control @error('titre') is-invalid @enderror"
           value="{{ old('titre', $formation->titre ?? '') }}" placeholder="Ex. : Master Génie Logiciel" required>
    @error('titre')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>

  <div class="col-md-6">
    <label for="date_debut" class="form-label">Ouverture des préinscriptions <span class="text-danger">*</span></label>
    <input type="date" name="date_debut" id="date_debut" class="form-control @error('date_debut') is-invalid @enderror"
           value="{{ old('date_debut', $formation->date_debut ?? '') }}" required>
    @error('date_debut')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>

  <div class="col-md-6">
    <label for="date_fin" class="form-label">Clôture des préinscriptions <span class="text-danger">*</span></label>
    <input type="date" name="date_fin" id="date_fin" class="form-control @error('date_fin') is-invalid @enderror"
           value="{{ old('date_fin', $formation->date_fin ?? '') }}" required>
    @error('date_fin')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
</div>

<div class="fieldset-title mt-4">Présentation sur le site public <span class="optional">· facultatif, mais conseillé</span></div>
<div class="row g-3">
  <x-champ name="duree" label="Durée" :value="$formation->duree ?? ''" placeholder="Ex. : 2 ans (4 semestres)" />
  <x-champ name="places" label="Nombre de places" type="number" min="1" :value="$formation->places ?? ''" placeholder="Ex. : 40" />
  <x-champ name="description" label="Description" type="textarea" rows="4" col="col-12" :value="$formation->description ?? ''"
           placeholder="Objectifs de la formation, contenu, points forts…" />
  <x-champ name="conditions_acces" label="Conditions d'accès" type="textarea" rows="3" :value="$formation->conditions_acces ?? ''"
           placeholder="Diplômes acceptés, mentions, prérequis…" aide="Une condition par ligne." />
  <x-champ name="modalites_selection" label="Modalités de sélection" type="textarea" rows="3" :value="$formation->modalites_selection ?? ''"
           placeholder="Étude du dossier, test écrit, entretien…" aide="Une étape par ligne." />
  <x-champ name="debouches" label="Débouchés" type="textarea" rows="3" col="col-12" :value="$formation->debouches ?? ''"
           placeholder="Métiers visés, poursuite d'études…" aide="Un débouché par ligne." />
</div>

@include('utilisateur.partials.traductions', ['modele' => $formation, 'champs' => [
  'titre' => ['Intitulé', 'text'],
  'duree' => ['Durée', 'text'],
  'description' => ['Description', 'textarea'],
  'conditions_acces' => ["Conditions d'accès", 'textarea'],
  'modalites_selection' => ['Modalités de sélection', 'textarea'],
  'debouches' => ['Débouchés', 'textarea'],
]])

<div class="d-flex gap-2 justify-content-end mt-4">
  <a href="{{ route('formations.index') }}" class="btn btn-light">Annuler</a>
  <button type="submit" class="btn btn-brand">
    <span class="material-symbols-rounded">save</span> Enregistrer
  </button>
</div>
