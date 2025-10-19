<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎓 Dashboard Estudiante - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php 
    // Definir la página actual para el header
    $currentPage = 'estudiante_dashboard';
    ?>
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">🎓 Dashboard Estudiante</h1>
            <?php include __DIR__ . '/../nav.php'; ?>
        </div>
    </header>

    <main class="container my-5">
        <!-- Bienvenida del Estudiante -->
        <div class="dashboard-welcome mb-4">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="welcome-title">¡Hola, <?php echo htmlspecialchars($_SESSION['user_name']); ?>! 👋</h2>
                    <p class="welcome-subtitle">Bienvenido a tu espacio de aprendizaje personalizado</p>
                </div>
                <div class="col-lg-4 text-end">
                    <a href="/plataforma-clases-online/home/perfil_edit" class="btn btn-outline-primary me-2">
                        👤 Editar Perfil
                    </a>
                    <a href="/plataforma-clases-online/auth/logout" class="btn btn-outline-danger">
                        🚪 Cerrar Sesión
                    </a>
                </div>
            </div>
        </div>

        <!-- Estadísticas del Estudiante -->
        <div class="modern-stats-grid mb-5">
            <div class="modern-stat-card">
                <div class="stat-icon">📚</div>
                <div class="stat-value text-primary"><?php echo $stats['clasesReservadas']; ?></div>
                <p class="stat-label">Clases Reservadas</p>
            </div>
            <div class="modern-stat-card">
                <div class="stat-icon">✅</div>
                <div class="stat-value text-success"><?php echo $stats['clasesCompletadas']; ?></div>
                <p class="stat-label">Clases Completadas</p>
            </div>
            <div class="modern-stat-card">
                <div class="stat-icon">👨‍🏫</div>
                <div class="stat-value text-info"><?php echo $stats['profesoresActivos']; ?></div>
                <p class="stat-label">Profesores Activos</p>
            </div>
            <div class="modern-stat-card">
                <div class="stat-icon">💰</div>
                <div class="stat-value text-warning">$<?php echo number_format($stats['totalInvertido'], 2); ?></div>
                <p class="stat-label">Total Invertido</p>
            </div>
        </div>

        <!-- Secciones del Dashboard -->
        <div class="row g-4">
            <!-- Mis Reservas -->
            <div class="col-lg-6">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>📅 Mis Reservas</h3>
                        <span class="badge bg-primary">Próximas Clases</span>
                    </div>
                    <div class="card-body">
                        <?php if (empty($reservas)): ?>
                        <div class="empty-state">
                            <div class="empty-icon">📚</div>
                            <p>No tienes clases reservadas aún</p>
                            <a href="/plataforma-clases-online/home/explorar_profesores" class="btn btn-primary btn-sm">Explorar Profesores</a>
                        </div>
                        <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($reservas as $reserva): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1"><?php echo htmlspecialchars($reserva['profesor_name']); ?></h6>
                                        <small class="text-muted"><?php echo htmlspecialchars($reserva['reservation_status']); ?> - <?php echo htmlspecialchars($reserva['class_date']); ?></small>
                                    </div>
                                    <span class="badge bg-<?php echo $reserva['reservation_status'] === 'confirmada' ? 'success' : 'secondary'; ?>"><?php echo htmlspecialchars($reserva['reservation_status']); ?></span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Mis Pagos -->
            <div class="col-lg-6">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>💳 Mis Pagos</h3>
                        <span class="badge bg-success">Historial</span>
                    </div>
                    <div class="card-body">
                        <?php if (empty($pagos)): ?>
                        <div class="empty-state">
                            <div class="empty-icon">💰</div>
                            <p>No hay pagos registrados</p>
                            <a href="/plataforma-clases-online/home/pagos" class="btn btn-success btn-sm">Ver Historial Completo</a>
                        </div>
                        <?php else: ?>
                        <div class="list-group">
                            <?php foreach (array_slice($pagos, 0, 3) as $pago): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted">Reserva #<?php echo htmlspecialchars($pago['reservation_id']); ?></small>
                                        <p class="mb-0">$<?php echo number_format($pago['amount'], 2); ?> - <?php echo htmlspecialchars($pago['payment_method']); ?></p>
                                    </div>
                                    <span class="badge bg-<?php echo $pago['payment_status'] === 'pagado' ? 'success' : 'warning'; ?>"><?php echo htmlspecialchars($pago['payment_status']); ?></span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <a href="/plataforma-clases-online/home/pagos" class="btn btn-success btn-sm mt-2">Ver Historial Completo</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Profesores Disponibles -->
            <div class="col-lg-12">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>👨‍🏫 Profesores Disponibles</h3>
                        <span class="badge bg-info">Buscar y Reservar</span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="quick-action-card">
                                    <div class="action-icon">🔍</div>
                                    <h4>Buscar Profesores</h4>
                                    <p>Encuentra el profesor perfecto para ti</p>
                                    <a href="/plataforma-clases-online/home/explorar_profesores" class="btn btn-outline-primary btn-sm">Buscar</a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="quick-action-card">
                                    <div class="action-icon">⭐</div>
                                    <h4>Mejor Valorados</h4>
                                    <p>Profesores con mejores calificaciones</p>
                                    <a href="/plataforma-clases-online/home/explorar_profesores" class="btn btn-outline-warning btn-sm">Ver Top</a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="quick-action-card">
                                    <div class="action-icon">💡</div>
                                    <h4>Recomendados</h4>
                                    <p>Sugerencias personalizadas para ti</p>
                                    <a href="/plataforma-clases-online/home/explorar_profesores" class="btn btn-outline-info btn-sm">Explorar</a>
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
</body>
</html>