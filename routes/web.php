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

        return view('dashboard');
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
                'compras' => \App\Models\Compra::sum('total') ?? 0,
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
        
        // Gestión de Clientes
        Route::resource('clientes', \App\Http\Controllers\Admin\ClienteController::class)->only(['index', 'show', 'destroy']);
        
        // Gestión de Beneficiarios
        Route::resource('beneficiarios', \App\Http\Controllers\Admin\BeneficiarioController::class)->only(['index', 'show', 'destroy']);
        
        // RF-10: Generación de Reportes (PDF/Excel)
        Route::get('/reportes', [\App\Http\Controllers\Admin\ReporteCompraController::class, 'index'])->name('reportes.index');
        Route::get('/reportes/compra/{id}/pdf', [\App\Http\Controllers\Admin\ReporteCompraController::class, 'generarPDF'])->name('reportes.compra.pdf');
        Route::post('/reportes/listado/pdf', [\App\Http\Controllers\Admin\ReporteCompraController::class, 'generarListadoPDF'])->name('reportes.listado.pdf');
        Route::post('/reportes/excel', [\App\Http\Controllers\Admin\ReporteCompraController::class, 'exportarExcel'])->name('reportes.excel');
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
        
        // Gestión de citas
        Route::get('/citas', [\App\Http\Controllers\Coordinador\CitaController::class, 'index'])->name('citas.index');
        Route::post('/citas/{id}/confirmar', [\App\Http\Controllers\Coordinador\CitaController::class, 'confirmar'])->name('citas.confirmar');
        Route::post('/citas/{id}/rechazar', [\App\Http\Controllers\Coordinador\CitaController::class, 'rechazar'])->name('citas.rechazar');
        Route::post('/citas/{id}/completar', [\App\Http\Controllers\Coordinador\CitaController::class, 'completar'])->name('citas.completar');
        Route::get('/calendario', [\App\Http\Controllers\Coordinador\CitaController::class, 'calendario'])->name('calendario');
    });


    // ============================================
    // RUTAS DE INSTRUCTOR
    // ============================================
    Route::middleware(['role:instructor'])->prefix('instructor')->name('instructor.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [\App\Http\Controllers\Instructor\InstructorController::class, 'dashboard'])->name('dashboard');
        
        // Mis Clases
        Route::get('/citas', [\App\Http\Controllers\Instructor\InstructorController::class, 'misCitas'])->name('citas.index');
        Route::post('/citas/{id}/completar', [\App\Http\Controllers\Instructor\InstructorController::class, 'marcarCompletada'])->name('citas.completar');
    });

    // ============================================
    // RUTAS DE CLIENTE
    // ============================================
    Route::middleware(['role:cliente'])->prefix('cliente')->name('cliente.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [\App\Http\Controllers\Cliente\DashboardController::class, 'index'])->name('dashboard');

        // RF-01.1: Visualizar Paquetes de Créditos
        Route::get('/paquetes', [PaqueteController::class, 'index'])->name('paquetes.index');
        Route::post('/paquetes/calcular-precio', [PaqueteController::class, 'calcularPrecio'])->name('paquetes.calcular');

        // RF-01.2: Realizar Compra de Créditos
        Route::get('/compras', [CompraController::class, 'index'])->name('compras.index');
        Route::get('/compras/crear', [CompraController::class, 'create'])->name('compras.create');
        Route::post('/compras', [CompraController::class, 'store'])->name('compras.store');
        Route::get('/compras/{id}/confirmacion', [CompraController::class, 'confirmacion'])->name('compras.confirmacion');
        Route::get('/compras/historial', [CompraController::class, 'historial'])->name('compras.historial');
        
        // Agendamiento de clases (cliente como beneficiario)
        Route::get('/agendamiento', [\App\Http\Controllers\Cliente\AgendamientoClienteController::class, 'index'])->name('agendamiento.index');
        Route::post('/agendamiento', [\App\Http\Controllers\Cliente\AgendamientoClienteController::class, 'store'])->name('agendamiento.store');
        Route::get('/agendamiento/instructores/{instrumentoId}', [\App\Http\Controllers\Cliente\AgendamientoClienteController::class, 'obtenerInstructores'])->name('agendamiento.instructores');
        Route::post('/agendamiento/verificar-disponibilidad', [\App\Http\Controllers\Cliente\AgendamientoClienteController::class, 'verificarDisponibilidad'])->name('agendamiento.verificar');
        
        // Historial de clases del cliente
        Route::get('/clases/historial', [\App\Http\Controllers\Cliente\AgendamientoClienteController::class, 'historial'])->name('clases.historial');
        Route::post('/clases/{id}/cancelar', [\App\Http\Controllers\Cliente\AgendamientoClienteController::class, 'cancelar'])->name('clases.cancelar');
        
        // Redistribución de créditos
        Route::get('/compras/{id}/distribuciones', [CompraController::class, 'obtenerDistribuciones'])->name('compras.distribuciones');
        Route::put('/compras/{id}/redistribuir', [CompraController::class, 'redistribuirCreditos'])->name('compras.redistribuir');
        
        // Buscar beneficiario por email
        Route::post('/beneficiarios/buscar', [CompraController::class, 'buscarBeneficiario'])->name('beneficiarios.buscar');
        
        // Reportes
        Route::get('/reportes', [\App\Http\Controllers\Cliente\ReporteCompraController::class, 'index'])->name('reportes.index');
        Route::get('/reportes/compra/{id}/pdf', [\App\Http\Controllers\Cliente\ReporteCompraController::class, 'generarPDF'])->name('reportes.compra.pdf');
        Route::get('/reportes/listado/pdf', [\App\Http\Controllers\Cliente\ReporteCompraController::class, 'generarListadoPDF'])->name('reportes.listado.pdf');
        Route::get('/reportes/excel', [\App\Http\Controllers\Cliente\ReporteCompraController::class, 'exportarExcel'])->name('reportes.excel');
    });

    // ============================================
    // RUTAS DE BENEFICIARIO
    // ============================================
    Route::middleware(['auth', 'role:beneficiario'])->prefix('beneficiario')->name('beneficiario.')->group(function () {
        Route::get('/dashboard', function () {
            return view('beneficiario.dashboard');
        })->name('dashboard');

        // Créditos
        Route::get('/creditos', [\App\Http\Controllers\Beneficiario\CreditoController::class, 'index'])->name('creditos.index');
        
        // Agendamiento de clases
        Route::get('/agendamiento', [\App\Http\Controllers\Beneficiario\AgendamientoController::class, 'index'])->name('agendamiento.index');
        Route::get('/agendamiento/instructores', [\App\Http\Controllers\Beneficiario\AgendamientoController::class, 'obtenerInstructores'])->name('agendamiento.instructores');
        Route::get('/agendamiento/disponibilidad', [\App\Http\Controllers\Beneficiario\AgendamientoController::class, 'obtenerDisponibilidad'])->name('agendamiento.disponibilidad');
        Route::post('/agendamiento', [\App\Http\Controllers\Beneficiario\AgendamientoController::class, 'store'])->name('agendamiento.store');
        
        // Mis citas
        Route::get('/citas', [\App\Http\Controllers\Beneficiario\AgendamientoController::class, 'misCitas'])->name('citas.index');
        Route::post('/citas/{id}/cancelar', [\App\Http\Controllers\Beneficiario\AgendamientoController::class, 'cancelar'])->name('citas.cancelar');
        
        // Historial de clases
        Route::get('/historial', [\App\Http\Controllers\Beneficiario\AgendamientoController::class, 'historial'])->name('historial.index');
    });
});