<?php

namespace App\Http\Controllers\Beneficiario;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Instrumento;
use App\Models\Instructor;
use App\Models\DistribucionCredito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Controlador para agendamiento de clases por beneficiarios
 */
class AgendamientoController extends Controller
{
    /**
     * Mostrar formulario de agendamiento
     */
    public function index()
    {
        $beneficiario = auth()->user()->beneficiario;
        
        // Obtener instrumentos activos (estado = true en BD)
        $instrumentos = Instrumento::where('estado', true)->get();
        
        // Obtener créditos disponibles del beneficiario
        $creditos = DistribucionCredito::where('beneficiario_id', $beneficiario->id)
            ->where('minutos_disponibles', '>', 0)
            ->with('compra')
            ->get();
        
        $totalMinutosDisponibles = $creditos->sum('minutos_disponibles');
        
        return view('beneficiario.agendamiento.index', compact(
            'instrumentos',
            'creditos',
            'totalMinutosDisponibles'
        ));
    }

    /**
     * Obtener instructores disponibles para un instrumento (AJAX)
     */
    public function obtenerInstructores(Request $request)
    {
        $instrumentoId = $request->instrumento_id;
        
        // Obtener instructores que enseñan este instrumento
        $instructores = Instructor::whereHas('especialidades', function($query) use ($instrumentoId) {
            $query->where('instrumento_id', $instrumentoId);
        })->with('user')->get();
        
        return response()->json($instructores);
    }

    /**
     * Obtener horarios disponibles de un instructor (AJAX)
     */
    public function obtenerDisponibilidad(Request $request)
    {
        $instructorId = $request->instructor_id;
        $fecha = $request->fecha;
        $duracion = $request->duracion;
        
        // Obtener citas ya agendadas para ese instructor en esa fecha
        $citasOcupadas = Cita::where('instructor_id', $instructorId)
            ->whereDate('fecha_hora', $fecha)
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->get();
        
        // Generar horarios disponibles (9:00 - 20:00, cada 30 min)
        $horariosDisponibles = [];
        $inicio = Carbon::parse($fecha . ' 09:00');
        $fin = Carbon::parse($fecha . ' 20:00');
        
        while ($inicio < $fin) {
            $horarioOcupado = false;
            
            // Verificar si este horario está ocupado
            foreach ($citasOcupadas as $cita) {
                $citaInicio = Carbon::parse($cita->fecha_hora);
                $citaFin = $citaInicio->copy()->addMinutes($cita->duracion_minutos);
                
                $nuevoInicio = $inicio->copy();
                $nuevoFin = $inicio->copy()->addMinutes($duracion);
                
                // Verificar si hay traslape
                if ($nuevoInicio < $citaFin && $nuevoFin > $citaInicio) {
                    $horarioOcupado = true;
                    break;
                }
            }
            
            if (!$horarioOcupado) {
                $horariosDisponibles[] = [
                    'hora' => $inicio->format('H:i'),
                    'disponible' => true
                ];
            }
            
            $inicio->addMinutes(30);
        }
        
        return response()->json($horariosDisponibles);
    }

