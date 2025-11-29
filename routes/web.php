<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Cliente\PaqueteController;
use App\Http\Controllers\Cliente\CompraController;
use App\Http\Controllers\Auth\RegisterController;

// Ruta raíz muestra la página de bienvenida
Route::get('/', function () {
    return view('welcome');
});

// ============================================
// RUTAS DE AUTENTICACIÓN (Públicas)
// ============================================

// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');


Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registro (Solo para Clientes/Beneficiarios)
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

// ============================================
// RUTAS PROTEGIDAS (Requieren autenticación)
// ============================================

Route::middleware(['auth'])->group(function () {

    // Dashboard principal (redirecciona según rol)
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->isAdministrador()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isCoordinador()) {
            return redirect()->route('coordinador.dashboard');
        } elseif ($user->isInstructor()) {
            return redirect()->route('instructor.dashboard');
        } elseif ($user->isCliente()) {
            return redirect()->route('cliente.dashboard');
        } elseif ($user->isEstudiante()) {
            return redirect()->route('estudiante.dashboard');
        }

        abort(403, 'No tienes un rol asignado.');
    })->name('dashboard');

    // ============================================
    // RUTAS DE ADMINISTRADOR
    // ============================================
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // TODO: Agregar rutas de instrumentos e instructores
    });

    // ============================================
    // RUTAS DE COORDINADOR
    // ============================================
    Route::prefix('coordinador')->name('coordinador.')->group(function () {
        Route::get('/dashboard', function () {
            return view('coordinador.dashboard');
        })->name('dashboard');

        // TODO: Agregar rutas de gestión de pagos
    });

    // ============================================
    // RUTAS DE INSTRUCTOR
    // ============================================
    Route::prefix('instructor')->name('instructor.')->group(function () {
        Route::get('/dashboard', function () {
            return view('instructor.dashboard');
        })->name('dashboard');

        // TODO: Agregar rutas de clases
    });

    // ============================================
    // RUTAS DE CLIENTE
    // ============================================
    Route::middleware(['role:cliente'])->prefix('cliente')->name('cliente.')->group(function () {
        // Dashboard del cliente
        Route::get('/dashboard', function () {
            return view('cliente.dashboard');
        })->name('dashboard');

        // RF-01.1: Visualizar Paquetes de Créditos
        Route::get('/paquetes', [PaqueteController::class, 'index'])->name('paquetes.index');
        Route::post('/paquetes/calcular-precio', [PaqueteController::class, 'calcularPrecio'])->name('paquetes.calcular');

        // RF-01.2: Realizar Compra de Créditos
        Route::get('/compras', [CompraController::class, 'index'])->name('compras.index');
        Route::get('/compras/crear', [CompraController::class, 'create'])->name('compras.create');
        Route::post('/compras', [CompraController::class, 'store'])->name('compras.store');
        Route::get('/compras/{id}/confirmacion', [CompraController::class, 'confirmacion'])->name('compras.confirmacion');
        
        // Buscar beneficiario por email
        Route::post('/beneficiarios/buscar', [CompraController::class, 'buscarBeneficiario'])->name('beneficiarios.buscar');
    });

    // ============================================
    // RUTAS DE ESTUDIANTE/BENEFICIARIO
    // ============================================
    Route::prefix('estudiante')->name('estudiante.')->group(function () {
        Route::get('/dashboard', function () {
            return view('estudiante.dashboard');
        })->name('dashboard');

        // TODO: Agregar rutas de consulta de créditos
    });
});