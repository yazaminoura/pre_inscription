<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AttestationController;
use App\Http\Controllers\DiplomeController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\StageController;
use App\Http\Controllers\CandidatController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\CandidatformController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Livewire\FormationStats;

require __DIR__.'/auth.php';

// Public routes
Route::get('/', [CandidatformController::class, 'showForm'])->name('candidat.form');
Route::post('/submit', [CandidatformController::class, 'submitStep'])->name('candidat.submit');
Route::post('/previous', [CandidatformController::class, 'previousStep'])->name('candidat.previous');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['auth', 'verified'])
        ->name('dashboard');

    Route::resource('responsable/administrateurs', UserController::class)
        ->parameters(['administrateurs' => 'user'])
        ->names('administrateurs');

    Route::put('responsable/administrateurs/{user}', [UserController::class, 'update'])->name('administrateurs.update');
    Route::resource('responsable/formations', FormationController::class);
    Route::resource('responsable/experiences', ExperienceController::class);
    Route::resource('responsable/attestations', AttestationController::class);
    Route::resource('responsable/stages', StageController::class);
    Route::resource('responsable/candidats', CandidatController::class);
    Route::resource('responsable/diplomes', DiplomeController::class);

    Route::get('responsable/stats_formations', FormationStats::class)->name('formation-stats');
    Route::post('responsable/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/export-candidats/{id}', [ExportController::class, 'export'])->name('export.candidats');
});