    /**
     * Crear nueva cita
     */
    public function store(Request $request)
    {
        $request->validate([
            'instrumento_id' => 'required|exists:instrumentos,id',
            'instructor_id' => 'required|exists:instructores,id',
            'fecha' => 'required|date|after:today',
            'hora' => 'required',
            'duracion_minutos' => 'required|in:30,45,60,90,120',
            'observaciones' => 'nullable|string|max:500'
        ]);
        
        $beneficiario = auth()->user()->beneficiario;
        
        DB::beginTransaction();
        try {
            // Verificar que el beneficiario tenga créditos suficientes
            $distribucion = DistribucionCredito::where('beneficiario_id', $beneficiario->id)
                ->where('minutos_disponibles', '>=', $request->duracion_minutos)
                ->first();
            
            if (!$distribucion) {
                return back()->with('error', 'No tienes créditos suficientes para agendar esta clase.');
            }
            
            // Crear fecha y hora completa
            $fechaHora = Carbon::parse($request->fecha . ' ' . $request->hora);
            
            // Verificar que el horario esté disponible
            $horarioOcupado = Cita::where('instructor_id', $request->instructor_id)
                ->where('fecha_hora', $fechaHora)
                ->whereIn('estado', ['pendiente', 'confirmada'])
                ->exists();
            
            if ($horarioOcupado) {
                return back()->with('error', 'Este horario ya no está disponible.');
            }
            
            // Crear la cita
            $cita = Cita::create([
                'beneficiario_id' => $beneficiario->id,
                'instructor_id' => $request->instructor_id,
                'instrumento_id' => $request->instrumento_id,
                'distribucion_credito_id' => $distribucion->id,
                'fecha_hora' => $fechaHora,
                'duracion_minutos' => $request->duracion_minutos,
                'minutos_consumidos' => $request->duracion_minutos,
                'estado' => 'pendiente',
                'observaciones' => $request->observaciones,
            ]);
            
            // Descontar créditos
            $distribucion->minutos_disponibles -= $request->duracion_minutos;
            $distribucion->save();
            
            DB::commit();
            
            return redirect()->route('beneficiario.citas.index')
                ->with('success', 'Cita agendada exitosamente. Espera la confirmación del coordinador.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al agendar la cita. Intenta nuevamente.');
        }
    }

    /**
     * Ver mis citas
     */
    public function misCitas()
    {
        $beneficiario = auth()->user()->beneficiario;
        
        $citas = Cita::where('beneficiario_id', $beneficiario->id)
            ->with(['instructor.user', 'instrumento'])
            ->orderBy('fecha_hora', 'desc')
            ->get();
        
        return view('beneficiario.citas.index', compact('citas'));
    }

    /**
     * Cancelar cita
     */
    public function cancelar($id)
    {
        $beneficiario = auth()->user()->beneficiario;
        
        $cita = Cita::where('id', $id)
            ->where('beneficiario_id', $beneficiario->id)
            ->firstOrFail();
        
        if (!$cita->puedeCancelarse()) {
            return back()->with('error', 'Solo puedes cancelar citas pendientes.');
        }
        
        DB::beginTransaction();
        try {
            // Devolver créditos
            $cita->distribucionCredito->minutos_disponibles += $cita->minutos_consumidos;
            $cita->distribucionCredito->save();
            
            // Cambiar estado
            $cita->estado = 'cancelada';
            $cita->save();
            
            DB::commit();
            
            return back()->with('success', 'Cita cancelada. Tus créditos han sido devueltos.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al cancelar la cita.');
        }
    }

    /**
     * Ver historial de clases completadas
     */
    public function historial()
    {
        $beneficiario = auth()->user()->beneficiario;
        
        // Obtener clases completadas
        $citasCompletadas = Cita::where('beneficiario_id', $beneficiario->id)
            ->where('estado', 'completada')
            ->with(['instructor.user', 'instrumento'])
            ->orderBy('fecha_hora', 'desc')
            ->get();
        
        // Calcular estadísticas
        $totalClases = $citasCompletadas->count();
        $totalMinutosUsados = $citasCompletadas->sum('minutos_consumidos');
        
        // Instrumento más estudiado
        $instrumentosFrecuentes = $citasCompletadas->groupBy('instrumento_id')
            ->map(function($grupo) {
                return [
                    'instrumento' => $grupo->first()->instrumento,
                    'cantidad' => $grupo->count(),
                    'minutos' => $grupo->sum('minutos_consumidos')
                ];
            })
            ->sortByDesc('cantidad');
        
        // Instructor favorito
        $instructoresFrecuentes = $citasCompletadas->groupBy('instructor_id')
            ->map(function($grupo) {
                return [
                    'instructor' => $grupo->first()->instructor,
                    'cantidad' => $grupo->count()
                ];
            })
            ->sortByDesc('cantidad');
        
        return view('beneficiario.historial.index', compact(
            'citasCompletadas',
            'totalClases',
            'totalMinutosUsados',
            'instrumentosFrecuentes',
            'instructoresFrecuentes'
        ));
    }
}
