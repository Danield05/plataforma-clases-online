<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🏠 Dashboard - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
    <style>
        .dashboard-hero {
            background: linear-gradient(135deg, #371783 0%, #8B5A96 100%);
            color: white;
            padding: 3rem 2rem;
            border-radius: 20px;
            margin-bottom: 3rem;
            position: relative;
            overflow: hidden;
        }

        .dashboard-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20"><defs><pattern id="grain" width="100" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="20" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            text-align: center;
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 0;
        }

        .activity-timeline {
            position: relative;
            padding-left: 2rem;
        }

        .activity-timeline::before {
            content: '';
            position: absolute;
            left: 1rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(135deg, #371783 0%, #8B5A96 100%);
        }

        .activity-item {
            position: relative;
            margin-bottom: 1.5rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #371783;
        }

        .activity-item::before {
            content: '';
            position: absolute;
            left: -2rem;
            top: 1.2rem;
            width: 1rem;
            height: 1rem;
            background: #371783;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 0 0 3px #371783;
        }

        .activity-time {
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .activity-text {
            font-size: 0.9rem;
            color: #333;
            margin: 0;
        }

        .profesor-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 10px;
        }

        .profesor-info {
            display: flex;
            align-items: center;
            background: rgba(55, 23, 131, 0.05);
            padding: 1rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            border: 1px solid rgba(55, 23, 131, 0.1);
        }

        .profesor-info:hover {
            background: rgba(55, 23, 131, 0.1);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(55, 23, 131, 0.15);
        }

        .profesor-details {
            flex: 1;
            margin-left: 0.75rem;
        }

        .profesor-name {
            font-weight: 600;
            color: #333;
            margin: 0;
            font-size: 0.9rem;
        }

        .profesor-level {
            font-size: 0.75rem;
            color: #666;
            margin: 0;
        }

        .stats-highlight {
            background: linear-gradient(135deg, rgba(55, 23, 131, 0.1) 0%, rgba(139, 90, 150, 0.1) 100%);
            padding: 1.5rem;
            border-radius: 16px;
            border-left: 4px solid #371783;
        }

        .highlight-number {
            font-size: 2rem;
            font-weight: 700;
            color: #371783;
            margin: 0;
        }

        .highlight-label {
            font-size: 0.9rem;
            color: #666;
            margin: 0;
        }

        .welcome-message {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .welcome-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.5rem;
        }

        .welcome-text {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.9);
            margin: 0;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .two-column-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .three-column-layout {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 2rem;
            align-items: start;
        }

        .metric-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(55, 23, 131, 0.15);
        }

        .metric-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.8;
        }

        .metric-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: #371783;
            margin-bottom: 0.5rem;
        }

        .metric-label {
            font-size: 1rem;
            color: #666;
            margin: 0;
        }

        .activity-feed {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .activity-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #371783;
            margin-bottom: 1.5rem;
        }

        .quick-actions {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .action-button {
            display: block;
            width: 100%;
            padding: 1rem;
            margin-bottom: 0.75rem;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #371783 0%, #8B5A96 100%);
            color: white;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            text-align: center;
        }

        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(55, 23, 131, 0.3);
            color: white;
            text-decoration: none;
        }

        /* Estado vacío mejorado */
        .empty-state {
            text-align: center;
            padding: 2rem 1rem;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 12px;
            border: 2px dashed #ddd;
        }

        .empty-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.6;
        }

        .empty-state p {
            color: #666;
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
        }

        /* Layout mejorado */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex: 1;
        }

        /* Mejor separación visual */
        .dashboard-grid {
            margin-bottom: 3rem;
        }

        .activity-feed, .quick-actions {
            margin-bottom: 1.5rem;
        }

        @media (max-width: 968px) {
            .three-column-layout {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .two-column-layout {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .dashboard-hero {
                padding: 2rem 1.5rem;
            }

            .hero-title {
                font-size: 2rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .activity-timeline {
                padding-left: 1.5rem;
            }

            .activity-item {
                padding: 0.75rem;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .metric-card {
                padding: 1.5rem;
            }

            .metric-value {
                font-size: 2rem;
            }

            .activity-feed, .quick-actions {
                margin-bottom: 1rem;
            }
        }
    </style>
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
    <script>
        // Animación de conteo para las estadísticas
        document.addEventListener('DOMContentLoaded', function() {
            // Función para animar números
            function animateNumber(element, target, duration = 2000) {
                const start = 0;
                const increment = target / (duration / 16);
                let current = start;

                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    element.textContent = Math.floor(current);
                }, 16);
            }

            // Animar estadísticas cuando sean visibles
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const statValue = entry.target;
                        const target = parseInt(statValue.getAttribute('data-target'));
                        animateNumber(statValue, target);
                        observer.unobserve(statValue);
                    }
                });
            });

            // Aplicar animación a todas las estadísticas
            document.querySelectorAll('.stat-value').forEach(stat => {
                const target = parseInt(stat.textContent);
                if (target > 0) {
                    stat.setAttribute('data-target', target);
                    stat.textContent = '0';
                    observer.observe(stat);
                }
            });

            // Efecto hover en tarjetas de estadísticas
            document.querySelectorAll('.modern-stat-card').forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-10px) scale(1.02)';
                });

                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });

            // Actualizar fecha y hora en tiempo real
            function updateDateTime() {
                const now = new Date();
                const options = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    timeZone: 'America/El_Salvador'
                };

                // Si hay algún elemento que muestre la fecha, actualizarlo
                const dateElements = document.querySelectorAll('.current-date');
                dateElements.forEach(el => {
                    el.textContent = now.toLocaleDateString('es-SV', options);
                });
            }

            // Actualizar cada minuto
            updateDateTime();
            setInterval(updateDateTime, 60000);
        });
    </script>
</body>
</html>