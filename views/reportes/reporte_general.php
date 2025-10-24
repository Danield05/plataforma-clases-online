<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte General Administrativo - <?= date('d/m/Y H:i'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
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
            padding: 0;
            min-height: 100vh;
        }

        main {
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

        .estadisticas-principales {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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

        .stat-card.profesores .value { color: #36b9cc; }
        .stat-card.estudiantes .value { color: #1cc88a; }
        .stat-card.reservas .value { color: #f6c23e; }
        .stat-card.ingresos .value { color: #e74a3b; }

        .section {
            margin-bottom: 40px;
        }

        .section h3 {
            color: #371783;
            margin-bottom: 20px;
            font-size: 1.5em;
            border-bottom: 2px solid #8B5A96;
            padding-bottom: 10px;
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
            .estadisticas-principales {
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
    <?php
    // Definir la página actual para el header
    $currentPage = 'reportes';
    ?>
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">📊 Reportes</h1>
            <?php include __DIR__ . '/../layouts/nav.php'; ?>
        </div>
    </header>

    <main>
        <div class="reporte-container" style="margin-top: 2rem;">
        <div class="reporte-header" style="background: linear-gradient(135deg, #371783 0%, #8B5A96 100%); color: white; text-align: center; padding: 40px 20px; position: relative; border-radius: 15px; overflow: hidden;">
            <h1 style="font-size: 2.5em; margin-bottom: 10px;">📊 Reporte General Administrativo</h1>
            <h3 style="font-size: 1.2em; font-weight: 300;">Plataforma de Clases Online</h3>
        </div>

        <div class="fecha-reporte">
            📅 Generado el: <?= date('d/m/Y \a \l\a\s H:i:s'); ?>
        </div>

        <div class="content">
            <!-- Estadísticas Principales -->
            <div class="estadisticas-principales">
                <div class="stat-card profesores">
                    <h4>👨‍🏫 Total Profesores</h4>
                    <div class="value"><?php echo $estadisticas['total_profesores']; ?></div>
                </div>
                <div class="stat-card estudiantes">
                    <h4>👥 Total Estudiantes</h4>
                    <div class="value"><?php echo $estadisticas['total_estudiantes']; ?></div>
                </div>
                <div class="stat-card reservas">
                    <h4>📚 Total Reservas</h4>
                    <div class="value"><?php echo $estadisticas['total_reservas']; ?></div>
                </div>
                <div class="stat-card ingresos">
                    <h4>💰 Ingresos Totales</h4>
                    <div class="value">$<?= number_format($estadisticas['total_ingresos'], 2); ?></div>
                </div>
            </div>

            <!-- Estadísticas de Profesores -->
            <div class="section">
                <h3>📈 Rendimiento por Profesor</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>👨‍🏫 Profesor</th>
                                <th>📚 Clases</th>
                                <th>💰 Ingresos</th>
                                <th>👥 Estudiantes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($profesorStats as $profesor): ?>
                                <tr>
                                    <td><?= htmlspecialchars($profesor['nombre']); ?></td>
                                    <td><?= $profesor['clases']; ?></td>
                                    <td><strong>$<?= number_format($profesor['ingresos'], 2); ?></strong></td>
                                    <td><?= $profesor['estudiantes']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Estadísticas de Estudiantes -->
            <div class="section">
                <h3>📈 Actividad por Estudiante</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>👤 Estudiante</th>
                                <th>📚 Clases</th>
                                <th>💰 Invertido</th>
                                <th>👨‍🏫 Profesores</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($estudianteStats as $estudiante): ?>
                                <tr>
                                    <td><?= htmlspecialchars($estudiante['nombre']); ?></td>
                                    <td><?= $estudiante['clases']; ?></td>
                                    <td><strong>$<?= number_format($estudiante['invertido'], 2); ?></strong></td>
                                    <td><?= $estudiante['profesores']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="actions">
            <div style="display: flex; gap: 15px; justify-content: center; align-items: center; flex-wrap: wrap;">
                <button onclick="guardarComoPDF()" class="print-button">
                    📄 Imprimir PDF
                </button>
                <button onclick="window.print()" class="print-button" style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%);">
                    🖨️ Imprimir Físico
                </button>
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
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/plataforma-clases-online/public/js/script.js"></script>
</body>
</html>