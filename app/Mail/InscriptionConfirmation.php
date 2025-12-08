<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InscriptionConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $candidatName;
    public $candidatEmail;
    public $personalInfo;
    public $formation;
    public $diplomas;
    public $stages;
    public $experiences;
    public $attestations;

    public function __construct($candidatName, $candidatEmail, $personalInfo = [], $formation = null, $diplomas = [], $stages = [], $experiences = [], $attestations = [])
    {
        $this->candidatName = $candidatName;
        $this->candidatEmail = $candidatEmail;
        $this->personalInfo = $personalInfo;
        $this->formation = $formation;
        $this->diplomas = $diplomas;
        $this->stages = $stages;
        $this->experiences = $experiences;
        $this->attestations = $attestations;
    }

    public function build()
    {
        return $this->view('mails.inscription_confirmation')
            ->subject('Confirmation d\'inscription - FST USMBA')
            ->with([
                'candidatName' => $this->candidatName,
                'candidatEmail' => $this->candidatEmail,
                'personalInfo' => $this->personalInfo,
                'formation' => $this->formation,
                'diplomas' => $this->diplomas,
                'stages' => $this->stages,
                'experiences' => $this->experiences,
                'attestations' => $this->attestations,
            ]);
    }
}