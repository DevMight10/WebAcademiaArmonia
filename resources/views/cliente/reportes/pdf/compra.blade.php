<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra #{{ $compra->id }}</title>
    <style>
        /* Estilos para el PDF */
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #4F46E5;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #4F46E5;
            margin: 0 0 5px 0;
            font-size: 24px;
        }
        .header p {
            margin: 0;
            color: #666;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            background-color: #4F46E5;
            color: white;
            padding: 8px 12px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            font-weight: bold;
            padding: 5px 10px;
            width: 40%;
            background-color: #f3f4f6;
        }
        .info-value {
            display: table-cell;
            padding: 5px 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th {
            background-color: #4F46E5;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .total-box {
            background-color: #f0fdf4;
            border: 2px solid #10b981;
            padding: 15px;
            text-align: center;
            margin-top: 20px;
        }
        .total-box .label {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        .total-box .amount {
            font-size: 28px;
            font-weight: bold;
            color: #10b981;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
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
    </style>
</head>
<body>
    {{-- Encabezado del reporte --}}
    <div class="header">
        <h1>Academia Armonía</h1>
        <p>Reporte de Compra</p>
        <p style="font-size: 10px;">Generado el {{ date('d/m/Y H:i') }}</p>
    </div>

    {{-- Información de la compra --}}
    <div class="section">
        <div class="section-title">INFORMACIÓN DE LA COMPRA</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">ID de Compra:</div>
                <div class="info-value">#{{ $compra->id }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Fecha de Compra:</div>
                <div class="info-value">{{ $compra->created_at->format('d/m/Y H:i') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Estado:</div>
                <div class="info-value">
                    <span class="badge badge-{{ strtolower($compra->estado) }}">
                        {{ $compra->estado }}
                    </span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Minutos Totales:</div>
                <div class="info-value">{{ $compra->minutos_totales }} minutos</div>
            </div>
            @if($compra->porcentaje_descuento > 0)
            <div class="info-row">
                <div class="info-label">Descuento Aplicado:</div>
                <div class="info-value">{{ $compra->porcentaje_descuento }}%</div>
            </div>
            @endif
        </div>
    </div>

    {{-- Información del cliente --}}
    <div class="section">
        <div class="section-title">DATOS DEL CLIENTE</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nombre:</div>
                <div class="info-value">{{ $compra->cliente->user->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value">{{ $compra->cliente->user->email }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">CI:</div>
                <div class="info-value">{{ $compra->cliente->ci }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Teléfono:</div>
                <div class="info-value">{{ $compra->cliente->telefono }}</div>
            </div>
        </div>
    </div>

    {{-- Distribución de créditos --}}
    <div class="section">
        <div class="section-title">DISTRIBUCIÓN DE CRÉDITOS</div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Beneficiario</th>
                    <th>Email</th>
                    <th style="text-align: center;">Minutos Asignados</th>
                    <th style="text-align: center;">Minutos Disponibles</th>
                </tr>
            </thead>
            <tbody>
                @foreach($compra->distribuciones as $index => $dist)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $dist->beneficiario->user->name }}</td>
                    <td>{{ $dist->beneficiario->user->email }}</td>
                    <td style="text-align: center;">{{ $dist->minutos_asignados }} min</td>
                    <td style="text-align: center;">{{ $dist->minutos_disponibles }} min</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Total a pagar --}}
    <div class="total-box">
        <div class="label">TOTAL PAGADO</div>
        <div class="amount">{{ number_format($compra->total, 2) }} Bs</div>
    </div>

    {{-- Pie de página --}}
    <div class="footer">
        <p>Este es un documento generado automáticamente por el sistema de Academia Armonía</p>
        <p>Para consultas: info@academiarmonia.com | Tel: (591) 2-1234567</p>
    </div>
</body>
</html>
