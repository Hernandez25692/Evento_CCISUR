<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetCodeController;
use App\Http\Controllers\CapacitacionController;
use App\Http\Controllers\ParticipanteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlantillaDiplomaController;
use App\Http\Controllers\CertificadoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\DiplomaPublicoController;

//------------------------------------------------------------
// 🔐 RUTAS DE AUTENTICACIÓN
//------------------------------------------------------------
Route::get('/', fn () => redirect()->route('login'))->name('home');
require __DIR__ . '/auth.php';

//------------------------------------------------------------
// 🔒 RUTAS PROTEGIDAS (AUTENTICADO)
//------------------------------------------------------------
Route::middleware(['auth'])->group(function () {

    //--------------------------------------------------------
    // 📚 CAPACITACIONES
    //--------------------------------------------------------
    Route::resource('capacitaciones', CapacitacionController::class);
    Route::get('/capacitaciones', [CapacitacionController::class, 'index'])->name('capacitaciones.index');

    //--------------------------------------------------------
    // 👥 PARTICIPANTES
    //--------------------------------------------------------
    Route::get('capacitaciones/{id}/participantes', [CapacitacionController::class, 'listarParticipantes'])->name('capacitaciones.participantes');
    Route::get('capacitaciones/{id}/participantes/create', [ParticipanteController::class, 'create'])->name('capacitaciones.participantes.create');
    Route::post('capacitaciones/{id}/participantes', [ParticipanteController::class, 'store'])->name('capacitaciones.participantes.store');
    Route::delete('participantes/{id}', [ParticipanteController::class, 'destroy'])->name('participantes.destroy');

    // Editar participante
    Route::get('capacitaciones/{capacitacion}/participantes/{participante}/editar', [ParticipanteController::class, 'edit'])->name('participantes.edit');
    Route::put('capacitaciones/{capacitacion}/participantes/{participante}', [ParticipanteController::class, 'update'])->name('participantes.update');

    // Importar / Exportar
    Route::post('capacitaciones/{id}/participantes/import', [ParticipanteController::class, 'importarExcel'])->name('participantes.importar');
    Route::get('capacitaciones/{id}/participantes/export', [ParticipanteController::class, 'exportarExcel'])->name('participantes.exportar');

    //--------------------------------------------------------
    // 🧾 PLANTILLAS Y DIPLOMAS
    //--------------------------------------------------------
    Route::get('capacitaciones/{id}/plantilla', [CapacitacionController::class, 'agregarPlantilla'])->name('capacitaciones.plantilla');
    Route::post('capacitaciones/{id}/plantilla', [CapacitacionController::class, 'guardarPlantilla'])->name('capacitaciones.plantilla.store');
    Route::get('capacitaciones/{id}/plantilla/configurar', [PlantillaDiplomaController::class, 'configuracionPlantilla'])->name('capacitaciones.configuracion.plantilla');
    Route::get('capacitaciones/{id}/diplomas', [CapacitacionController::class, 'generarDiplomas'])->name('capacitaciones.diplomas');
    Route::get('capacitaciones/{id}/diplomas/preview', [CapacitacionController::class, 'vistaPreviaDiploma'])->name('capacitaciones.diplomas.preview');

    //--------------------------------------------------------
    // 📊 DASHBOARD
    //--------------------------------------------------------
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/filtro', [DashboardController::class, 'filtro'])->name('dashboard.filtro');

    //--------------------------------------------------------
    // 👤 PERFIL
    //--------------------------------------------------------
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //--------------------------------------------------------
    // 🔚 CERRAR SESIÓN
    //--------------------------------------------------------
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    //--------------------------------------------------------
    // 📑 REPORTES
    //--------------------------------------------------------
    Route::get('/reportes/capacitaciones', [ReporteController::class, 'index'])->name('reportes.capacitaciones');
    Route::get('/reportes/capacitaciones/exportar', [ReporteController::class, 'exportarExcel'])->name('reportes.capacitaciones.exportar');
    Route::get('/reportes/capacitaciones/exportar', [ReporteController::class, 'exportarExcel'])->name('reportes.capacitaciones.export');

});

//------------------------------------------------------------
// 🌐 RUTAS PÚBLICAS
//------------------------------------------------------------

// Recuperación de contraseña con código
Route::get('/password/request-code', [ResetCodeController::class, 'showRequestForm'])->name('password.request-code');
Route::post('/password/send-code', [ResetCodeController::class, 'sendCode'])->name('password.send-code');
Route::get('/password/verify-code', [ResetCodeController::class, 'showVerifyForm'])->name('password.verify-code-form');
Route::post('/password/verify-code', [ResetCodeController::class, 'verifyCode'])->name('password.verify-code');
Route::post('/password/reset-with-code', [ResetCodeController::class, 'resetPassword'])->name('password.reset-with-code');

// Validación de diplomas públicos
Route::get('/verificar-diploma', [DiplomaPublicoController::class, 'index'])->name('diploma.publico.index');
Route::post('/verificar-diploma', [DiplomaPublicoController::class, 'buscar'])->name('diploma.publico.buscar');
Route::get('/diplomas/descargar/{capacitacion_id}/{identidad}', [DiplomaPublicoController::class, 'descargar'])->name('diplomas.descargar');

// Certificados públicos
Route::get('/buscar-certificados', [CertificadoController::class, 'buscar'])->name('certificados.buscar');
Route::post('/buscar-certificados', [CertificadoController::class, 'resultado'])->name('certificados.resultado');
Route::get('/certificados/{capacitacion}/plantilla', [CertificadoController::class, 'agregarPlantilla'])->name('certificados.plantilla');
Route::get('/certificados/{capacitacion}/{participante}/descargar', [CertificadoController::class, 'descargar'])->name('certificados.descargar');
