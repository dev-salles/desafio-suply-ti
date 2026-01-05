<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrechoController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
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

    // Listagem e Dashboard
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

// 3. APIs auxiliares (Se elas precisarem de login, mova-as para dentro do grupo acima)
Route::get('/api/ufs', [TrechoController::class, 'getUfs']);
Route::get('/api/rodovias/{uf}', [TrechoController::class, 'getRodovias']);

Route::get('/instalar-dados-projeto', function () {
    // Aumenta o tempo de execução do PHP para este processo não travar
    set_time_limit(300); 

    try {
        echo "Iniciando Seeding...<br>";
        Artisan::call('db:seed', ['--force' => true]);
        return "Dados instalados com sucesso! Verifique o DBeaver.";
    } catch (\Exception $e) {
        return "Erro ao rodar seed: " . $e->getMessage();
    }
});

require __DIR__.'/auth.php';
