<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use Illuminate\Http\Request;
use Carbon\Carbon;

/**
 * Controlador para dashboard e interfaz de instructor
 */
class InstructorController extends Controller
{
    /**
     * Dashboard del instructor
     */
    public function dashboard()
    {
        $instructor = auth()->user()->instructor;
        
        // Citas de hoy
        $citasHoy = Cita::where('instructor_id', $instructor->id)
            ->whereDate('fecha_hora', today())
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->with(['beneficiario.user', 'instrumento'])
            ->orderBy('fecha_hora', 'asc')
            ->get();
        
        // Próximas citas (próximos 7 días)
        $proximasCitas = Cita::where('instructor_id', $instructor->id)
            ->where('fecha_hora', '>', now())
            ->where('fecha_hora', '<=', now()->addDays(7))
            ->where('estado', 'confirmada')
            ->with(['beneficiario.user', 'instrumento'])
            ->orderBy('fecha_hora', 'asc')
            ->limit(5)
            ->get();
        
        // Estadísticas del mes
        $citasEsteMes = Cita::where('instructor_id', $instructor->id)
            ->whereMonth('fecha_hora', now()->month)
            ->whereYear('fecha_hora', now()->year)
            ->get();
        
        $totalClasesEsteMes = $citasEsteMes->where('estado', 'completada')->count();
        $horasEsteMes = $citasEsteMes->where('estado', 'completada')->sum('duracion_minutos') / 60;
        $pendientesConfirmar = $citasEsteMes->where('estado', 'pendiente')->count();
        
        return view('instructor.dashboard', compact(
            'citasHoy',
            'proximasCitas',
            'totalClasesEsteMes',
            'horasEsteMes',
            'pendientesConfirmar'
        ));
    }

    /**
     * Ver todas mis citas
     */
    public function misCitas(Request $request)
    {
        $instructor = auth()->user()->instructor;
        
        $query = Cita::where('instructor_id', $instructor->id)
            ->with(['beneficiario.user', 'instrumento']);
        
        // Filtros
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        
        if ($request->filled('fecha')) {
            $query->whereDate('fecha_hora', $request->fecha);
        }
        
        $citas = $query->orderBy('fecha_hora', 'desc')->paginate(15);
        
        return view('instructor.citas.index', compact('citas'));
    }

    /**
     * Marcar clase como completada
     */
    public function marcarCompletada($id)
    {
        $instructor = auth()->user()->instructor;
        
        $cita = Cita::where('id', $id)
            ->where('instructor_id', $instructor->id)
            ->firstOrFail();
        
        if ($cita->estado !== 'confirmada') {
            return back()->with('error', 'Solo se pueden completar clases confirmadas.');
        }
        
        $cita->estado = 'completada';
        $cita->save();
        
        return back()->with('success', 'Clase marcada como completada. Ahora aparecerá en el historial del estudiante.');
    }
}
