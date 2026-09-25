<?php

namespace App\Console\Commands;

use App\Mail\EmailTest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TesterEmail extends Command
{
    protected $signature = 'email:tester {adresse : adresse qui recevra l\'email de test}';

    protected $description = 'Envoie un email de test pour vérifier la configuration MAIL_* du fichier .env';

    public function handle(): int
    {
        $mailer = config('mail.default');
        try {
            Mail::to($this->argument('adresse'))->send(new EmailTest());
        } catch (\Throwable $e) {
            $this->error('Échec de l\'envoi : ' . $e->getMessage());
            return self::FAILURE;
        }

        $mailer === 'log'
            ? $this->warn('MAIL_MAILER=log : l\'email a été écrit dans storage/logs/laravel.log, pas envoyé. Configurez le SMTP dans .env.')
            : $this->info('Email envoyé à ' . $this->argument('adresse') . ' via « ' . $mailer . ' ».');

        return self::SUCCESS;
    }
}
