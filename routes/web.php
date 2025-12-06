<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Cliente\PaqueteController;
use App\Http\Controllers\Cliente\CompraController;
use App\Http\Controllers\Coordinador\CompraCoordinadorController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\InstrumentoController;

// Ruta raíz muestra la página de bienvenida
Route::get('/', function () {
    return view('welcome');
});

// RUTAS DE AUTENTICACIÓN (Públicas)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registro (Solo para Clientes/Beneficiarios)
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

// RUTAS PROTEGIDAS (Requieren autenticación)

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
        } elseif ($user->isBeneficiario()) {
            return redirect()->route('beneficiario.dashboard');
        }

        abort(403, 'No tienes un rol asignado.');
    })->name('dashboard');

    // ============================================
    // RUTAS DE ADMINISTRADOR
    // ============================================
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/dashboard', function () {
            $stats = [
                'instrumentos' => \App\Models\Instrumento::count(),
                'instructores' => \App\Models\Instructor::count(),
                'clientes' => \App\Models\Cliente::count(),
                'ventas' => \App\Models\Compra::where('estado', 'pagado_y_confirmado')->sum('total') ?? 0,
            ];
            return view('admin.dashboard', compact('stats'));
        })->name('dashboard');

        // Gestión de Instrumentos
        Route::resource('instrumentos', InstrumentoController::class);
        Route::post('instrumentos/{instrumento}/restore', [InstrumentoController::class, 'restore'])
            ->name('instrumentos.restore');

        // Gestión de Instructores
        Route::resource('instructores', \App\Http\Controllers\Admin\InstructorController::class);
        Route::post('instructores/{instructore}/restore', [\App\Http\Controllers\Admin\InstructorController::class, 'restore'])
            ->name('instructores.restore');
    });

    // ============================================
    // RUTAS DE COORDINADOR
    // ============================================
    Route::middleware(['role:coordinador'])->prefix('coordinador')->name('coordinador.')->group(function () {
        Route::get('/dashboard', function () {
            return view('coordinador.dashboard');
        })->name('dashboard');

        // Gestión de compras
        Route::get('/compras', [CompraCoordinadorController::class, 'index'])->name('compras.index');
        Route::get('/compras/{id}', [CompraCoordinadorController::class, 'show'])->name('compras.show');
        Route::post('/compras/{id}/aprobar', [CompraCoordinadorController::class, 'aprobar'])->name('compras.aprobar');
        Route::post('/compras/{id}/rechazar', [CompraCoordinadorController::class, 'rechazar'])->name('compras.rechazar');
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
        
        // Redistribución de créditos
        Route::get('/compras/{id}/distribuciones', [CompraController::class, 'obtenerDistribuciones'])->name('compras.distribuciones');
        Route::put('/compras/{id}/redistribuir', [CompraController::class, 'redistribuirCreditos'])->name('compras.redistribuir');
        
        // Buscar beneficiario por email
        Route::post('/beneficiarios/buscar', [CompraController::class, 'buscarBeneficiario'])->name('beneficiarios.buscar');
    });

    // ============================================
    // RUTAS DE BENEFICIARIO
    // ============================================
    Route::prefix('beneficiario')->name('beneficiario.')->middleware(['auth', 'role:beneficiario'])->group(function () {
        Route::get('/dashboard', function () {
            return view('beneficiario.dashboard');
        })->name('dashboard');

        // Créditos
        Route::get('/creditos', [\App\Http\Controllers\Beneficiario\CreditoController::class, 'index'])->name('creditos.index');
    });
});