<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Compra;
use App\Exports\ComprasExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

/**
 * Controlador para generación de reportes de compras (Admin)
 * Implementa exportación a PDF y Excel con filtros
 */
class ReporteCompraController extends Controller
{
    /**
     * Mostrar vista de reportes con filtros
     */
    public function index(Request $request)
    {
        // Obtener TODAS las compras del sistema (Admin ve todo)
        $query = Compra::with(['cliente.user', 'distribuciones.beneficiario.user']);
        
        // Aplicar filtros si existen
        if ($request->filled('fecha_inicio')) {
            // Filtrar por fecha de inicio
            $query->whereDate('created_at', '>=', $request->fecha_inicio);
        }
        
        if ($request->filled('fecha_fin')) {
            // Filtrar por fecha de fin
            $query->whereDate('created_at', '<=', $request->fecha_fin);
        }
        
        if ($request->filled('estado')) {
            // Filtrar por estado de la compra
            $query->where('estado', $request->estado);
        }
        
        if ($request->filled('monto_min')) {
            // Filtrar por monto mínimo
            $query->where('total', '>=', $request->monto_min);
        }
        
        if ($request->filled('monto_max')) {
            // Filtrar por monto máximo
            $query->where('total', '<=', $request->monto_max);
        }
        
        // Ordenar por fecha descendente
        $compras = $query->orderBy('created_at', 'desc')->get();
        
        // Calcular estadísticas para el reporte
        $totalCompras = $compras->count();
        $totalGastado = $compras->sum('total');
        $totalMinutos = $compras->sum('minutos_totales');
        
        return view('admin.reportes.index', compact(
            'compras',
            'totalCompras',
            'totalGastado',
            'totalMinutos'
        ));
    }
    
    /**
     * Generar PDF de una compra específica
     */
    public function generarPDF($id)
    {
        // Obtener la compra con sus relaciones (Admin puede ver cualquier compra)
        $compra = Compra::where('id', $id)
            ->with(['distribuciones.beneficiario.user', 'cliente.user'])
            ->firstOrFail();
        
        // Generar PDF usando la vista blade
        $pdf = Pdf::loadView('admin.reportes.pdf.compra', compact('compra'));
        
        // Configurar orientación y tamaño
        $pdf->setPaper('letter', 'portrait');
        
        // Descargar el PDF con nombre descriptivo
        $nombreArchivo = 'compra_' . $compra->id . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($nombreArchivo);
    }
    
    /**
     * Generar PDF de listado de compras con filtros
     */
    public function generarListadoPDF(Request $request)
    {
        // Aplicar los mismos filtros que en index
        $query = Compra::with(['cliente.user', 'distribuciones.beneficiario.user']);
        
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('created_at', '>=', $request->fecha_inicio);
        }
        
        if ($request->filled('fecha_fin')) {
            $query->whereDate('created_at', '<=', $request->fecha_fin);
        }
        
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        
        if ($request->filled('monto_min')) {
            $query->where('total', '>=', $request->monto_min);
        }
        
        if ($request->filled('monto_max')) {
            $query->where('total', '<=', $request->monto_max);
        }
        
        $compras = $query->orderBy('created_at', 'desc')->get();
        
        // Calcular totales para el reporte
        $totalCompras = $compras->count();
        $totalGastado = $compras->sum('total');
        $totalMinutos = $compras->sum('minutos_totales');
        
        // Información de filtros aplicados
        $filtros = [
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'estado' => $request->estado,
            'monto_min' => $request->monto_min,
            'monto_max' => $request->monto_max,
        ];
        
        // Generar PDF del listado
        $pdf = Pdf::loadView('admin.reportes.pdf.listado', compact(
            'compras',
            'totalCompras',
            'totalGastado',
            'totalMinutos',
            'filtros'
        ));
        
        $pdf->setPaper('letter', 'landscape'); // Horizontal para tabla
        
        $nombreArchivo = 'listado_compras_' . date('Y-m-d_H-i-s') . '.pdf';
        
        return $pdf->download($nombreArchivo);
    }
    
    /**
     * Exportar compras a Excel
     */
    public function exportarExcel(Request $request)
    {
        // Aplicar filtros
        $query = Compra::with(['cliente.user', 'distribuciones.beneficiario.user']);
        
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('created_at', '>=', $request->fecha_inicio);
        }
        
        if ($request->filled('fecha_fin')) {
            $query->whereDate('created_at', '<=', $request->fecha_fin);
        }
        
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        
        if ($request->filled('monto_min')) {
            $query->where('total', '>=', $request->monto_min);
        }
        
        if ($request->filled('monto_max')) {
            $query->where('total', '<=', $request->monto_max);
        }
        
        $compras = $query->orderBy('created_at', 'desc')->get();
        
        // Nombre del archivo Excel
        $nombreArchivo = 'compras_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        // Exportar usando la clase ComprasExport
        return Excel::download(new ComprasExport($compras), $nombreArchivo);
    }
}
