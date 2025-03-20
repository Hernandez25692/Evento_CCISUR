<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CapacitacionController;
use App\Http\Controllers\ParticipanteController;
use App\Http\Controllers\PlantillaDiplomaController;



// Ruta para mostrar capacitaciones en la página principal
Route::get('/', [CapacitacionController::class, 'index'])->name('home');

Route::resource('capacitaciones', CapacitacionController::class);
Route::resource('participantes', ParticipanteController::class);

// Rutas adicionales para funcionalidades específicas del menú de detalles
Route::get('capacitaciones/{id}/participantes', [CapacitacionController::class, 'listarParticipantes'])->name('capacitaciones.participantes');
Route::get('capacitaciones/{id}/participantes/create', [ParticipanteController::class, 'create'])->name('capacitaciones.participantes.create');
Route::post('capacitaciones/{id}/participantes', [ParticipanteController::class, 'store'])->name('capacitaciones.participantes.store');

Route::get('capacitaciones/{id}/plantilla', [CapacitacionController::class, 'agregarPlantilla'])->name('capacitaciones.plantilla');
Route::post('capacitaciones/{id}/plantilla', [CapacitacionController::class, 'guardarPlantilla'])->name('capacitaciones.plantilla.store');

Route::get('capacitaciones/{id}/diplomas', [CapacitacionController::class, 'generarDiplomas'])->name('capacitaciones.diplomas');

Route::resource('capacitaciones', CapacitacionController::class);



Route::get('capacitaciones/{id}/plantilla', [CapacitacionController::class, 'agregarPlantilla'])->name('capacitaciones.plantilla');
Route::post('capacitaciones/{id}/plantilla', [CapacitacionController::class, 'guardarPlantilla'])->name('capacitaciones.plantilla.store');
Route::get('capacitaciones/{id}/diplomas', [CapacitacionController::class, 'generarDiplomas'])->name('capacitaciones.diplomas');



