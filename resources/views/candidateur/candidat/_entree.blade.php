{{-- Un stage / une expérience / une attestation. $i vaut "__i__" dans le modèle JS. --}}
@php
    $p = "{$liste}[{$i}]";
    $fichier = $e['attestation'] ?? null;
    // Libellés contenant une apostrophe : préparés ici, pas dans les attributs du composant
    $lTypeAttestation = __("Type d'attestation");
    $lOrganisme = $liste === 'stages' ? __("Organisme d'accueil") : __('Employeur');
    $lSecteur = __("Secteur d'activité");
    $lFonction = $liste === 'stages' ? __('Intitulé du stage') : __('Poste occupé');
@endphp
<div class="repeat-item">
    <button type="button" class="btn btn-sm btn-icon btn-outline-danger remove-item" title="{{ __('Retirer') }}">
        <span class="material-symbols-rounded">close</span>
    </button>
    <div class="row g-3">
        @if ($liste === 'attestations')
            <x-champ :name="$p . '[type_attestation]'" :label="$lTypeAttestation" :value="$e['type_attestation'] ?? ''" required :placeholder="__('Ex. : TOEIC, DELF, bénévolat…')" />
            <x-champ :name="$p . '[description]'" :label="__('Précision')" :value="$e['description'] ?? ''" :placeholder="__('Ex. : niveau B2, score 850')" />
        @else
            <x-champ :name="$p . '[fonction]'" :label="$lFonction" :value="$e['fonction'] ?? ''" required />
            <x-champ :name="$p . '[etablissement]'" :label="$lOrganisme" :value="$e['etablissement'] ?? ''" required />
            <x-champ :name="$p . '[periode]'" :label="__('Période')" :value="$e['periode'] ?? ''" :placeholder="__('Ex. : juillet - août 2025')" />
            <x-champ :name="$p . '[secteur_activite]'" :label="$lSecteur" :value="$e['secteur_activite'] ?? ''" />
            <x-champ :name="$p . '[description]'" :label="__('Missions')" type="textarea" :value="$e['description'] ?? ''" col="col-12" :placeholder="__('En une ou deux phrases')" />
        @endif
        <x-champ :name="$p . '[attestation]'" :label="__('Justificatif')" type="file" accept=".pdf,.jpg,.jpeg,.png" col="col-12" :aide="__('Facultatif · PDF, JPG ou PNG.')">
            @if ($fichier)
                <input type="hidden" name="{{ $p }}[attestation_actuelle]" value="{{ $fichier }}">
                <span class="file-kept"><span class="material-symbols-rounded">check_circle</span> {{ __('Justificatif déjà envoyé') }}</span>
            @endif
        </x-champ>
    </div>
</div>
