<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Estudiante - <?= date('d/m/Y H:i'); ?></title>
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
            max-width: 1200px;
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

        .stat-card.clases .value { color: #36b9cc; }
        .stat-card.invertido .value { color: #1cc88a; }
        .stat-card.profesores .value { color: #f6c23e; }

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

        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 600;
        }

        .status-completada { background: #d1ecf1; color: #0c5460; }
        .status-pendiente { background: #fff3cd; color: #856404; }
        .status-cancelada { background: #f8d7da; color: #721c24; }

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
            <h1>📊 Reporte de Estudiante</h1>
            <h3><?= htmlspecialchars($userData['first_name'] . ' ' . $userData['last_name']); ?></h3>
        </div>

        <div class="fecha-reporte">
            📅 Generado el: <?= date('d/m/Y \a \l\a\s H:i:s'); ?>
        </div>

        <div class="content">
            <div class="estadisticas">
                <div class="stat-card clases">
                    <h4>📚 Total Clases</h4>
                    <div class="value"><?php echo $estadisticas['total_clases']; ?></div>
                </div>
                <div class="stat-card invertido">
                    <h4>💰 Total Invertido</h4>
                    <div class="value">$<?= number_format($estadisticas['total_invertido'], 2); ?></div>
                </div>
                <div class="stat-card profesores">
                    <h4>👥 Profesores Activos</h4>
                    <div class="value"><?php echo $estadisticas['profesores_activos']; ?></div>
                </div>
            </div>

            <?php if (!empty($clases)): ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>👨‍🏫 Profesor</th>
                            <th>📅 Fecha</th>
                            <th>🕒 Hora</th>
                            <th>💰 Precio</th>
                            <th>📊 Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($clases as $clase): ?>
                            <?php
                                $estado = strtolower($clase['reservation_status'] ?? 'pendiente');
                                $statusClass = match($estado) {
                                    'completada' => 'status-completada',
                                    'pendiente' => 'status-pendiente',
                                    'confirmada' => 'status-completada',
                                    'cancelada' => 'status-cancelada',
                                    default => 'status-pendiente'
                                };
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($clase['profesor_name'] . ' ' . $clase['profesor_last_name']); ?></td>
                                <td><?= htmlspecialchars($clase['class_date'] ?? 'N/A'); ?></td>
                                <td><?= htmlspecialchars($clase['start_time'] ?? 'N/A'); ?></td>
                                <td><strong>
                                    <?php
                                    $precio = $clase['hourly_rate'] ?? 0;
                                    echo '$' . number_format($precio, 2);
                                    ?>
                                </strong></td>
                                <td>
                                    <span class="status-badge <?= $statusClass; ?>">
                                        <?= ucfirst($estado); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>

        <div class="actions">
            <div style="display: flex; gap: 15px; justify-content: center; align-items: center; flex-wrap: wrap;">
                <button onclick="guardarComoPDF()" class="print-button">
                    📄 Imprimir PDF
                </button>
                <a href="/plataforma-clases-online" class="btn btn-secondary">
                    ← Volver a Dashboard Estudiante
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