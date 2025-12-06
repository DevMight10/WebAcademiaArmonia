<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Controlador para gestión de citas por coordinador
 */
class CitaController extends Controller
{
    /**
     * Ver todas las citas pendientes
     */
    public function index(Request $request)
    {
        $query = Cita::with(['beneficiario.user', 'instructor.user', 'instrumento']);
        
        // Filtros
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        } else {
            // Por defecto mostrar pendientes
            $query->where('estado', 'pendiente');
        }
        
        if ($request->filled('fecha')) {
            $query->whereDate('fecha_hora', $request->fecha);
        }
        
        if ($request->filled('instructor_id')) {
            $query->where('instructor_id', $request->instructor_id);
        }
        
        $citas = $query->orderBy('fecha_hora', 'asc')->paginate(20);
        
        // Estadísticas
        $pendientes = Cita::where('estado', 'pendiente')->count();
        $confirmadas = Cita::where('estado', 'confirmada')->count();
        
        return view('coordinador.citas.index', compact('citas', 'pendientes', 'confirmadas'));
    }

    /**
     * Confirmar una cita
     */
    public function confirmar($id)
    {
        $cita = Cita::findOrFail($id);
        
        if ($cita->estado !== 'pendiente') {
            return back()->with('error', 'Solo se pueden confirmar citas pendientes.');
        }
        
        $cita->estado = 'confirmada';
        $cita->save();
        
        return back()->with('success', 'Cita confirmada exitosamente.');
    }

    /**
     * Rechazar una cita
     */
    public function rechazar(Request $request, $id)
    {
        $request->validate([
            'motivo' => 'required|string|max:500'
        ]);
        
        $cita = Cita::findOrFail($id);
        
        if ($cita->estado !== 'pendiente') {
            return back()->with('error', 'Solo se pueden rechazar citas pendientes.');
        }
        
        DB::beginTransaction();
        try {
            // Devolver créditos al beneficiario
            $cita->distribucionCredito->minutos_disponibles += $cita->minutos_consumidos;
            $cita->distribucionCredito->save();
            
            // Cambiar estado y agregar observación
            $cita->estado = 'cancelada';
            $cita->observaciones = 'Rechazada por coordinador: ' . $request->motivo;
            $cita->save();
            
            DB::commit();
            
            return back()->with('success', 'Cita rechazada. Los créditos han sido devueltos al beneficiario.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al rechazar la cita.');
        }
    }

    /**
     * Ver calendario de citas
     */
    public function calendario(Request $request)
    {
        $mes = $request->input('mes', now()->month);
        $anio = $request->input('anio', now()->year);
        
        // Obtener todas las citas del mes
        $citas = Cita::with(['beneficiario.user', 'instructor.user', 'instrumento'])
            ->whereYear('fecha_hora', $anio)
            ->whereMonth('fecha_hora', $mes)
            ->whereIn('estado', ['pendiente', 'confirmada', 'completada'])
            ->orderBy('fecha_hora', 'asc')
            ->get();
        
        // Agrupar por día
        $citasPorDia = $citas->groupBy(function($cita) {
            return Carbon::parse($cita->fecha_hora)->format('Y-m-d');
        });
        
        return view('coordinador.calendario.index', compact('citasPorDia', 'mes', 'anio'));
    }

    /**
     * Marcar cita como completada
     */
    public function completar($id)
    {
        $cita = Cita::findOrFail($id);
        
        if ($cita->estado !== 'confirmada') {
            return back()->with('error', 'Solo se pueden completar citas confirmadas.');
        }
        
        $cita->estado = 'completada';
        $cita->save();
        
        return back()->with('success', 'Cita marcada como completada.');
    }
}
