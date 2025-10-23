<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Profesor - <?= date('d/m/Y H:i'); ?></title>
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

        .stat-card.ingresos .value { color: #1cc88a; }
        .stat-card.clases .value { color: #36b9cc; }
        .stat-card.calificacion .value { color: #f6c23e; }

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
            box-shadow: 0 4px 15px rgba(55, 23, 131, 0.3);
            position: relative;
            overflow: hidden;
        }

        .print-button:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .print-button:hover:before {
            left: 100%;
        }

        .print-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }

        .print-button:active {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .print-button:nth-child(2) {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        }

        .print-button:nth-child(2):hover {
            background: linear-gradient(135deg, #5a6268 0%, #343a40 100%);
        }

        kbd {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.2rem;
            box-shadow: 0 0.1rem 0.1rem rgba(0, 0, 0, 0.1);
            color: #495057;
            font-size: 0.8em;
            padding: 0.2rem 0.4rem;
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
            <h1>📊 Reporte de Profesor</h1>
            <h3><?= htmlspecialchars(($userData['first_name'] ?? 'Usuario') . ' ' . ($userData['last_name'] ?? 'Desconocido')); ?></h3>
        </div>

        <div class="fecha-reporte">
            📅 Generado el: <?= date('d/m/Y \a \l\a\s H:i:s'); ?>
            <?php if (!empty($filtros['fecha_inicio']) || !empty($filtros['fecha_fin'])): ?>
                <br><small>Período: <?= !empty($filtros['fecha_inicio']) ? date('d/m/Y', strtotime($filtros['fecha_inicio'])) : 'Inicio'; ?> - <?= !empty($filtros['fecha_fin']) ? date('d/m/Y', strtotime($filtros['fecha_fin'])) : 'Actual'; ?></small>
            <?php endif; ?>
        </div>

        <div class="content">
            <div class="estadisticas">
                <div class="stat-card clases">
                    <h4>📅 Total Clases</h4>
                    <div class="value"><?php echo isset($estadisticas['total_clases']) ? $estadisticas['total_clases'] : 0; ?></div>
                </div>
                <div class="stat-card ingresos">
                    <h4>💰 Ingresos Totales</h4>
                    <div class="value">$<?= number_format(isset($estadisticas['ingresos_totales']) ? $estadisticas['ingresos_totales'] : 0, 2); ?></div>
                </div>
                <div class="stat-card calificacion">
                    <h4>⭐ Calificación Promedio</h4>
                    <div class="value"><?php echo number_format(isset($estadisticas['calificacion_promedio']) ? $estadisticas['calificacion_promedio'] : 0, 1); ?>/5</div>
                </div>
            </div>

            <?php if (!empty($clases)): ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>👤 Estudiante</th>
                            <th>📚 Materia</th>
                            <th>📝 Tema/Clase</th>
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
                                <td><?= htmlspecialchars($clase['estudiante_name'] . ' ' . $clase['estudiante_last_name']); ?></td>
                                <td><?= htmlspecialchars($clase['subject_name'] ?? 'Materia'); ?></td>
                                <td><?= htmlspecialchars($clase['notes'] ?? 'Clase general'); ?></td>
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
            <?php else: ?>
            <div style="text-align: center; padding: 50px; color: #6c757d;">
                <h3>📚 No hay clases registradas</h3>
                <p>No se encontraron clases para mostrar en este período.</p>
            </div>
            <?php endif; ?>
        </div>

        <div class="actions">
            <div style="margin-bottom: 20px; padding: 15px; background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%); border-radius: 10px; border-left: 4px solid #371783;">
                <div style="color: #333; font-size: 1em; margin-bottom: 8px;">
                    💡 <strong>Exportar Reporte:</strong> Usa los botones abajo para descargar o enviar por email
                </div>
                <div style="color: #666; font-size: 0.9em;">
                    <strong>Opciones:</strong> PDF, Excel, CSV o Email
                </div>
            </div>
            <div style="display: flex; gap: 15px; justify-content: center; align-items: center; flex-wrap: wrap;">
                <button onclick="guardarComoPDF()" class="print-button" style="background: linear-gradient(135deg, #371783 0%, #8B5A96 100%); font-size: 1.2em; padding: 18px 35px; box-shadow: 0 6px 20px rgba(55, 23, 131, 0.4);">
                    📄 Exportar PDF
                </button>
                <button onclick="window.print()" class="print-button" style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%); font-size: 1.1em; padding: 15px 25px;">
                    🖨️ Imprimir Físico
                </button>
                <a href="/plataforma-clases-online/reportes/exportar?tipo=excel&tipo_reporte=profesor" class="print-button" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); font-size: 1.1em; padding: 15px 25px;">
                    📊 Descargar Excel
                </a>
                <a href="/plataforma-clases-online/reportes/exportar?tipo=csv&tipo_reporte=profesor" class="print-button" style="background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); font-size: 1.1em; padding: 15px 25px;">
                    📋 Descargar CSV
                </a>
                <button onclick="enviarPorEmail()" class="print-button" style="background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%); font-size: 1.1em; padding: 15px 25px;">
                    📧 Enviar por Email
                </button>
                <a href="/plataforma-clases-online/" class="btn btn-secondary" style="padding: 15px 25px;">
                    ← Volver al Inicio
                </a>
            </div>
        </div>

        <script>
        function guardarComoPDF() {
            try {
                // Abrir nueva ventana con el reporte PDF
                const fechaInicio = '<?= $filtros['fecha_inicio'] ?? ''; ?>';
                const fechaFin = '<?= $filtros['fecha_fin'] ?? ''; ?>';
                let url = '/plataforma-clases-online/reportes/exportar?tipo=pdf&tipo_reporte=profesor';
                if (fechaInicio) url += '&fecha_inicio=' + fechaInicio;
                if (fechaFin) url += '&fecha_fin=' + fechaFin;

                window.open(url, '_blank');
            } catch (error) {
                // Fallback para navegadores que no soportan window.open()
                alert('Para imprimir PDF:\n\n' +
                      '1. Presiona Ctrl+P (Cmd+P en Mac)\n' +
                      '2. Selecciona "Guardar como PDF" o "Exportar a PDF"\n' +
                      '3. Elige la ubicación y guarda el archivo');
            }
        }

        function enviarPorEmail() {
            // Obtener parámetros de la URL actual
            const urlParams = new URLSearchParams(window.location.search);
            const tipoReporte = 'profesor'; // Tipo específico para reporte de profesor
            const fechaInicio = urlParams.get('fecha_inicio') || '';
            const fechaFin = urlParams.get('fecha_fin') || '';

            // Mostrar loading
            const button = event.target;
            const originalText = button.textContent;
            button.textContent = '⏳ Enviando...';
            button.disabled = true;

            // Hacer request AJAX
            fetch('/plataforma-clases-online/reportes/exportar?tipo=email&tipo_reporte=' + tipoReporte + '&fecha_inicio=' + fechaInicio + '&fecha_fin=' + fechaFin, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✅ ' + data.message);
                } else {
                    alert('❌ Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('❌ Error al enviar el email: ' + error.message);
            })
            .finally(() => {
                // Restaurar botón
                button.textContent = originalText;
                button.disabled = false;
            });
        }

        // Agregar evento para cuando se cargue la página
        document.addEventListener('DOMContentLoaded', function() {
            // Agregar atajo de teclado Ctrl+P para PDF
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