<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🏠 Dashboard - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/home.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php
    // Definir la página actual para el header
    $currentPage = 'home';
    ?>
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">🏠 Dashboard</h1>
            <?php include __DIR__ . '/nav.php'; ?>
        </div>
    </header>

    <main class="container my-5">
        <!-- Hero Section -->
        <div class="dashboard-hero">
            <div class="hero-content">
                <h1 class="hero-title">🎓 Bienvenido al Dashboard</h1>
                <p class="hero-subtitle">Gestiona tu plataforma de clases online de manera eficiente</p>
            </div>
        </div>

        <!-- Estadísticas en 3 columnas balanceadas -->
        <div class="dashboard-grid">
            <div class="metric-card">
                <div class="metric-icon">👨‍🏫</div>
                <div class="metric-value"><?php echo $data['estadisticas']['totalProfesores'] ?? 0; ?></div>
                <div class="metric-label">Profesores Registrados</div>
            </div>

            <div class="metric-card">
                <div class="metric-icon">👨‍🎓</div>
                <div class="metric-value"><?php echo $data['estadisticas']['totalEstudiantes'] ?? 0; ?></div>
                <div class="metric-label">Estudiantes Activos</div>
            </div>

            <div class="metric-card">
                <div class="metric-icon">📅</div>
                <div class="metric-value"><?php echo $data['estadisticas']['reservasActivas'] ?? 0; ?></div>
                <div class="metric-label">Reservas Activas</div>
            </div>
        </div>

        <!-- Layout principal en 3 columnas: Actividad + Acciones + Finanzas -->
        <div class="three-column-layout">
            <!-- Columna Principal: Actividad Reciente -->
            <div class="activity-feed">
                <h3 class="activity-title">🕐 Actividad Reciente</h3>
                <div class="activity-timeline">
                    <div class="activity-item">
                        <div class="activity-time">Hace 2 horas</div>
                        <div class="activity-text">Nueva reserva creada por Juan Pérez con María González</div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-time">Hace 4 horas</div>
                        <div class="activity-text">Carlos Rodríguez actualizó su horario de disponibilidad</div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-time">Hace 1 día</div>
                        <div class="activity-text">Ana Martínez recibió una nueva calificación (5⭐)</div>
                    </div>
                </div>
            </div>

            <!-- Columna Lateral 1: Acciones Rápidas -->
            <div class="quick-actions">
                <h3 class="activity-title">⚡ Acciones Rápidas</h3>
                <a href="/plataforma-clases-online/home/profesores" class="action-button">
                    👨‍🏫 Gestionar Profesores
                </a>
                <a href="/plataforma-clases-online/home/estudiantes" class="action-button">
                    👨‍🎓 Gestionar Estudiantes
                </a>
                <a href="/plataforma-clases-online/home/reservas" class="action-button">
                    📅 Ver Reservas
                </a>
                <a href="/plataforma-clases-online/home/disponibilidad" class="action-button">
                    🕒 Gestionar Horarios
                </a>
            </div>

            <!-- Columna Lateral 2: Resumen Financiero -->
            <div class="quick-actions">
                <h3 class="activity-title">💰 Resumen Financiero</h3>
                <div class="stats-highlight mb-3">
                    <p class="highlight-number">$<?php echo number_format($data['estadisticas']['ingresosMensuales'] ?? 0, 2); ?></p>
                    <p class="highlight-label">Ingresos del Mes</p>
                </div>
                <div class="stats-highlight">
                    <p class="highlight-number"><?php echo $data['estadisticas']['reservasActivas'] ?? 0; ?></p>
                    <p class="highlight-label">Clases Programadas</p>
                </div>
            </div>
        </div>

        <!-- Fila inferior: Profesores Recientes en ancho completo -->
        <div class="quick-actions">
            <h3 class="activity-title">👨‍🏫 Profesores Recientes</h3>
            <?php if (!empty($data['estadisticas']['profesoresRecientes'])): ?>
                <div class="row">
                    <?php foreach($data['estadisticas']['profesoresRecientes'] as $index => $profesor): ?>
                        <?php if($index < 6): // Mostrar máximo 6 profesores ?>
                            <div class="col-md-4 col-lg-2 mb-3">
                                <div class="profesor-info">
                                    <div class="profesor-avatar">
                                        <?php
                                        $nombres = explode(' ', htmlspecialchars($profesor['first_name'] . ' ' . $profesor['last_name']));
                                        echo strtoupper(substr($nombres[0], 0, 1) . (isset($nombres[1]) ? substr($nombres[1], 0, 1) : ''));
                                        ?>
                                    </div>
                                    <div class="profesor-details">
                                        <p class="profesor-name mb-0"><?php echo htmlspecialchars($profesor['first_name'] . ' ' . $profesor['last_name']); ?></p>
                                        <p class="profesor-level mb-0"><?php echo htmlspecialchars($profesor['academic_level'] ?? 'Sin nivel académico'); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">👨‍🏫</div>
                    <p>No hay profesores registrados aún.</p>
                    <a href="/plataforma-clases-online/home/profesores_create" class="action-button">
                        ➕ Agregar Primer Profesor
                    </a>
                </div>
            <?php endif; ?>
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
    <script src="/plataforma-clases-online/public/js/script.js"></script>
    <script src="/plataforma-clases-online/public/js/home.js?v=<?php echo time(); ?>"></script>
</body>
</html>