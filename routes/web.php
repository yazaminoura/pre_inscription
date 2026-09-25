<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CandidatController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\CandidatformController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EtablissementController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\RecuController;
use App\Http\Controllers\SuiviController;
use App\Http\Middleware\DefinirLangue;
use App\Livewire\FormationStats;

require __DIR__.'/auth.php';

// Public routes (français / anglais / arabe)
Route::middleware(DefinirLangue::class)->group(function () {
    Route::get('/', [CandidatformController::class, 'accueil'])->name('accueil');
    Route::get('/formations/{formation}', [CandidatformController::class, 'formation'])->name('formation.public');
    Route::get('/suivi', [SuiviController::class, 'formulaire'])->name('suivi');
    Route::post('/suivi', [SuiviController::class, 'consulter'])->middleware('throttle:10,1')->name('suivi.consulter');
    Route::get('/preinscription', [CandidatformController::class, 'showForm'])->name('candidat.form');
    Route::post('/preinscription', [CandidatformController::class, 'submitStep'])->name('candidat.submit');
    Route::get('/preinscription/merci', [CandidatformController::class, 'merci'])->name('candidat.merci');
    Route::post('/preinscription/recommencer', [CandidatformController::class, 'recommencer'])->name('candidat.recommencer');
    Route::get('/preinscription/recu/{reference}', [RecuController::class, 'telecharger'])->name('candidat.recu');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('responsable/administrateurs', UserController::class)
        ->except('show')
        ->parameters(['administrateurs' => 'user'])
        ->names('administrateurs');

    Route::resource('responsable/formations', FormationController::class)->except('show');

    Route::get('responsable/etablissement', [EtablissementController::class, 'edit'])->name('etablissement.edit');
    Route::put('responsable/etablissement', [EtablissementController::class, 'update'])->name('etablissement.update');
    Route::post('responsable/etablissement/email-test', [EtablissementController::class, 'testerEmail'])->middleware('throttle:5,1')->name('etablissement.email-test');

    // Les diplômes, stages, expériences et attestations font partie du dossier candidat (page show)
    Route::resource('responsable/candidats', CandidatController::class)->only(['index', 'show', 'destroy']);
    Route::patch('responsable/inscriptions/{inscription}/statut', [InscriptionController::class, 'updateStatut'])->name('inscriptions.statut');

    Route::get('responsable/documents/{chemin}', [DocumentController::class, 'voir'])->where('chemin', '.*')->name('documents.voir');

    Route::get('responsable/stats_formations', FormationStats::class)->name('formation-stats');
    Route::get('/export-candidats/{id}', [ExportController::class, 'export'])->name('export.candidats');
    Route::get('/export-candidats', [ExportController::class, 'exportTout'])->name('export.tout');
    Route::get('responsable/import/modele', [ImportController::class, 'modele'])->name('import.modele');
    Route::post('responsable/import', [ImportController::class, 'importer'])->name('import.candidats');

    Route::post('responsable/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
