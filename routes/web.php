<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CapacitacionController;
use App\Http\Controllers\ParticipanteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\ResetCodeController;


// Redirigir a login si no está autenticado
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Rutas de autenticación
require __DIR__ . '/auth.php';

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

    // Vista previa de diplomas
    Route::get('capacitaciones/{id}/diplomas/preview', [CapacitacionController::class, 'vistaPreviaDiploma'])->name('capacitaciones.diplomas.vistaPrevia');
    Route::get('capacitaciones/{id}/diplomas/preview', [CapacitacionController::class, 'vistaPreviaDiploma'])->name('capacitaciones.diplomas.preview');

    // Importar y exportar participantes
    Route::post('/capacitaciones/{id}/importar-excel', [ParticipanteController::class, 'importarExcel'])->name('participantes.importar');
    Route::get('/capacitaciones/{id}/exportar-excel', [ParticipanteController::class, 'exportarExcel'])->name('participantes.exportar');
});

//-----------------------------------------------------------------------------------------------------------------------------
// ✅ RUTAS PÚBLICAS para el proceso de recuperación de contraseña por código
// Mostrar formulario para solicitar código
Route::get('/password/request-code', [ResetCodeController::class, 'showRequestForm'])->name('password.request-code');

// Enviar código por correo
Route::post('/password/send-code', [ResetCodeController::class, 'sendCode'])->name('password.send-code');

// Mostrar formulario para verificar código
Route::get('/password/verify-code', [ResetCodeController::class, 'showVerifyForm'])->name('password.verify-code-form');

// Validar código ingresado
Route::post('/password/verify-code', [ResetCodeController::class, 'verifyCode'])->name('password.verify-code');

// Guardar nueva contraseña
Route::post('/password/reset-with-code', [ResetCodeController::class, 'resetPassword'])->name('password.reset-with-code');
//-----------------------------------------------------------------------------------------------------------------------------