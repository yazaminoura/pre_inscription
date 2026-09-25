<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

/** Email de test : vérifie que la configuration d'envoi fonctionne. */
class EmailTest extends Mailable
{
    use Queueable;

    public function build()
    {
        return $this->subject('Email de test · ' . config('etablissement.court'))
            ->html(
                '<p>Bonjour,</p>'
                . '<p>Si vous lisez ce message, l\'envoi d\'emails de la plateforme de préinscription de <strong>'
                . e(config('etablissement.nom')) . '</strong> fonctionne.</p>'
                . '<p>Envoyé le ' . now()->format('d/m/Y à H:i') . '.</p>'
            );
    }
}
