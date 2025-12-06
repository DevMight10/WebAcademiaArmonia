<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

/**
 * Clase para exportar compras a Excel
 * Implementa formato profesional con encabezados y estilos
 */
class ComprasExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    protected $compras;
    
    public function __construct($compras)
    {
        $this->compras = $compras;
    }
    
    /**
     * Retornar colección de datos para exportar
     */
    public function collection()
    {
        // Mapear los datos de las compras al formato de Excel
        return $this->compras->map(function($compra, $index) {
            return [
                $index + 1, // Número de fila
                $compra->id, // ID de compra
                $compra->created_at->format('d/m/Y H:i'), // Fecha
                $compra->minutos_totales, // Minutos
                $compra->porcentaje_descuento . '%', // Descuento
                number_format($compra->total, 2) . ' Bs', // Total
                $compra->estado, // Estado
                $compra->distribuciones->count(), // Beneficiarios
            ];
        });
    }
    
    /**
     * Definir encabezados de las columnas
     */
    public function headings(): array
    {
        return [
            '#',
            'ID Compra',
            'Fecha',
            'Minutos',
            'Descuento',
            'Total',
            'Estado',
            'Beneficiarios',
        ];
    }
    
    /**
     * Aplicar estilos a las celdas
     */
    public function styles(Worksheet $sheet)
    {
        // Estilo para la fila de encabezados
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5'], // Color indigo
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);
        
        // Aplicar bordes a todas las celdas con datos
        $lastRow = $this->compras->count() + 1;
        $sheet->getStyle('A1:H' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);
        
        // Centrar texto en columnas específicas
        $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('B2:B' . $lastRow)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('D2:D' . $lastRow)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('E2:E' . $lastRow)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('G2:G' . $lastRow)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('H2:H' . $lastRow)->getAlignment()->setHorizontal('center');
        
        return $sheet;
    }
    
    /**
     * Definir ancho de columnas
     */
    public function columnWidths(): array
    {
        return [
            'A' => 8,  // #
            'B' => 12, // ID
            'C' => 18, // Fecha
            'D' => 12, // Minutos
            'E' => 12, // Descuento
            'F' => 15, // Total
            'G' => 15, // Estado
            'H' => 15, // Beneficiarios
        ];
    }
}
