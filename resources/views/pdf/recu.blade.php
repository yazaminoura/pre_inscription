<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    @php
        $c = config('etablissement.couleur');
        $f = $inscription->formation;
        $d = $candidat->diplomes->first();
        $oui = fn ($v) => $v ? '✓ ' . __('Fourni') : '—';
    @endphp
    <style>
        @page { margin: 28px 34px; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10.5px; color: #1c2b39; }
        table { width: 100%; border-collapse: collapse; }
        .entete td { vertical-align: middle; }
        .logo { height: 58px; }
        .etab { font-size: 15px; font-weight: bold; color: {{ $c }}; }
        .sous { color: #6a7b8a; font-size: 10px; }
        .titre { margin: 14px 0 10px; padding: 12px 14px; background: {{ $c }}; color: #fff; }
        .titre h1 { margin: 0; font-size: 16px; }
        .titre .ref { font-size: 20px; font-weight: bold; letter-spacing: 1px; }
        h2 { font-size: 11.5px; color: {{ $c }}; text-transform: uppercase; letter-spacing: .5px; border-bottom: 1.5px solid {{ $c }}; padding-bottom: 3px; margin: 14px 0 6px; }
        .grille td { padding: 5px 8px; vertical-align: top; }
        .grille tr:nth-child(odd) td { background: #F8FAFC; }
        .grille tr:nth-child(even) td { background: #FFFFFF; }
        .grille td.l { width: 28%; color: #6a7b8a; }
        .liste td { padding: 5px 8px; vertical-align: top; }
        .liste tr:nth-child(even) td { background: #F8FAFC; }
        .liste th { text-align: left; font-size: 9.5px; color: #6a7b8a; padding: 4px 6px; background: #f3f6f9; }
        .photo { width: 78px; height: 96px; border: 1px solid #E2E8F0; border-radius: 6px; padding: 2px; background: #fff; }
        .note { margin-top: 16px; padding: 10px 12px; background: #F1F5F9; border-radius: 6px; font-size: 9.5px; color: #334155; }
        .note .i { display: inline-block; width: 14px; height: 14px; line-height: 14px; text-align: center; border-radius: 7px; background: #64748B; color: #fff; font-weight: bold; font-size: 9px; margin-right: 6px; }
        .ref-pill { display: inline-block; padding: 5px 12px; border-radius: 14px; background: {{ $c }}; color: #fff; font-size: 14px; font-weight: bold; letter-spacing: 1px; }
        .pied { position: fixed; bottom: -8px; left: 0; right: 0; text-align: center; font-size: 8.5px; color: #9aa8b4; }
        .statut { display: inline-block; padding: 3px 10px; border-radius: 10px; font-weight: bold; {{ $inscription->statut_pastille }} }
    </style>
</head>
<body>
    <table class="entete">
        <tr>
            <td style="width: 90px;">@if ($logo)<img src="{{ $logo }}" class="logo">@endif</td>
            <td>
                <div class="etab">{{ config('etablissement.nom') }}</div>
                <div class="sous">{{ collect([config('etablissement.adresse'), config('etablissement.ville'), config('etablissement.pays')])->filter()->implode(', ') }}</div>
                <div class="sous">{{ collect([config('etablissement.telephone'), config('etablissement.email'), config('etablissement.site')])->filter()->implode(' · ') }}</div>
            </td>
            <td style="text-align: right; width: 170px;">
                <div class="sous" style="margin-bottom: 4px;">{{ __('Référence') }}</div>
                <span class="ref-pill">{{ $inscription->reference }}</span>
                <div class="sous" style="margin-top: 5px;">{{ __('Édité le :date', ['date' => now()->format('d/m/Y H:i')]) }}</div>
            </td>
        </tr>
    </table>

    <div class="titre">
        <table>
            <tr>
                <td><h1>{{ __('Récapitulatif de préinscription') }}</h1>{{ __($f->type_formation) }} · {{ $f->tr('titre') }}</td>
            </tr>
        </table>
    </div>

    <table>
        <tr>
            <td style="vertical-align: top;">
                <h2 style="margin-top: 0;">{{ __('Identité') }}</h2>
                <table class="grille">
                    <tr><td class="l">{{ __('Nom') }}</td><td><strong>{{ $candidat->nom }}</strong> @if ($candidat->nom_ar)<span style="color:#6a7b8a;">({{ $candidat->nom_ar }})</span>@endif</td></tr>
                    <tr><td class="l">{{ __('Prénom') }}</td><td><strong>{{ $candidat->prenom }}</strong> @if ($candidat->prenom_ar)<span style="color:#6a7b8a;">({{ $candidat->prenom_ar }})</span>@endif</td></tr>
                    <tr><td class="l">{{ __('CNE / Code Massar') }}</td><td>{{ $candidat->CNE }}</td></tr>
                    <tr><td class="l">{{ __('CIN ou n° de passeport') }}</td><td>{{ $candidat->CIN }}</td></tr>
                    <tr><td class="l">{{ __('Date de naissance') }}</td><td>{{ \Carbon\Carbon::parse($candidat->date_naissance)->format('d/m/Y') }} · {{ $candidat->ville_naissance }}, {{ $candidat->pay_naissance }}</td></tr>
                    <tr><td class="l">{{ __('Sexe') }}</td><td>{{ $candidat->sexe === 'F' ? __('Femme') : __('Homme') }}</td></tr>
                    <tr><td class="l">{{ __('Nationalité') }}</td><td>{{ $candidat->nationalite }}</td></tr>
                </table>
            </td>
            <td style="width: 100px; text-align: right; vertical-align: top;">
                @if ($photo)<img src="{{ $photo }}" class="photo">@endif
            </td>
        </tr>
    </table>

    <h2>{{ __('Coordonnées') }}</h2>
    <table class="grille">
        <tr><td class="l">{{ __('Email') }}</td><td>{{ $candidat->email }}</td></tr>
        <tr><td class="l">{{ __('Téléphone mobile') }}</td><td>{{ $candidat->telephone_mob }}@if ($candidat->telephone_fix) · {{ $candidat->telephone_fix }}@endif</td></tr>
        <tr><td class="l">{{ __('Adresse') }}</td><td>{{ $candidat->adresse }}, {{ $candidat->ville }}, {{ $candidat->province }}, {{ $candidat->pays }}</td></tr>
    </table>

    <h2>{{ __('Parcours académique') }}</h2>
    <table class="liste">
        <tr><th>{{ __('Diplôme') }}</th><th>{{ __('Filière') }}</th><th>{{ __('Établissement') }}</th><th>{{ __('Année') }}</th></tr>
        <tr><td>{{ __('Baccalauréat') }}</td><td>{{ $candidat->serie_bac }}</td><td>—</td><td>{{ $candidat->annee_bac }}</td></tr>
        @if ($d && $d->type_diplome_bac_2)
            <tr><td>Bac+2 · {{ $d->type_diplome_bac_2 }}</td><td>{{ $d->filiere_diplome_bac_2 }}</td><td>{{ $d->etablissement_bac_2 }}</td><td>{{ $d->annee_diplome_bac_2 }}</td></tr>
        @endif
        @if ($d && $d->type_diplome_bac_3)
            <tr><td>Bac+3 · {{ $d->type_diplome_bac_3 }}</td><td>{{ $d->filiere_diplome_bac_3 }}</td><td>{{ $d->etablissement_bac_3 }}</td><td>{{ $d->annee_diplome_bac_3 }}</td></tr>
        @endif
    </table>

    {{-- toBase() : sans lui, une liste de stages vide reste une collection Eloquent et merge() plante sur les lignes [type, modèle] --}}
    @php $experiences = $candidat->stages->toBase()->map(fn ($s) => [__('Stage'), $s])->merge($candidat->experiences->toBase()->map(fn ($e) => [__('Expérience'), $e])); @endphp
    @if ($experiences->isNotEmpty() || $candidat->attestations->isNotEmpty())
        <h2>{{ __('Expérience') }}</h2>
        <table class="liste">
            @foreach ($experiences as [$type, $e])
                <tr><td style="width: 18%;">{{ $type }}</td><td><strong>{{ $e->fonction }}</strong> · {{ $e->etablissement }}</td><td style="width: 26%;">{{ is_numeric($e->periode) ? $e->periode . ' ' . __('mois') : $e->periode }}</td></tr>
            @endforeach
            @foreach ($candidat->attestations as $a)
                <tr><td>{{ __('Attestation') }}</td><td><strong>{{ $a->type_attestation }}</strong></td><td>{{ $a->description }}</td></tr>
            @endforeach
        </table>
    @endif

    <h2>{{ __('Pièces jointes') }}</h2>
    <table class="grille">
        <tr><td class="l">{{ __('Curriculum vitae (CV)') }}</td><td>{{ $oui($candidat->CV) }}</td><td class="l">{{ __('Lettre de demande') }}</td><td>{{ $oui($candidat->demande) }}</td></tr>
        <tr><td class="l">{{ __("Pièce d'identité") }}</td><td>{{ $oui($candidat->scan_cartid) }}</td><td class="l">{{ __("Photo d'identité") }}</td><td>{{ $oui($candidat->photo) }}</td></tr>
        <tr><td class="l">{{ __('Scan du baccalauréat') }}</td><td>{{ $oui($candidat->scan_bac) }}</td><td class="l">{{ __('Diplômes') }}</td><td>{{ $oui($d?->scan_bac_2 || $d?->scan_bac_3) }}</td></tr>
    </table>

    <h2>{{ __('Suivi') }}</h2>
    <table class="grille">
        <tr><td class="l">{{ __('Déposée le') }}</td><td>{{ $inscription->created_at?->format('d/m/Y H:i') }}</td></tr>
        <tr><td class="l">{{ __('Statut') }}</td><td><span class="statut">{{ __($inscription->statut_label) }}</span></td></tr>
        <tr><td class="l">{{ __('Suivre mon dossier') }}</td><td>{{ route('suivi', ['reference' => $inscription->reference]) }}</td></tr>
    </table>

    <div class="note">
        <span class="i">i</span>{{ __("Ce document récapitule la préinscription déposée en ligne. Il ne vaut pas admission : la décision de l'établissement est communiquée par email et visible sur la page « Suivre mon dossier ».") }}
        <br>{{ __("Je certifie sur l'honneur l'exactitude des informations et des documents fournis.") }}
    </div>

    <div class="pied">{{ config('etablissement.nom') }} · {{ __('Préinscription') }} {{ $inscription->reference }}</div>
</body>
</html>
