<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicSurveyController;
use App\Http\Controllers\SurveyController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- PUBLIC-FACING ROUTES ---

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// This is the corrected route group.
Route::prefix('survey')->name('public.survey.')->group(function () {
    Route::get('/complete', [PublicSurveyController::class, 'complete'])->name('complete');
    Route::get('/{survey}/closed', [\App\Http\Controllers\PublicSurveyController::class, 'closed'])->name('closed');
    Route::get('/{survey}', [PublicSurveyController::class, 'start'])->name('start');
    Route::get('/{session_uuid}/question/{order}', [PublicSurveyController::class, 'showQuestion'])->name('question.show');
    Route::post('/{session_uuid}/question/{order}', [PublicSurveyController::class, 'storeResponse'])->name('question.store');
});


// --- ADMIN & AUTHENTICATED ROUTES ---

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    // Survey Management
    Route::get('/surveys/create', [SurveyController::class, 'create'])->name('surveys.create');
    Route::get('/surveys/{survey}', [SurveyController::class, 'show'])->name('surveys.show');
    Route::get('/surveys/{survey}/share', [SurveyController::class, 'share'])->name('surveys.share');
    Route::patch('/surveys/{survey}/status', [SurveyController::class, 'updateStatus'])->name('surveys.status.update');
    Route::get('/surveys/{survey}/report', [SurveyController::class, 'report'])->name('surveys.report');
    Route::get('/surveys', [SurveyController::class, 'index'])->name('surveys.index');
    Route::post('/surveys', [SurveyController::class, 'store'])->name('surveys.store');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
