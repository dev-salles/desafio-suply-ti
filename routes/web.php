<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrechoController;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Principais rotas do projeto
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/trechos', [TrechoController::class, 'index'])->name('trechos.index'); 
    Route::get('/trechos/create', [TrechoController::class, 'create'])->name('trechos.create');
    Route::post('/trechos', [TrechoController::class, 'store'])->name('trechos.store');
});

Route::get('/api/ufs', [App\Http\Controllers\TrechoController::class, 'getUfs']);

Route::get('/api/rodovias/{uf}', [App\Http\Controllers\TrechoController::class, 'getRodovias']);

require __DIR__.'/auth.php';
