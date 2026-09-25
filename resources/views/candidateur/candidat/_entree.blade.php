{{-- Un stage / une expérience / une attestation. $i vaut "__i__" dans le modèle JS. --}}
@php
    $p = "{$liste}[{$i}]";
    $fichier = $e['attestation'] ?? null;
@endphp
<div class="repeat-item">
    <button type="button" class="btn btn-sm btn-icon btn-outline-danger remove-item" title="Retirer">
        <span class="material-symbols-rounded">close</span>
    </button>
    <div class="row g-3">
        @if ($liste === 'attestations')
            <x-champ :name="$p . '[type_attestation]'" label="Type d'attestation" :value="$e['type_attestation'] ?? ''" required placeholder="Ex. : TOEIC, DELF, bénévolat…" />
            <x-champ :name="$p . '[description]'" label="Précision" :value="$e['description'] ?? ''" placeholder="Ex. : niveau B2, score 850" />
        @else
            <x-champ :name="$p . '[fonction]'" :label="$liste === 'stages' ? 'Intitulé du stage' : 'Poste occupé'" :value="$e['fonction'] ?? ''" required />
            <x-champ :name="$p . '[etablissement]'" :label="$liste === 'stages' ? 'Organisme d\'accueil' : 'Employeur'" :value="$e['etablissement'] ?? ''" required />
            <x-champ :name="$p . '[periode]'" label="Période" :value="$e['periode'] ?? ''" placeholder="Ex. : juillet - août 2025" />
            <x-champ :name="$p . '[secteur_activite]'" label="Secteur d'activité" :value="$e['secteur_activite'] ?? ''" />
            <x-champ :name="$p . '[description]'" label="Missions" type="textarea" :value="$e['description'] ?? ''" col="col-12" placeholder="En une ou deux phrases" />
        @endif
        <x-champ :name="$p . '[attestation]'" label="Justificatif" type="file" accept=".pdf,.jpg,.jpeg,.png" col="col-12" aide="Facultatif · PDF, JPG ou PNG.">
            @if ($fichier)
                <input type="hidden" name="{{ $p }}[attestation_actuelle]" value="{{ $fichier }}">
                <span class="file-kept"><span class="material-symbols-rounded">check_circle</span> Justificatif déjà envoyé</span>
            @endif
        </x-champ>
    </div>
</div>
