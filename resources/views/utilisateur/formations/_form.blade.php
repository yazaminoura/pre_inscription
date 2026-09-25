{{-- Formulaire commun création / modification d'une formation --}}
@php $formation = $formation ?? null; @endphp
<input type="hidden" name="user_id" value="{{ $formation->user_id ?? auth()->id() }}">

<div class="row g-3">
  <div class="col-md-6">
    <label for="type_formation" class="form-label">Type <span class="text-danger">*</span></label>
    <select name="type_formation" id="type_formation" class="form-select @error('type_formation') is-invalid @enderror" required>
      <option value="">Choisir…</option>
      @foreach (['Licence', 'Master'] as $type)
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

<div class="d-flex gap-2 justify-content-end mt-4">
  <a href="{{ route('formations.index') }}" class="btn btn-light">Annuler</a>
  <button type="submit" class="btn btn-brand">
    <span class="material-symbols-rounded">save</span> Enregistrer
  </button>
</div>
