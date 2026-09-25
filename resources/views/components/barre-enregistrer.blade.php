{{-- Barre d'enregistrement collée en bas de l'écran, toujours visible ; elle s'allume dès qu'un champ du formulaire change.
     <x-barre-enregistrer :annuler="route('...')" libelle="Créer le compte" /> à placer en dernier dans le <form>. --}}
@props(['annuler' => null, 'libelle' => 'Enregistrer'])
<div class="barre-enregistrer" data-barre-enregistrer>
  <span class="barre-etat">
    <span class="material-symbols-rounded">edit_note</span> Modifications non enregistrées
  </span>
  @if ($annuler)
    <a href="{{ $annuler }}" class="btn btn-light">Annuler</a>
  @endif
  <button type="submit" class="btn btn-brand">
    <span class="material-symbols-rounded">save</span> {{ $libelle }}
  </button>
</div>
