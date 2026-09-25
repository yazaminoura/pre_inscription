@extends('utilisateur.layouts.app')
@section('title', 'Établissement')

@section('content')
<div class="page-head">
  <div>
    <h2>Établissement</h2>
    <p>Ces informations apparaissent sur le site public, dans les emails et dans l'administration.</p>
  </div>
  <a href="{{ route('accueil') }}" target="_blank" class="btn btn-soft">
    <span class="material-symbols-rounded">visibility</span> Voir le site public
  </a>
</div>

@if ($errors->any())
  <div class="alert alert-danger">Merci de corriger les champs signalés.</div>
@endif

<form action="{{ route('etablissement.update') }}" method="POST" enctype="multipart/form-data">
  @csrf
  @method('PUT')

  <div class="row g-4">
    <div class="col-xl-8">
      <div class="panel mb-4">
        <div class="panel-head"><h3><span class="material-symbols-rounded">apartment</span> Identité</h3></div>
        <div class="panel-body">
          <div class="row g-3">
            <x-champ name="nom" label="Nom de l'établissement" :value="$etablissement->nom" required placeholder="Ex. : Faculté des Sciences" />
            <x-champ name="sigle" label="Sigle" :value="$etablissement->sigle" placeholder="Ex. : FS" aide="Utilisé dans les titres courts. Facultatif." />
            <x-champ name="slogan" label="Accroche" :value="$etablissement->slogan" col="col-12" placeholder="Une phrase affichée en haut de la page d'accueil" />
            <x-champ name="presentation" label="Présentation" type="textarea" :value="$etablissement->presentation" col="col-12" rows="6"
                     placeholder="Quelques lignes sur l'établissement : histoire, points forts, équipements…" aide="Affichée sur la page d'accueil, rubrique « L'établissement »." />
          </div>
          @include('utilisateur.partials.traductions', ['modele' => $etablissement, 'champs' => [
            'nom' => ["Nom de l'établissement", 'text'],
            'slogan' => ['Accroche', 'text'],
            'presentation' => ['Présentation', 'textarea'],
          ]])
        </div>
      </div>

      <div class="panel">
        <div class="panel-head"><h3><span class="material-symbols-rounded">contact_mail</span> Coordonnées</h3></div>
        <div class="panel-body">
          <div class="row g-3">
            <x-champ name="adresse" label="Adresse" :value="$etablissement->adresse" col="col-12" />
            <x-champ name="ville" label="Ville" :value="$etablissement->ville" />
            <x-champ name="pays" label="Pays" :value="$etablissement->pays" />
            <x-champ name="telephone" label="Téléphone" type="tel" :value="$etablissement->telephone" />
            <x-champ name="email" label="Email de contact" type="email" :value="$etablissement->email" />
            <x-champ name="site" label="Site web" type="url" :value="$etablissement->site" col="col-12" placeholder="https://" />
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-4">
      <div class="panel mb-4">
        <div class="panel-head"><h3><span class="material-symbols-rounded">palette</span> Apparence</h3></div>
        <div class="panel-body">
          <label class="form-label">Logo</label>
          <div class="d-flex align-items-center justify-content-center mb-2 p-3 rounded-3" style="background: var(--bg); min-height: 120px;">
            <img src="{{ asset(config('etablissement.logo')) }}" alt="" style="max-height: 100px; max-width: 100%;">
          </div>
          <input type="file" name="logo" accept="image/png,image/jpeg,image/webp,image/svg+xml" class="form-control @error('logo') is-invalid @enderror">
          @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
          <div class="form-text-soft">PNG, JPG, WEBP ou SVG · 2 Mo max. Fond transparent ou blanc de préférence.</div>
          @if ($etablissement->logo)
            <div class="form-check mt-2">
              <input class="form-check-input" type="checkbox" name="retirer_logo" value="1" id="retirer_logo">
              <label class="form-check-label small" for="retirer_logo">Revenir au logo par défaut</label>
            </div>
          @endif

          <label class="form-label mt-4" for="couleur">Couleur principale</label>
          <div class="d-flex gap-2 align-items-center">
            <input type="color" id="couleur" name="couleur" value="{{ old('couleur', $etablissement->couleur ?: config('etablissement.couleur')) }}" class="form-control form-control-color" style="width: 56px;">
            <span class="form-text-soft m-0">Boutons, en-têtes et liens. Choisissez une couleur foncée pour que le texte blanc reste lisible.</span>
          </div>
          @error('couleur')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
      </div>
    </div>
  </div>

  <x-barre-enregistrer />
</form>

{{-- En dehors du formulaire principal : un formulaire ne peut pas en contenir un autre --}}
<div class="row">
  <div class="col-xl-4 offset-xl-8">
    <div class="panel">
      <div class="panel-head"><h3><span class="material-symbols-rounded">mail</span> Emails</h3></div>
      <div class="panel-body">
        @if (config('mail.default') === 'log')
          <div class="alert alert-warning small py-2">Mode <strong>log</strong> : les emails sont écrits dans le journal, pas envoyés aux candidats. Configurez le serveur SMTP dans le fichier <code>.env</code>.</div>
        @else
          <p class="small text-muted">Envoi via <strong>{{ config('mail.default') }}</strong> ({{ config('mail.mailers.smtp.host') }}), expéditeur <strong>{{ config('mail.from.address') }}</strong>.</p>
        @endif
        @if (session('email_test'))
          <div class="alert {{ session('email_test.ok') ? 'alert-success' : 'alert-danger' }} small py-2">{{ session('email_test.message') }}</div>
        @endif
        <form action="{{ route('etablissement.email-test') }}" method="POST">
          @csrf
          <button class="btn btn-soft w-100 justify-content-center">
            <span class="material-symbols-rounded">send</span> M'envoyer un email de test
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
