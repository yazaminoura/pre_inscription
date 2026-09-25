<?php

namespace App\Mail;

use App\Models\Inscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/** Email envoyé au candidat quand l'établissement change le statut de sa candidature. */
class StatutChange extends Mailable
{
    use Queueable, SerializesModels;

    // Phrase principale selon le statut
    public const MESSAGES = [
        'en_cours' => 'Votre dossier est en cours d\'étude par la commission.',
        'incomplet' => 'Votre dossier est incomplet. Merci de faire parvenir à l\'établissement les éléments indiqués ci-dessous pour que votre candidature puisse être étudiée.',
        'liste_attente' => 'Votre candidature a été placée sur liste d\'attente. Nous vous recontacterons si une place se libère.',
        'acceptee' => 'Nous avons le plaisir de vous annoncer que votre candidature a été acceptée.',
        'refusee' => 'Après étude de votre dossier, nous sommes au regret de ne pas pouvoir retenir votre candidature.',
        'en_attente' => 'Votre dossier est enregistré et en attente d\'étude.',
    ];

    public function __construct(public Inscription $inscription)
    {
    }

    public function build()
    {
        $this->inscription->loadMissing('candidat', 'formation');

        return $this->subject($this->inscription->statut_label . ' · ' . $this->inscription->formation->titre . ' · ' . config('etablissement.court'))
            ->view('mails.statut_change', [
                'inscription' => $this->inscription,
                'texte' => self::MESSAGES[$this->inscription->statut] ?? '',
            ]);
    }
}
