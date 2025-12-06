<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Compras</title>
    <style>
        /* Estilos para el PDF en formato horizontal */
        @page {
            margin: 15px;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #333;
            margin: 0;
            padding: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #4F46E5;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #4F46E5;
            margin: 0 0 5px 0;
            font-size: 20px;
        }
        .header p {
            margin: 2px 0;
            color: #666;
            font-size: 9px;
        }
        .filters {
            background-color: #f3f4f6;
            padding: 8px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .filters strong {
            color: #4F46E5;
        }
        .stats {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .stat-item {
            display: table-cell;
            text-align: center;
            padding: 8px;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
        }
        .stat-label {
            font-size: 9px;
            color: #666;
            display: block;
        }
        .stat-value {
            font-size: 16px;
            font-weight: bold;
            color: #4F46E5;
            display: block;
            margin-top: 3px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th {
            background-color: #4F46E5;
            color: white;
            padding: 6px 4px;
            text-align: left;
            font-weight: bold;
            font-size: 9px;
        }
        table td {
            padding: 5px 4px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 9px;
        }
        table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 8px;
            font-weight: bold;
        }
        .badge-pendiente {
            background-color: #fef3c7;
            color: #92400e;
        }
        .badge-completada {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-rechazada {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #999;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    {{-- Encabezado --}}
    <div class="header">
        <h1>Academia Armonía</h1>
        <p>Listado de Compras - {{ $cliente->user->name }}</p>
        <p>Generado el {{ date('d/m/Y H:i') }}</p>
    </div>

    {{-- Filtros aplicados --}}
    @if($filtros['fecha_inicio'] || $filtros['fecha_fin'] || $filtros['estado'] || $filtros['monto_min'] || $filtros['monto_max'])
    <div class="filters">
        <strong>Filtros aplicados:</strong>
        @if($filtros['fecha_inicio'])
            Desde: {{ \Carbon\Carbon::parse($filtros['fecha_inicio'])->format('d/m/Y') }}
        @endif
        @if($filtros['fecha_fin'])
            Hasta: {{ \Carbon\Carbon::parse($filtros['fecha_fin'])->format('d/m/Y') }}
        @endif
        @if($filtros['estado'])
            Estado: {{ $filtros['estado'] }}
        @endif
        @if($filtros['monto_min'])
            Monto mín: {{ $filtros['monto_min'] }} Bs
        @endif
        @if($filtros['monto_max'])
            Monto máx: {{ $filtros['monto_max'] }} Bs
        @endif
    </div>
    @endif

    {{-- Estadísticas --}}
    <div class="stats">
        <div class="stat-item">
            <span class="stat-label">Total Compras</span>
            <span class="stat-value">{{ $totalCompras }}</span>
        </div>
        <div class="stat-item">
            <span class="stat-label">Total Gastado</span>
            <span class="stat-value">{{ number_format($totalGastado, 2) }} Bs</span>
        </div>
        <div class="stat-item">
            <span class="stat-label">Total Minutos</span>
            <span class="stat-value">{{ $totalMinutos }}</span>
        </div>
    </div>

    {{-- Tabla de compras --}}
    <table>
        <thead>
            <tr>
                <th style="width: 8%;">ID</th>
                <th style="width: 15%;">Fecha</th>
                <th style="width: 12%;">Minutos</th>
                <th style="width: 10%;">Desc.</th>
                <th style="width: 15%;">Total</th>
                <th style="width: 12%;">Estado</th>
                <th style="width: 10%;">Benef.</th>
                <th style="width: 18%;">Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($compras as $compra)
            <tr>
                <td>#{{ $compra->id }}</td>
                <td>{{ $compra->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $compra->minutos_totales }} min</td>
                <td>{{ $compra->porcentaje_descuento }}%</td>
                <td style="font-weight: bold;">{{ number_format($compra->total, 2) }} Bs</td>
                <td>
                    <span class="badge badge-{{ strtolower($compra->estado) }}">
                        {{ $compra->estado }}
                    </span>
                </td>
                <td style="text-align: center;">{{ $compra->distribuciones->count() }}</td>
                <td style="font-size: 8px;">{{ $compra->observaciones ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 20px;">
                    No se encontraron compras con los filtros aplicados
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pie de página --}}
    <div class="footer">
        <p>Este es un documento generado automáticamente por el sistema de Academia Armonía</p>
        <p>Para consultas: info@academiarmonia.com | Tel: (591) 2-1234567</p>
    </div>
</body>
</html>
