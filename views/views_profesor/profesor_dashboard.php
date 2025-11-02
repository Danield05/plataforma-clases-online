<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>👨‍🏫 Dashboard Profesor - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/profesor_dashboard.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php 
    // Definir la página actual para el header
    $currentPage = 'profesor_dashboard';
    ?>
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">👨‍🏫 Dashboard Profesor</h1>
            <?php include __DIR__ . '/../layouts/nav.php'; ?>
        </div>
    </header>

    <main class="container my-5">
        <!-- Bienvenida del Profesor -->
        <div class="dashboard-welcome mb-4">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="welcome-title">¡Hola, <?php echo htmlspecialchars($_SESSION['user_name']); ?>! 👋</h2>
                    <p class="welcome-subtitle">Gestiona tus clases y estudiantes desde aquí</p>
                    <div class="mt-3">
                        <a href="/plataforma-clases-online/home/perfil_edit" class="btn btn-outline-primary me-2">
                            👤 Editar Perfil
                        </a>
                        <a href="/plataforma-clases-online/auth/logout" class="btn btn-outline-danger">
                            🚪 Cerrar Sesión
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <!-- Estadísticas del Profesor -->
                    <div class="modern-stats-grid">
                        <div class="modern-stat-card">
                            <div class="stat-icon">📅</div>
                            <div class="stat-value text-primary"><?php echo $stats['reservasActivas']; ?></div>
                            <p class="stat-label">Reservas Activas</p>
                        </div>
                        <div class="modern-stat-card">
                            <div class="stat-icon">🎓</div>
                            <div class="stat-value text-success"><?php echo $stats['estudiantesTotales']; ?></div>
                            <p class="stat-label">Estudiantes Totales</p>
                        </div>
                        <div class="modern-stat-card">
                            <div class="stat-icon">⭐</div>
                            <div class="stat-value text-warning"><?php echo $stats['calificacionPromedio']; ?></div>
                            <p class="stat-label">Calificación Promedio</p>
                        </div>
                        <div class="modern-stat-card">
                            <div class="stat-icon">💰</div>
                            <div class="stat-value text-info">$<?php echo number_format($stats['ingresosMes'], 2); ?></div>
                            <p class="stat-label">Ingresos del Mes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendario de Clases (Fila completa para mejor visibilidad) -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>📅 Calendario de Clases</h3>
                        <span class="badge bg-info">Reservas de Estudiantes</span>
                    </div>
                    <div class="card-body">
                        <!-- Controles de navegación del calendario -->
                        <div class="calendar-navigation mb-3">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <button class="btn btn-outline-primary btn-sm" onclick="cambiarMesProfesor(-1)">‹ Mes Anterior</button>
                                </div>
                                <div class="col-md-4 text-center">
                                    <h5 id="mesAnioActualProfesor" class="mb-0"></h5>
                                </div>
                                <div class="col-md-4 text-end">
                                    <button class="btn btn-outline-primary btn-sm" onclick="cambiarMesProfesor(1)">Mes Siguiente ›</button>
                                </div>
                            </div>
                        </div>

                        <div id="calendarioClases" class="mb-3">
                            <!-- Calendario se cargará aquí -->
                        </div>

                        <!-- Leyenda del calendario -->
                        <div class="calendar-legend mb-3">
                            <div class="row text-center">
                                <div class="col-3">
                                    <span class="badge bg-success">●</span> Confirmada
                                </div>
                                <div class="col-3">
                                    <span class="badge bg-warning">●</span> Pendiente
                                </div>
                                <div class="col-3">
                                    <span class="badge bg-info">●</span> Completada
                                </div>
                                <div class="col-3">
                                    <span class="badge bg-secondary">●</span> Cancelada
                                </div>
                            </div>
                        </div>

                        <?php if (empty($reservas)): ?>
                        <div class="empty-state">
                            <div class="empty-icon">📚</div>
                            <p>No tienes reservas programadas</p>
                            <a href="/plataforma-clases-online/home/disponibilidad_create" class="btn btn-primary btn-sm">Configurar Disponibilidad</a>
                        </div>
                        <?php else: ?>
                        <!-- Mostrar mensaje de éxito o error si existe -->
                        <?php if (!empty($_GET['success'])): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?php if ($_GET['success'] === 'cancelled'): ?>
                                    ✅ Reserva cancelada exitosamente
                                <?php endif; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($_GET['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php if ($_GET['error'] === 'cancel_failed'): ?>
                                    ❌ Error al cancelar la reserva
                                <?php endif; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Próximas clases destacadas -->
                        <div class="mt-4">
                            <h6 class="mb-3">🎯 Próximas Clases</h6>
                            <div class="row g-2" id="proximasClasesContainerProfesor">
                                <!-- Las próximas clases se cargarán aquí -->
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <a href="/plataforma-clases-online/home/reservas" class="btn btn-outline-primary btn-sm">
                                Ver Todas las Reservas <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Otras Secciones del Dashboard -->
        <!-- Estudiantes y Pagos -->
        <div class="row g-4 mt-2">
            <!-- Estudiantes -->
            <div class="col-lg-8" id="estudiantes-section">
                <div class="dashboard-card">
                    <div class="card-header bg-gradient-success text-white position-relative">
                        <h3 class="mb-0">🎓 Mis Estudiantes</h3>
                        <span class="badge bg-light text-success position-absolute top-50 end-0 translate-middle-y me-3"><?php echo count($estudiantes); ?> activos</span>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($estudiantes)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-0 fw-bold" style="width: 40%; padding: 15px 12px;">ESTUDIANTE</th>
                                            <th class="border-0 fw-bold" style="width: 35%; padding: 15px 12px;">EMAIL</th>
                                            <th class="border-0 fw-bold" style="width: 25%; padding: 15px 12px;">ACCIÓN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($estudiantes as $est): ?>
                                            <tr class="align-middle">
                                                <td class="py-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                            <?php echo strtoupper(substr($est['first_name'] ?? 'E', 0, 1) . substr($est['last_name'] ?? 'S', 0, 1)); ?>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold text-dark"><?php echo htmlspecialchars($est['first_name'] . ' ' . $est['last_name']); ?></div>
                                                            <small class="text-muted">Estudiante</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-3">
                                                    <div class="text-muted">
                                                        <i class="fas fa-envelope me-1"></i><?php echo htmlspecialchars($est['email']); ?>
                                                    </div>
                                                </td>
                                                <td class="py-3">
                                                    <a href="/plataforma-clases-online/home/ver_estudiante?id=<?php echo $est['user_id']; ?>" 
                                                       class="btn btn-sm btn-outline-success">
                                                        <i class="fas fa-user me-1"></i>Ver Perfil
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-4">
                                <a href="/plataforma-clases-online/home/estudiantes" class="btn btn-outline-success btn-sm">
                                    Ver Todos los Estudiantes <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <div class="text-muted fs-1 mb-3">👥</div>
                                <h5 class="text-muted">Aún no tienes estudiantes</h5>
                                <p class="text-muted mb-3">Los estudiantes aparecerán aquí cuando reserven clases contigo</p>
                                <a href="/plataforma-clases-online/home/estudiantes" class="btn btn-success">
                                    <i class="fas fa-users me-2"></i>Ver Todos los Estudiantes
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Mis Pagos -->
            <div class="col-lg-4">
                <div class="dashboard-card">
                    <div class="card-header bg-gradient-warning text-white position-relative">
                        <h3 class="mb-0">💰 Mis Pagos</h3>
                        <span class="badge bg-light text-warning position-absolute top-50 end-0 translate-middle-y me-3"><?php echo count($pagos); ?> ingresos</span>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($pagos)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-0 fw-bold" style="padding: 15px 12px;">PAGO</th>
                                            <th class="border-0 fw-bold" style="padding: 15px 12px;">MONTO</th>
                                            <th class="border-0 fw-bold" style="padding: 15px 12px;">ESTADO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach(array_slice($pagos, 0, 5) as $pago): ?>
                                            <tr class="align-middle">
                                                <td class="py-3">
                                                    <div class="text-center">
                                                        <span class="badge bg-light text-dark fs-6">#<?php echo htmlspecialchars($pago['payment_id']); ?></span>
                                                        <br><small class="text-muted">Res. #<?php echo htmlspecialchars($pago['reservation_id']); ?></small>
                                                    </div>
                                                </td>
                                                <td class="py-3">
                                                    <div class="text-center">
                                                        <div class="fw-bold text-success">$<?php echo number_format($pago['amount'], 2); ?></div>
                                                        <small class="text-muted"><?php echo date('d/m/Y', strtotime($pago['payment_date'])); ?></small>
                                                    </div>
                                                </td>
                                                <td class="py-3">
                                                    <div class="text-center">
                                                        <span class="badge bg-success px-2 py-1">
                                                            <i class="fas fa-check-circle"></i>
                                                        </span>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-4">
                                <a href="/plataforma-clases-online/home/pagos" class="btn btn-outline-warning btn-sm">
                                    Ver Todos los Pagos <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <div class="text-muted fs-1 mb-3">💸</div>
                                <h5 class="text-muted">No hay pagos registrados</h5>
                                <p class="text-muted mb-3">Los pagos aparecerán aquí cuando los estudiantes completen sus reservas</p>
                                <a href="/plataforma-clases-online/home/pagos" class="btn btn-warning">
                                    <i class="fas fa-chart-line me-2"></i>Ver Historial de Pagos
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>⚡ Acciones Rápidas</h3>
                        <span class="badge bg-secondary">Herramientas</span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="quick-action-card">
                                    <div class="action-icon">📝</div>
                                    <h4>Crear Clase</h4>
                                    <p>Programa una nueva clase</p>
                                    <a href="/plataforma-clases-online/home/crear_clase" class="btn btn-outline-primary btn-sm">Crear</a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="quick-action-card">
                                    <div class="action-icon">📊</div>
                                    <h4>Ver Reportes</h4>
                                    <p>Analiza tu rendimiento</p>
                                    <a href="/plataforma-clases-online/home/reportes" class="btn btn-outline-info btn-sm">Reportes</a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="quick-action-card">
                                    <div class="action-icon">⚙️</div>
                                    <h4>Configuración</h4>
                                    <p>Ajusta tu perfil</p>
                                    <a href="/plataforma-clases-online/home/perfil_edit" class="btn btn-outline-secondary btn-sm">Configurar</a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="quick-action-card">
                                    <div class="action-icon">💬</div>
                                    <h4>Mensajes</h4>
                                    <p>Comunícate con estudiantes</p>
                                    <a href="/plataforma-clases-online/home/mensajes" class="btn btn-outline-success btn-sm">Mensajes</a>
                                </div>
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
    <script src="/plataforma-clases-online/public/js/script.js"></script>
    <script src="/plataforma-clases-online/public/js/profesor_dashboard.js?v=<?php echo time(); ?>"></script>
</body>
</html>