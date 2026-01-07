<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrechoController;
use Inertia\Inertia;

// 1. Rota Raiz (Página Welcome)
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('home');

// 2. Grupo de Rotas Protegidas (Tudo aqui exige login)
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard | Tela com aviso que o usuário está logado
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Gerenciamento de Trechos (Index agora está protegido aqui)
    Route::get('/trechos', [TrechoController::class, 'index'])->name('trechos.index');
    Route::get('/trechos/create', [TrechoController::class, 'create'])->name('trechos.create');
    Route::post('/trechos', [TrechoController::class, 'store'])->name('trechos.store');
    Route::get('/trechos/{trecho}/edit', [TrechoController::class, 'edit'])->name('trechos.edit');
    Route::put('/trechos/{trecho}', [TrechoController::class, 'update'])->name('trechos.update');
    Route::delete('/trechos/{trecho}', [TrechoController::class, 'destroy'])->name('trechos.destroy');
    Route::get('/trechos/{trecho}', [TrechoController::class, 'show'])->name('trechos.show');

    // Perfil do Usuário
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 3. APIs auxiliares
Route::get('/api/ufs', [TrechoController::class, 'getUfs']);
Route::get('/api/rodovias/{uf}', [TrechoController::class, 'getRodovias']);

require __DIR__.'/auth.php';
