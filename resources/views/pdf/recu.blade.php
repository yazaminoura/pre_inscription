<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    @php
        $c = config('etablissement.couleur');
        $f = $inscription->formation;
        $d = $candidat->diplomes->first();
        $oui = fn ($v) => $v ? '✓' : '—';
        $mois = fn ($p) => is_numeric($p) ? $p . ' ' . __('mois') : $p;
        // toBase() : une liste de stages vide resterait une collection Eloquent et merge() planterait
        $experiences = $candidat->stages->toBase()->map(fn ($s) => [__('Stage'), $s])
            ->merge($candidat->experiences->toBase()->map(fn ($e) => [__('Expérience'), $e]));
        [$fondStatut, $texteStatut] = \App\Models\Inscription::PASTILLES[$inscription->statut] ?? ['#F1F5F9', '#334155'];
        $adresseEtab = collect([config('etablissement.adresse'), config('etablissement.ville'), config('etablissement.pays')])->filter()->implode(', ');
        $contactEtab = collect([config('etablissement.telephone'), config('etablissement.email'), config('etablissement.site')])->filter()->implode('   ·   ');
    @endphp
    <style>
        @page { margin: 30px 40px 46px; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #1e293b; line-height: 1.35; }
        table { width: 100%; border-collapse: collapse; }

        /* En-tête centré */
        .entete { text-align: center; padding-bottom: 10px; }
        .entete .logo { height: 60px; margin-bottom: 4px; }
        .entete .nom { font-size: 15px; font-weight: bold; color: {{ $c }}; letter-spacing: .3px; }
        .entete .ligne { font-size: 8.5px; color: #64748b; margin-top: 2px; }
        .filet { height: 3px; background: {{ $c }}; margin: 10px auto 0; width: 60px; border-radius: 2px; }

        /* Titre du document */
        .titre { text-align: center; margin: 12px 0 11px; }
        .titre .doc { font-size: 17px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; color: #0f172a; }
        .titre .formation { font-size: 11px; color: #475569; margin-top: 4px; }
        .reference { display: inline-block; margin-top: 8px; padding: 5px 18px; border: 1.5px solid {{ $c }}; border-radius: 6px; }
        .reference .l { font-size: 7.5px; text-transform: uppercase; letter-spacing: 1px; color: #64748b; }
        .reference .v { font-size: 16px; font-weight: bold; color: {{ $c }}; letter-spacing: 1.5px; }

        /* Blocs */
        .bloc { border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 8px; }
        .bloc-titre { background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 5px 12px; font-size: 9px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: {{ $c }}; border-radius: 8px 8px 0 0; }
        .bloc-corps { padding: 6px 12px; }
        .kv td { padding: 3px 0; vertical-align: top; }
        .kv .k { color: #64748b; font-size: 8.5px; width: 22%; }
        .kv .v { font-weight: bold; width: 28%; padding-right: 10px; }
        .photo { width: 74px; height: 90px; border: 1px solid #e2e8f0; border-radius: 6px; padding: 3px; }
        .tab th { text-align: left; font-size: 8px; text-transform: uppercase; letter-spacing: .5px; color: #64748b; padding: 5px 6px; border-bottom: 1px solid #e2e8f0; }
        .tab td { padding: 4px 6px; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
        .tab tr:last-child td { border-bottom: 0; }
        .ok { color: #166534; font-weight: bold; }
        .non { color: #94a3b8; }
        .statut { display: inline-block; padding: 3px 11px; border-radius: 10px; font-weight: bold; background: {{ $fondStatut }}; color: {{ $texteStatut }}; }

        .note { margin-top: 4px; padding: 9px 12px; border-left: 3px solid {{ $c }}; background: #f8fafc; font-size: 8.5px; color: #475569; }

        /* Pied de page : date d'édition, référence, pagination */
        .pied { position: fixed; bottom: -30px; left: 0; right: 0; border-top: 1px solid #e2e8f0; padding-top: 6px; font-size: 7.5px; color: #94a3b8; }
        .pied .num:after { content: counter(page); }
    </style>
</head>
<body>
    <div class="pied">
        <table>
            <tr>
                <td>{{ config('etablissement.nom') }} · {{ __('Préinscription') }} {{ $inscription->reference }}</td>
                <td style="text-align: center;">{{ __('Édité le :date', ['date' => now()->format('d/m/Y H:i')]) }}</td>
                <td style="text-align: right;">{{ __('Page') }} <span class="num"></span></td>
            </tr>
        </table>
    </div>

    {{-- En-tête : logo de l'établissement au centre --}}
    <div class="entete">
        @if ($logo)<img src="{{ $logo }}" class="logo"><br>@endif
        <div class="nom">{{ config('etablissement.nom') }}</div>
        @if ($adresseEtab)<div class="ligne">{{ $adresseEtab }}</div>@endif
        @if ($contactEtab)<div class="ligne">{{ $contactEtab }}</div>@endif
        <div class="filet"></div>
    </div>

    <div class="titre">
        <div class="doc">{{ __('Récapitulatif de préinscription') }}</div>
        <div class="formation">{{ __($f->type_formation) }} · <strong>{{ $f->tr('titre') }}</strong></div>
        <div class="reference">
            <div class="l">{{ __('Référence') }}</div>
            <div class="v">{{ $inscription->reference }}</div>
        </div>
    </div>

    {{-- Identité --}}
    <div class="bloc">
        <div class="bloc-titre">{{ __('Identité') }}</div>
        <div class="bloc-corps">
            <table>
                <tr>
                    <td style="vertical-align: top;">
                        <table class="kv">
                            <tr>
                                <td class="k">{{ __('Nom') }}</td><td class="v">{{ $candidat->nom }}@if ($candidat->nom_ar)<br><span style="font-weight: normal; color: #64748b;">{{ $arabe($candidat->nom_ar) }}</span>@endif</td>
                                <td class="k">{{ __('Prénom') }}</td><td class="v">{{ $candidat->prenom }}@if ($candidat->prenom_ar)<br><span style="font-weight: normal; color: #64748b;">{{ $arabe($candidat->prenom_ar) }}</span>@endif</td>
                            </tr>
                            <tr>
                                <td class="k">{{ __('CNE / Code Massar') }}</td><td class="v">{{ $candidat->CNE }}</td>
                                <td class="k">{{ __('CIN ou n° de passeport') }}</td><td class="v">{{ $candidat->CIN }}</td>
                            </tr>
                            <tr>
                                <td class="k">{{ __('Date de naissance') }}</td><td class="v">{{ \Carbon\Carbon::parse($candidat->date_naissance)->format('d/m/Y') }}</td>
                                <td class="k">{{ __('Ville de naissance') }}</td><td class="v">{{ $candidat->ville_naissance }}, {{ $candidat->pay_naissance }}@if ($candidat->ville_naissance_ar)<br><span style="font-weight: normal; color: #64748b;">{{ $arabe($candidat->ville_naissance_ar) }}</span>@endif</td>
                            </tr>
                            <tr>
                                <td class="k">{{ __('Sexe') }}</td><td class="v">{{ $candidat->sexe === 'F' ? __('Femme') : __('Homme') }}</td>
                                <td class="k">{{ __('Nationalité') }}</td><td class="v">{{ $candidat->nationalite }}</td>
                            </tr>
                        </table>
                    </td>
                    @if ($photo)
                        <td style="width: 96px; text-align: right; vertical-align: top;"><img src="{{ $photo }}" class="photo"></td>
                    @endif
                </tr>
            </table>
        </div>
    </div>

    {{-- Coordonnées --}}
    <div class="bloc">
        <div class="bloc-titre">{{ __('Coordonnées') }}</div>
        <div class="bloc-corps">
            <table class="kv">
                <tr>
                    <td class="k">{{ __('Email') }}</td><td class="v">{{ $candidat->email }}</td>
                    <td class="k">{{ __('Téléphone mobile') }}</td><td class="v">{{ $candidat->telephone_mob }}@if ($candidat->telephone_fix)<br>{{ $candidat->telephone_fix }}@endif</td>
                </tr>
                <tr>
                    <td class="k">{{ __('Adresse') }}</td>
                    <td class="v" colspan="3">{{ $candidat->adresse }}, {{ $candidat->ville }}, {{ $candidat->province }}, {{ $candidat->pays }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Parcours --}}
    <div class="bloc">
        <div class="bloc-titre">{{ __('Parcours académique') }}</div>
        <div class="bloc-corps">
            <table class="tab">
                <tr><th style="width: 30%;">{{ __('Diplôme') }}</th><th>{{ __('Filière') }}</th><th>{{ __('Établissement') }}</th><th style="width: 12%;">{{ __('Année') }}</th></tr>
                <tr><td><strong>{{ __('Baccalauréat') }}</strong></td><td>{{ $candidat->serie_bac }}</td><td>—</td><td>{{ $candidat->annee_bac }}</td></tr>
                @if ($d && $d->type_diplome_bac_2)
                    <tr><td><strong>Bac+2</strong> · {{ $d->type_diplome_bac_2 }}</td><td>{{ $d->filiere_diplome_bac_2 }}</td><td>{{ $d->etablissement_bac_2 }}</td><td>{{ $d->annee_diplome_bac_2 }}</td></tr>
                @endif
                @if ($d && $d->type_diplome_bac_3)
                    <tr><td><strong>Bac+3</strong> · {{ $d->type_diplome_bac_3 }}</td><td>{{ $d->filiere_diplome_bac_3 }}</td><td>{{ $d->etablissement_bac_3 }}</td><td>{{ $d->annee_diplome_bac_3 }}</td></tr>
                @endif
            </table>
            @if ($candidat->annees_experience !== null)
                <div style="margin-top: 6px; color: #475569;">{{ __("Nombre d'années d'expérience professionnelle") }} : <strong>{{ $candidat->annees_experience }}</strong></div>
            @endif
        </div>
    </div>

    {{-- Expérience --}}
    @if ($experiences->isNotEmpty() || $candidat->attestations->isNotEmpty())
        <div class="bloc">
            <div class="bloc-titre">{{ __('Expérience') }}</div>
            <div class="bloc-corps">
                <table class="tab">
                    @foreach ($experiences as [$type, $e])
                        <tr><td style="width: 18%; color: #64748b;">{{ $type }}</td><td><strong>{{ $e->fonction }}</strong> · {{ $e->etablissement }}</td><td style="width: 20%; text-align: right;">{{ $mois($e->periode) }}</td></tr>
                    @endforeach
                    @foreach ($candidat->attestations as $a)
                        <tr><td style="color: #64748b;">{{ __('Attestation') }}</td><td><strong>{{ $a->type_attestation }}</strong></td><td style="text-align: right;">{{ $a->description }}</td></tr>
                    @endforeach
                </table>
            </div>
        </div>
    @endif

    {{-- Pièces jointes + suivi, côte à côte --}}
    <table>
        <tr>
            <td style="width: 58%; vertical-align: top; padding-right: 6px;">
                <div class="bloc">
                    <div class="bloc-titre">{{ __('Pièces jointes') }}</div>
                    <div class="bloc-corps">
                        <table class="tab">
                            @foreach (collect([
                                [__('Curriculum vitae (CV)'), $candidat->CV],
                                [__('Lettre de demande'), $candidat->demande],
                                [__("Pièce d'identité"), $candidat->scan_cartid],
                                [__("Photo d'identité"), $candidat->photo],
                                [__('Scan du baccalauréat'), $candidat->scan_bac],
                                [__('Diplômes'), $d?->scan_bac_2 || $d?->scan_bac_3],
                            ])->chunk(2) as $paire)
                                <tr>
                                    @foreach ($paire as [$piece, $fournie])
                                        <td>{{ $piece }}</td><td style="width: 8%; text-align: right;" class="{{ $fournie ? 'ok' : 'non' }}">{{ $oui($fournie) }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </td>
            <td style="vertical-align: top; padding-left: 6px;">
                <div class="bloc">
                    <div class="bloc-titre">{{ __('Suivi') }}</div>
                    <div class="bloc-corps">
                        <div style="color: #64748b; font-size: 8.5px;">{{ __('Statut') }}</div>
                        <div style="margin: 3px 0 9px;"><span class="statut">{{ __($inscription->statut_label) }}</span></div>
                        <div style="color: #64748b; font-size: 8.5px;">{{ __('Déposée le') }}</div>
                        <div style="font-weight: bold; margin: 2px 0 9px;">{{ $inscription->created_at?->format('d/m/Y H:i') }}</div>
                        <div style="color: #64748b; font-size: 8.5px;">{{ __('Suivre mon dossier') }}</div>
                        <div style="font-size: 8px; margin-top: 2px; color: {{ $c }};">{{ route('suivi', ['reference' => $inscription->reference]) }}</div>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <div class="note">
        {{ __("Ce document récapitule la préinscription déposée en ligne. Il ne vaut pas admission : la décision de l'établissement est communiquée par email et visible sur la page « Suivre mon dossier ».") }}
        <br><strong>{{ __("Je certifie sur l'honneur l'exactitude des informations et des documents fournis.") }}</strong>
    </div>
</body>
</html>
