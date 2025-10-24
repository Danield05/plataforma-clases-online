<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ingresos por Período - <?= date('d/m/Y H:i'); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 20px;
        }

        .reporte-container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #371783 0%, #8B5A96 100%);
            color: white;
            text-align: center;
            padding: 40px 20px;
            position: relative;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .header h3 {
            font-size: 1.2em;
            font-weight: 300;
        }

        .fecha-reporte {
            text-align: right;
            padding: 20px 30px;
            background: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            color: #5a5c69;
            font-size: 0.9em;
        }

        .content {
            padding: 30px;
        }

        .periodo-info {
            background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
            border-left: 4px solid #371783;
        }

        .periodo-info h3 {
            color: #371783;
            margin-bottom: 10px;
        }

        .estadisticas {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border: 1px solid #e3e6f0;
        }

        .stat-card h4 {
            font-size: 1.1em;
            margin-bottom: 15px;
            color: #5a5c69;
            font-weight: 600;
        }

        .stat-card .value {
            font-size: 2em;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-card.ingresos .value { color: #1cc88a; }
        .stat-card.promedio .value { color: #36b9cc; }
        .stat-card.transacciones .value { color: #f6c23e; }

        .chart-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .chart-placeholder {
            height: 300px;
            background: linear-gradient(135deg, #f8f9fc 0%, #e9ecef 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 1.1em;
            margin-bottom: 20px;
        }

        .table-container {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: linear-gradient(135deg, #371783 0%, #8B5A96 100%);
            color: white;
            padding: 15px 12px;
            text-align: center;
            font-weight: 600;
        }

        td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #e3e6f0;
        }

        tr:nth-child(even) {
            background-color: #f8f9fc;
        }

        .ingresos-amount {
            font-weight: 700;
            color: #1cc88a;
            font-size: 1.1em;
        }

        .actions {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            border-top: 1px solid #e3e6f0;
            background: #f8f9fc;
        }

        .print-button {
            background: linear-gradient(135deg, #371783 0%, #8B5A96 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 10px;
            min-width: 200px;
            display: inline-block;
            text-decoration: none;
        }

        .print-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            margin: 0 10px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 0.9em;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        @media print {
            .no-print { display: none !important; }
            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .reporte-container {
                box-shadow: none !important;
                border-radius: 0 !important;
                max-width: none !important;
                margin: 0 !important;
            }
            .header {
                background: #371783 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .btn {
                display: none !important;
            }
            .actions {
                display: none !important;
            }
            .print-button {
                display: none !important;
            }
            table { page-break-inside: avoid; }
            .stat-card {
                break-inside: avoid;
                page-break-inside: avoid;
            }
        }

        @media (max-width: 768px) {
            .estadisticas {
                grid-template-columns: 1fr;
            }

            .content {
                padding: 20px;
            }

            .header h1 {
                font-size: 1.8em;
            }

            table {
                font-size: 0.8em;
            }
        }
    </style>
</head>
<body>
    <div class="reporte-container">
        <div class="header">
            <h1>💰 Reporte de Ingresos por Período</h1>
            <h3>Plataforma de Clases Online</h3>
        </div>

        <div class="fecha-reporte">
            📅 Generado el: <?= date('d/m/Y \a \l\a\s H:i:s'); ?>
        </div>

        <div class="content">
            <!-- Información del período -->
            <div class="periodo-info">
                <h3>📊 Período: <?= ucfirst($periodo); ?></h3>
                <p>Este reporte muestra los ingresos agrupados por período <?= $periodo ?>.</p>
            </div>

            <?php if (!empty($ingresos)): ?>
            <!-- Estadísticas generales -->
            <div class="estadisticas">
                <div class="stat-card ingresos">
                    <h4>💵 Total Ingresos</h4>
                    <div class="value">$<?= number_format(array_sum(array_column($ingresos, 'ingresos')), 2); ?></div>
                </div>
                <div class="stat-card promedio">
                    <h4>📈 Promedio por Período</h4>
                    <div class="value">$<?= number_format(array_sum(array_column($ingresos, 'ingresos')) / count($ingresos), 2); ?></div>
                </div>
                <div class="stat-card transacciones">
                    <h4>🔢 Total Transacciones</h4>
                    <div class="value"><?= array_sum(array_column($ingresos, 'cantidad')); ?></div>
                </div>
            </div>

            <!-- Gráfico placeholder -->
            <div class="chart-container">
                <h4 style="text-align: center; color: #371783; margin-bottom: 20px;">📊 Evolución de Ingresos</h4>
                <div class="chart-placeholder">
                    📈 Gráfico de ingresos por período
                    <br><small>(Se implementaría con librerías como Chart.js o similar)</small>
                </div>
            </div>

            <!-- Tabla de ingresos por período -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>📅 Período</th>
                            <th>💰 Ingresos</th>
                            <th>🔢 Transacciones</th>
                            <th>📊 Promedio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($ingresos as $ingreso): ?>
                            <tr>
                                <td><?= htmlspecialchars($ingreso['periodo']); ?></td>
                                <td><span class="ingresos-amount">$<?= number_format($ingreso['ingresos'], 2); ?></span></td>
                                <td><?= $ingreso['cantidad']; ?></td>
                                <td>$<?= number_format($ingreso['ingresos'] / ($ingreso['cantidad'] ?: 1), 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div style="text-align: center; padding: 50px; color: #6c757d;">
                <h3>📊 No hay datos de ingresos para mostrar</h3>
                <p>No se encontraron ingresos en el período seleccionado.</p>
            </div>
            <?php endif; ?>
        </div>

        <div class="actions">
            <div style="display: flex; gap: 15px; justify-content: center; align-items: center; flex-wrap: wrap;">
                <button onclick="guardarComoPDF()" class="print-button">
                    📄 Imprimir PDF
                </button>
                <button onclick="window.print()" class="print-button" style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%);">
                    🖨️ Imprimir Físico
                </button>
                <a href="/plataforma-clases-online/reportes/ingresos" class="btn btn-secondary">
                    ← Volver a Reportes
                </a>
            </div>
        </div>

        <script>
        function guardarComoPDF() {
            try {
                window.print();
            } catch (error) {
                alert('Para imprimir PDF:\n\n' +
                      '1. Presiona Ctrl+P (Cmd+P en Mac)\n' +
                      '2. Selecciona "Guardar como PDF" o "Exportar a PDF"\n' +
                      '3. Elige la ubicación y guarda el archivo');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                    e.preventDefault();
                    guardarComoPDF();
                }
            });
        });
        </script>
    </div>
</body>
</html>