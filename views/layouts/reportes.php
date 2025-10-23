<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php
    // Definir la página actual para el header
    $currentPage = 'reportes';
    ?>
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">📊 Reportes y Análisis</h1>
            <?php include __DIR__ . '/nav.php'; ?>
        </div>
    </header>

    <main class="container my-5">
        <!-- Filtros de Reporte -->
        <div class="dashboard-card mb-4">
            <div class="card-header">
                <h3>🔍 Filtros de Reporte</h3>
            </div>
            <div class="card-body">
                <form class="row g-3" action="/plataforma-clases-online/reportes/profesor" method="GET">
                    <div class="col-md-3">
                        <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio">
                    </div>
                    <div class="col-md-3">
                        <label for="fecha_fin" class="form-label">Fecha Fin</label>
                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
                    </div>
                    <div class="col-md-3">
                        <label for="tipo_reporte" class="form-label">Tipo de Reporte</label>
                        <select class="form-select" id="tipo_reporte" name="tipo_reporte">
                            <option value="general">General</option>
                            <option value="financiero">Financiero</option>
                            <option value="estudiantes">Estudiantes</option>
                            <option value="clases">Clases</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            📊 Generar Reporte
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Estadísticas Generales -->
        <div class="row g-4 mb-4">
            <div class="col-lg-3">
                <div class="dashboard-card text-center">
                    <div class="stat-icon">📅</div>
                    <div class="stat-value text-primary"><?php echo $reportes['total_clases'] ?? 0; ?></div>
                    <p class="stat-label">Total Clases</p>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="dashboard-card text-center">
                    <div class="stat-icon">🎓</div>
                    <div class="stat-value text-success"><?php echo $reportes['total_estudiantes'] ?? 0; ?></div>
                    <p class="stat-label">Estudiantes Activos</p>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="dashboard-card text-center">
                    <div class="stat-icon">💰</div>
                    <div class="stat-value text-info">$<?php echo number_format($reportes['ingresos_totales'] ?? 0, 2); ?></div>
                    <p class="stat-label">Ingresos Totales</p>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="dashboard-card text-center">
                    <div class="stat-icon">⭐</div>
                    <div class="stat-value text-warning"><?php echo number_format($reportes['calificacion_promedio'] ?? 0, 1); ?></div>
                    <p class="stat-label">Calificación Promedio</p>
                </div>
            </div>
        </div>

        <!-- Gráficos y Análisis -->
        <div class="row g-4">
            <!-- Clases por Mes -->
            <div class="col-lg-6">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h4>📈 Clases por Mes</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="clasesChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Ingresos por Mes -->
            <div class="col-lg-6">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h4>💵 Ingresos por Mes</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="ingresosChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Top Estudiantes -->
            <div class="col-lg-6">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h4>🎓 Estudiantes Más Activos</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($reportes['top_estudiantes'])): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Estudiante</th>
                                            <th>Clases Tomadas</th>
                                            <th>Última Clase</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach(array_slice($reportes['top_estudiantes'], 0, 5) as $est): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($est['nombre']); ?></td>
                                                <td><?php echo $est['clases']; ?></td>
                                                <td><?php echo date('d/m/Y', strtotime($est['ultima_clase'])); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-icon">📊</div>
                                <p>No hay datos de estudiantes disponibles</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Calificaciones Recientes -->
            <div class="col-lg-6">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h4>⭐ Calificaciones Recientes</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($reportes['calificaciones_recientes'])): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Estudiante</th>
                                            <th>Calificación</th>
                                            <th>Fecha</th>
                                            <th>Comentario</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach(array_slice($reportes['calificaciones_recientes'], 0, 5) as $cal): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($cal['estudiante']); ?></td>
                                                <td>
                                                    <span class="badge bg-warning"><?php echo $cal['rating']; ?>/5</span>
                                                </td>
                                                <td><?php echo date('d/m/Y', strtotime($cal['fecha'])); ?></td>
                                                <td><?php echo htmlspecialchars(substr($cal['comentario'], 0, 30)) . '...'; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-icon">⭐</div>
                                <p>No hay calificaciones recientes</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exportar Reporte -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h4>📤 Exportar Reporte</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <button class="btn btn-outline-primary w-100" onclick="exportarReporte('pdf')">
                                    📄 PDF
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-outline-success w-100" onclick="exportarReporte('excel')">
                                    📊 Excel
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-outline-info w-100" onclick="exportarReporte('csv')">
                                    📈 CSV
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-outline-secondary w-100" onclick="exportarReporte('email')">
                                    📧 Email
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="modern-footer">
        <div class="footer-content">
            <div class="footer-info">
                <div class="footer-brand">
                    <span>💎</span>
                    <span>Plataforma Clases Online</span>
                </div>
                <div class="footer-links">
                    <a href="#privacidad">Privacidad</a>
                    <a href="#terminos">Términos</a>
                    <a href="#soporte">Soporte</a>
                    <a href="#contacto">Contacto</a>
                </div>
            </div>
            <div class="footer-copy">
                © <?php echo date('Y'); ?> Plataforma Clases Online. Todos los derechos reservados.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="/plataforma-clases-online/public/js/script.js"></script>
    <script>
        // Gráfico de clases por mes
        const clasesCtx = document.getElementById('clasesChart').getContext('2d');
        new Chart(clasesCtx, {
            type: 'line',
            data: {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                datasets: [{
                    label: 'Clases',
                    data: [12, 19, 15, 25, 22, 30],
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            }
        });

        // Gráfico de ingresos por mes
        const ingresosCtx = document.getElementById('ingresosChart').getContext('2d');
        new Chart(ingresosCtx, {
            type: 'bar',
            data: {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                datasets: [{
                    label: 'Ingresos ($)',
                    data: [1200, 1900, 1500, 2500, 2200, 3000],
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            }
        });

        // Función para exportar reportes
        function exportarReporte(tipo) {
            // Obtener filtros del formulario
            const fechaInicio = document.getElementById('fecha_inicio').value;
            const fechaFin = document.getElementById('fecha_fin').value;
            const tipoReporte = document.getElementById('tipo_reporte').value;

            // Construir URL con parámetros - usar el controlador de reportes
            let url = `/plataforma-clases-online/reportes/exportar?tipo=${tipo}&tipo_reporte=${tipoReporte}`;
            
            if (fechaInicio) url += `&fecha_inicio=${fechaInicio}`;
            if (fechaFin) url += `&fecha_fin=${fechaFin}`;

            switch(tipo) {
                case 'pdf':
                    // Abrir en nueva ventana para ver y poder imprimir/guardar como PDF
                    window.open(url, '_blank');
                    break;
                case 'excel':
                case 'csv':
                    // Descargar archivo
                    const link = document.createElement('a');
                    link.href = url;
                    link.download = '';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    break;
                case 'email':
                    // Enviar por email usando AJAX
                    enviarPorEmail(url);
                    break;
            }
        }

        // Función para enviar reporte por email
        function enviarPorEmail(url) {
            if (confirm('¿Desea enviar el reporte por email?')) {
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Reporte enviado exitosamente por email');
                    } else {
                        alert('Error al enviar el reporte: ' + (data.message || 'Error desconocido'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al enviar el reporte por email');
                });
            }
        }
    </script>
</body>
</html>