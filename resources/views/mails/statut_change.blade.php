<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
@php $couleur = config('etablissement.couleur'); @endphp
<body style="margin:0; padding:0; background:#f3f6f9; font-family: Arial, Helvetica, sans-serif; color:#1c2b39;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f3f6f9; padding:24px 12px;">
    <tr>
        <td align="center">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px; background:#ffffff; border-radius:12px; overflow:hidden; border:1px solid #e3eaf0;">
                <tr>
                    <td style="background:{{ $couleur }}; color:#ffffff; padding:20px 28px; font-size:18px; font-weight:bold;">
                        {{ config('etablissement.nom') }}
                    </td>
                </tr>
                <tr>
                    <td style="padding:28px;">
                        <p style="margin:0 0 16px; font-size:15px;">Bonjour {{ $inscription->candidat->prenom }} {{ $inscription->candidat->nom }},</p>

                        <p style="margin:0 0 8px; font-size:13px; color:#6a7b8a;">Votre candidature</p>
                        <p style="margin:0 0 20px; font-size:15px; font-weight:bold;">
                            {{ $inscription->formation->type_formation }} · {{ $inscription->formation->titre }}<br>
                            <span style="font-weight:normal; color:#6a7b8a; font-size:13px;">Référence {{ $inscription->reference }}</span>
                        </p>

                        <p style="margin:0 0 20px;">
                            <span style="display:inline-block; padding:6px 14px; border-radius:20px; font-weight:bold; font-size:14px; color:{{ $inscription->statut_color }}; background:{{ $inscription->statut_color }}1a;">
                                {{ $inscription->statut_label }}
                            </span>
                        </p>

                        <p style="margin:0 0 16px; font-size:15px; line-height:1.6;">{{ $texte }}</p>

                        @if ($inscription->motif)
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 20px;">
                                <tr>
                                    <td style="background:#f3f6f9; border-left:4px solid {{ $inscription->statut_color }}; padding:12px 16px; font-size:14px; line-height:1.5;">
                                        <strong>Précision de l'établissement :</strong><br>{{ $inscription->motif }}
                                    </td>
                                </tr>
                            </table>
                        @endif

                        <p style="margin:0 0 24px; font-size:14px; line-height:1.6;">
                            Vous pouvez suivre votre dossier à tout moment avec votre référence et votre email :
                        </p>
                        <p style="margin:0 0 24px;">
                            <a href="{{ route('suivi', ['reference' => $inscription->reference]) }}"
                               style="display:inline-block; background:{{ $couleur }}; color:#ffffff; text-decoration:none; padding:12px 22px; border-radius:8px; font-weight:bold; font-size:14px;">
                                Suivre mon dossier
                            </a>
                        </p>

                        <p style="margin:0; font-size:13px; color:#6a7b8a; line-height:1.6;">
                            Cordialement,<br>{{ config('etablissement.nom') }}
                            @if (config('etablissement.email'))<br><a href="mailto:{{ config('etablissement.email') }}" style="color:{{ $couleur }};">{{ config('etablissement.email') }}</a>@endif
                        </p>
                    </td>
                </tr>
            </table>
            <p style="font-size:11px; color:#9aa8b4; margin:16px 0 0;">Message automatique, merci de ne pas y répondre directement.</p>
        </td>
    </tr>
</table>
</body>
</html>
