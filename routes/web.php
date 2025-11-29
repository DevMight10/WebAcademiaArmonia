<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Cliente\PaqueteController;
use App\Http\Controllers\Cliente\CompraController;

// Ruta raíz redirige al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas de autenticación
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// ==========================================
// RUTAS TEMPORALES PARA DESARROLLO SIN LOGIN
// TODO: Mover estas rutas dentro del middleware de autenticación cuando esté listo
// ==========================================

// Rutas de Cliente - Paquetes y Compras
Route::prefix('cliente')->name('cliente.')->group(function () {

    // RF-01.1: Visualizar Paquetes
    Route::get('/paquetes', [PaqueteController::class, 'index'])->name('paquetes.index');
    Route::post('/paquetes/calcular-precio', [PaqueteController::class, 'calcularPrecio'])->name('paquetes.calcular');

    // RF-01.2: Realizar Compra
    Route::get('/compras', [CompraController::class, 'index'])->name('compras.index');
    Route::get('/compras/crear', [CompraController::class, 'create'])->name('compras.create');
    Route::post('/compras', [CompraController::class, 'store'])->name('compras.store');
    Route::get('/compras/{id}/confirmacion', [CompraController::class, 'confirmacion'])->name('compras.confirmacion');
});
