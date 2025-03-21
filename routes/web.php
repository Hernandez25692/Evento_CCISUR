<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CapacitacionController;
use App\Http\Controllers\ParticipanteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

// Redirigir a login si no está autenticado
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Rutas de autenticación
require __DIR__.'/auth.php';

// Rutas protegidas
Route::middleware(['auth'])->group(function () {
    // Mostrar lista de capacitaciones después de iniciar sesión
    Route::get('/capacitaciones', [CapacitacionController::class, 'index'])->name('capacitaciones.index');

    // CRUD de capacitaciones
    Route::resource('capacitaciones', CapacitacionController::class);

    // Rutas de participantes
    Route::get('capacitaciones/{id}/participantes', [CapacitacionController::class, 'listarParticipantes'])->name('capacitaciones.participantes');
    Route::get('capacitaciones/{id}/participantes/create', [ParticipanteController::class, 'create'])->name('capacitaciones.participantes.create');
    Route::post('capacitaciones/{id}/participantes', [ParticipanteController::class, 'store'])->name('capacitaciones.participantes.store');
    Route::delete('participantes/{id}', [ParticipanteController::class, 'destroy'])->name('participantes.destroy');

    // Plantilla y diplomas
    Route::get('capacitaciones/{id}/plantilla', [CapacitacionController::class, 'agregarPlantilla'])->name('capacitaciones.plantilla');
    Route::post('capacitaciones/{id}/plantilla', [CapacitacionController::class, 'guardarPlantilla'])->name('capacitaciones.plantilla.store');
    Route::get('capacitaciones/{id}/diplomas', [CapacitacionController::class, 'generarDiplomas'])->name('capacitaciones.diplomas');

    // Dashboard y Filtro
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/filtro', [DashboardController::class, 'filtro'])->name('dashboard.filtro');

    // Exportar/Importar participantes
    Route::get('capacitaciones/{id}/participantes/export', [ParticipanteController::class, 'export'])->name('capacitaciones.participantes.export');
    Route::post('capacitaciones/{id}/participantes/import', [ParticipanteController::class, 'import'])->name('capacitaciones.participantes.import');

    // Perfil del usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cerrar sesión
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
