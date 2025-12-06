<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Instrumento;
use App\Models\Instructor;
use App\Models\Cita;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AgendamientoClienteController extends Controller
{
    // Mostrar formulario de agendamiento
    public function index()
    {
        $cliente = auth()->user()->cliente;
        // Obtener o crear beneficiario para el cliente
        $beneficiario = $cliente->getOrCreateBeneficiario();
        
        $instrumentos = Instrumento::where('estado', true)->get();
        
        return view('cliente.agendamiento.index', compact('instrumentos', 'beneficiario'));
    }

    // Obtener instructores por instrumento (AJAX)
    public function obtenerInstructores($instrumentoId)
    {
        $instructores = Instructor::whereHas('instrumentos', function($query) use ($instrumentoId) {
            $query->where('instrumento_id', $instrumentoId);
        })->with('user')->get();

        return response()->json($instructores);
    }

    // Verificar disponibilidad de horario (AJAX)
    public function verificarDisponibilidad(Request $request)
    {
        $fecha = Carbon::parse($request->fecha . ' ' . $request->hora);
        $duracion = $request->duracion_minutos;

        // Verificar si el instructor tiene una cita en ese horario
        $citaExistente = Cita::where('instructor_id', $request->instructor_id)
            ->where('fecha_hora', $fecha)
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->exists();

        return response()->json([
            'disponible' => !$citaExistente
        ]);
    }

    // Guardar cita
    public function store(Request $request)
    {
        $request->validate([
            'instrumento_id' => 'required|exists:instrumentos,id',
            'instructor_id' => 'required|exists:instructors,id',
            'fecha' => 'required|date|after:today',
            'hora' => 'required',
            'duracion_minutos' => 'required|in:30,45,60,90,120',
            'observaciones' => 'nullable|string|max:500'
        ]);

        $cliente = auth()->user()->cliente;
        $beneficiario = $cliente->getOrCreateBeneficiario();

        // Verificar créditos disponibles
        $creditosDisponibles = $beneficiario->distribuciones()
            ->whereHas('compra', function($query) {
                $query->where('estado', 'aprobada');
            })
            ->sum('minutos_disponibles');

        if ($creditosDisponibles < $request->duracion_minutos) {
            return back()->with('error', 'No tienes suficientes créditos disponibles.');
        }

        // Crear cita
        $fechaHora = Carbon::parse($request->fecha . ' ' . $request->hora);
        
        $cita = Cita::create([
            'beneficiario_id' => $beneficiario->id,
            'instructor_id' => $request->instructor_id,
            'instrumento_id' => $request->instrumento_id,
            'fecha_hora' => $fechaHora,
            'duracion_minutos' => $request->duracion_minutos,
            'observaciones' => $request->observaciones,
            'estado' => 'pendiente'
        ]);

        // Consumir créditos
        $minutosRestantes = $request->duracion_minutos;
        $distribuciones = $beneficiario->distribuciones()
            ->whereHas('compra', function($query) {
                $query->where('estado', 'aprobada');
            })
            ->where('minutos_disponibles', '>', 0)
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($distribuciones as $dist) {
            if ($minutosRestantes <= 0) break;

            $aConsumir = min($minutosRestantes, $dist->minutos_disponibles);
            $dist->minutos_disponibles -= $aConsumir;
            $dist->save();

            $minutosRestantes -= $aConsumir;
        }

        return redirect()->route('cliente.clases.historial')
            ->with('success', 'Clase agendada exitosamente. Espera la confirmación del coordinador.');
    }

    // Ver historial de clases
    public function historial()
    {
        $cliente = auth()->user()->cliente;
        $beneficiario = $cliente->getOrCreateBeneficiario();
        
        // Todas las citas
        $citas = Cita::where('beneficiario_id', $beneficiario->id)
            ->with(['instructor.user', 'instrumento'])
            ->orderBy('fecha_hora', 'desc')
            ->paginate(15);
        
        // Clases completadas para estadísticas
        $citasCompletadas = Cita::where('beneficiario_id', $beneficiario->id)
            ->where('estado', 'completada')
            ->with(['instructor.user', 'instrumento'])
            ->get();
        
        $totalClases = $citasCompletadas->count();
        $totalMinutosUsados = $citasCompletadas->sum('duracion_minutos');
        
        // Instrumento más frecuente
        $instrumentosFrecuentes = $citasCompletadas->groupBy('instrumento_id')
            ->map(function($group) {
                return [
                    'instrumento' => $group->first()->instrumento->nombre,
                    'count' => $group->count(),
                    'minutos' => $group->sum('duracion_minutos')
                ];
            })->sortByDesc('count');
        
        return view('cliente.clases.historial', compact(
            'citas',
            'totalClases',
            'totalMinutosUsados',
            'instrumentosFrecuentes'
        ));
    }

    // Cancelar cita
    public function cancelar($id)
    {
        $cliente = auth()->user()->cliente;
        $beneficiario = $cliente->getOrCreateBeneficiario();
        
        $cita = Cita::where('id', $id)
            ->where('beneficiario_id', $beneficiario->id)
            ->firstOrFail();

        if ($cita->estado !== 'pendiente') {
            return back()->with('error', 'Solo puedes cancelar citas pendientes.');
        }

        // Devolver créditos
        $distribucion = $beneficiario->distribuciones()
            ->whereHas('compra', function($query) {
                $query->where('estado', 'aprobada');
            })
            ->orderBy('created_at', 'desc')
            ->first();

        if ($distribucion) {
            $distribucion->minutos_disponibles += $cita->duracion_minutos;
            $distribucion->save();
        }

        $cita->estado = 'cancelada';
        $cita->save();

        return back()->with('success', 'Cita cancelada y créditos devueltos.');
    }
}
