<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>👨‍🏫 Dashboard Profesor - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
    <style>
        /* Estilos específicos para el calendario mejorado */
        .calendar-navigation {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            color: white;
        }

        .calendar-navigation .btn {
            border-color: rgba(255, 255, 255, 0.3);
            color: white;
        }

        .calendar-navigation .btn:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.5);
            color: white;
        }

        .calendar-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .calendar-table {
            margin-bottom: 0;
            font-size: 0.875rem;
        }

        .calendar-day {
            background-color: #f8f9fa;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }

        .calendar-cell {
            position: relative;
            min-height: 80px;
            vertical-align: top;
            padding: 5px;
            border: 1px solid #dee2e6;
            transition: background-color 0.3s ease;
        }

        .calendar-cell:hover {
            background-color: #f8f9fa;
        }

        .calendar-cell.calendar-today {
            background-color: #e3f2fd;
            font-weight: bold;
        }

        .calendar-day-number {
            font-weight: 600;
            margin-bottom: 5px;
            text-align: center;
        }

        .calendar-reservations {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .calendar-reservation {
            padding: 2px 4px;
            border-radius: 4px;
            color: white;
            font-size: 0.75rem;
            text-align: center;
        }

        .reservation-confirmed {
            background-color: #28a745;
        }

        .reservation-pending {
            background-color: #ffc107;
            color: #212529;
        }

        .reservation-completed {
            background-color: #17a2b8;
        }

        .reservation-cancelled {
            background-color: #6c757d;
        }

        .calendar-more {
            padding: 2px 4px;
            border-radius: 4px;
            background-color: #e9ecef;
            color: #495057;
            font-size: 0.75rem;
            text-align: center;
        }

        .calendar-legend {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 10px;
        }

        .next-class-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .next-class-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .class-date {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .class-time {
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .class-teacher {
            font-size: 0.8rem;
            margin-bottom: 8px;
        }

        .class-status {
            margin-bottom: 8px;
        }

        .class-actions .btn {
            font-size: 0.8rem;
        }

        .day-detail-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
        }

        .day-reservations {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .reservation-detail-card {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            border-left: 4px solid #667eea;
        }

        .reservation-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .reservation-teacher {
            margin: 0;
            color: #495057;
        }

        .reservation-time {
            color: #6c757d;
            margin-bottom: 8px;
        }

        .reservation-level, .reservation-rate {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .reservation-actions {
            margin-top: 10px;
            text-align: center;
        }

        /* Mejoras para las tablas del dashboard */
        .table {
            border-radius: 0;
        }

        .table th {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            padding: 15px 12px !important;
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
            padding: 12px !important;
        }

        .avatar-sm {
            font-size: 0.8rem;
            font-weight: 600;
        }

        .btn-group .btn {
            margin: 0 1px;
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%) !important;
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%) !important;
        }

        .card-footer {
            border-top: 1px solid rgba(0,0,0,0.1);
            background-color: #f8f9fa !important;
        }

        .btn-outline-primary:hover,
        .btn-outline-success:hover,
        .btn-outline-warning:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        @media (max-width: 768px) {
            .calendar-cell {
                min-height: 60px;
            }

            .next-class-card {
                margin-bottom: 10px;
            }

            .table-responsive {
                font-size: 0.85rem;
            }

            .avatar-sm {
                width: 30px !important;
                height: 30px !important;
                font-size: 0.7rem;
            }
        }
    </style>
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
                    <div class="card-header bg-gradient-success text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">🎓 Mis Estudiantes</h3>
                            <span class="badge bg-light text-success"><?php echo count($estudiantes); ?> activos</span>
                        </div>
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
                    <div class="card-header bg-gradient-warning text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">💰 Mis Pagos</h3>
                            <span class="badge bg-light text-warning"><?php echo count($pagos); ?> ingresos</span>
                        </div>
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
    <script>
        let mesActualProfesor = new Date().getMonth();
        let anioActualProfesor = new Date().getFullYear();

        // Los datos se cargarán dinámicamente desde el controlador

        // Función para inicializar el calendario de reservas del profesor
        async function inicializarCalendarioClasesProfesor() {
            actualizarTituloCalendarioProfesor();
            await generarCalendarioProfesor(mesActualProfesor, anioActualProfesor);
            await cargarProximasClasesProfesor();
        }

        async function cambiarMesProfesor(direccion) {
            mesActualProfesor += direccion;

            if (mesActualProfesor < 0) {
                mesActualProfesor = 11;
                anioActualProfesor--;
            } else if (mesActualProfesor > 11) {
                mesActualProfesor = 0;
                anioActualProfesor++;
            }

            await generarCalendarioProfesor(mesActualProfesor, anioActualProfesor);
            actualizarTituloCalendarioProfesor();
        }

        function actualizarTituloCalendarioProfesor() {
            const nombresMeses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                                 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            document.getElementById('mesAnioActualProfesor').textContent = `${nombresMeses[mesActualProfesor]} ${anioActualProfesor}`;
        }

        async function generarCalendarioProfesor(mes, anio) {
            const calendarioContainer = document.getElementById('calendarioClases');
            const nombresMeses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                                  'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

            const primerDia = new Date(anio, mes, 1);
            const ultimoDia = new Date(anio, mes + 1, 0);
            const diasEnMes = ultimoDia.getDate();
            const diaSemanaInicio = primerDia.getDay();

            // Preparar datos de reservas para el mes actual
            const reservasMes = await prepararDatosReservasProfesor(mes, anio);

            let html = `
                <div class="calendar-container">
                    <table class="table table-bordered calendar-table">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center calendar-day">Dom</th>
                                <th class="text-center calendar-day">Lun</th>
                                <th class="text-center calendar-day">Mar</th>
                                <th class="text-center calendar-day">Mié</th>
                                <th class="text-center calendar-day">Jue</th>
                                <th class="text-center calendar-day">Vie</th>
                                <th class="text-center calendar-day">Sáb</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            let diaActual = 1;
            const hoy = new Date();
            const esMesActual = (anio === hoy.getFullYear() && mes === hoy.getMonth());

            for (let semana = 0; semana < 6; semana++) {
                html += '<tr>';
                for (let diaSemana = 0; diaSemana < 7; diaSemana++) {
                    if ((semana === 0 && diaSemana < diaSemanaInicio) || diaActual > diasEnMes) {
                        html += '<td class="calendar-cell calendar-empty"></td>';
                    } else {
                        const fechaActual = `${anio}-${String(mes + 1).padStart(2, '0')}-${String(diaActual).padStart(2, '0')}`;
                        const esHoy = esMesActual && diaActual === hoy.getDate();
                        const reservasDelDia = reservasMes[fechaActual] || [];

                        const hoyClass = esHoy ? 'calendar-today' : '';

                        html += `
                            <td class="calendar-cell ${hoyClass}" data-fecha="${fechaActual}">
                                <div class="calendar-day-number">${diaActual}</div>
                                <div class="calendar-reservations">
                        `;

                        // Mostrar hasta 2 reservas por día
                        const reservasMostrar = reservasDelDia.slice(0, 2);
                        reservasMostrar.forEach(reserva => {
                            const estadoClass = obtenerClaseEstadoProfesor(reserva.reservation_status);
                            html += `
                                <div class="calendar-reservation ${estadoClass}" title="${reserva.estudiante_name} - ${reserva.start_time || 'N/A'}">
                                    <small>${reserva.start_time ? reserva.start_time.substring(0, 5) : 'N/A'}</small>
                                </div>
                            `;
                        });

                        // Si hay más de 2 reservas, mostrar indicador
                        if (reservasDelDia.length > 2) {
                            html += `
                                <div class="calendar-more" title="${reservasDelDia.length - 2} más reservas">
                                    <small>+${reservasDelDia.length - 2}</small>
                                </div>
                            `;
                        }

                        html += `
                                </div>
                            </td>
                        `;
                        diaActual++;
                    }
                }
                html += '</tr>';
                if (diaActual > diasEnMes) break;
            }

            html += `
                        </tbody>
                    </table>
                </div>
            `;

            calendarioContainer.innerHTML = html;

            // Agregar eventos de clic a las celdas con reservas
            document.querySelectorAll('.calendar-cell[data-fecha]').forEach(celda => {
                if (celda.querySelector('.calendar-reservations').children.length > 0) {
                    celda.style.cursor = 'pointer';
                    celda.addEventListener('click', function() {
                        mostrarDetalleDiaProfesor(this.dataset.fecha);
                    });
                }
            });
        }

        async function prepararDatosReservasProfesor(mes, anio) {
            try {
                const response = await fetch('/plataforma-clases-online/home/calendario');
                const data = await response.json();

                if (data.success) {
                    const reservasData = data.reservas;
                    const reservasMes = {};
                    const mesActual = mes + 1; // JavaScript usa meses 0-11, necesitamos 1-12

                    reservasData.forEach(reserva => {
                        if (!reserva.fecha) return;
                        const fechaReserva = new Date(reserva.fecha + 'T00:00:00');
                        const coincide = fechaReserva.getMonth() + 1 === mesActual && fechaReserva.getFullYear() === anio;
                        if (coincide) {
                            if (!reservasMes[reserva.fecha]) {
                                reservasMes[reserva.fecha] = [];
                            }
                            reservasMes[reserva.fecha].push(reserva);
                        }
                    });

                    return reservasMes;
                } else {
                    throw new Error('Error al obtener datos del calendario');
                }
            } catch (error) {
                console.error('Error:', error);
                return {};
            }
        }

        function obtenerClaseEstadoProfesor(estado) {
            switch(estado.toLowerCase()) {
                case 'confirmada': return 'reservation-confirmed';
                case 'pendiente': return 'reservation-pending';
                case 'completada': return 'reservation-completed';
                case 'cancelada': return 'reservation-cancelled';
                default: return 'reservation-default';
            }
        }

        async function cargarProximasClasesProfesor() {
            try {
                const response = await fetch('/plataforma-clases-online/home/calendario');
                const data = await response.json();

                if (data.success) {
                    const reservasData = data.reservas;
                    const hoy = new Date();
                    const mesActual = hoy.getMonth() + 1;
                    const anioActual = hoy.getFullYear();

                    const reservasProximas = reservasData.filter(reserva => {
                        if (!reserva.fecha) return false;
                        const fechaReserva = new Date(reserva.fecha + 'T00:00:00');
                        return fechaReserva >= hoy &&
                               fechaReserva.getMonth() + 1 === mesActual &&
                               fechaReserva.getFullYear() === anioActual;
                    });

                    reservasProximas.sort((a, b) => new Date(a.fecha + 'T' + a.start_time) - new Date(b.fecha + 'T' + b.start_time));

                    const proximasReservas = reservasProximas.slice(0, 3);

                    const container = document.getElementById('proximasClasesContainerProfesor');
                    container.innerHTML = '';

                    proximasReservas.forEach(reserva => {
                        const card = document.createElement('div');
                        card.className = 'col-md-4';
                        card.innerHTML = `
                            <div class="next-class-card">
                                <div class="class-date">
                                    ${reserva.fecha_display}
                                </div>
                                <div class="class-time">
                                    ${reserva.start_time} - ${reserva.end_time}
                                </div>
                                <div class="class-teacher">
                                    👨‍🎓 ${reserva.estudiante_name}
                                </div>
                                <div class="class-status">
                                    <span class="badge bg-${reserva.reservation_status === 'confirmada' ? 'success' : (reserva.reservation_status === 'pendiente' ? 'warning' : (reserva.reservation_status === 'completada' ? 'info' : 'secondary'))}">
                                        ${reserva.reservation_status.charAt(0).toUpperCase() + reserva.reservation_status.slice(1)}
                                    </span>
                                </div>
                                ${(reserva.reservation_status === 'pendiente' || reserva.reservation_status === 'confirmada') ?
                                    `<div class="class-actions mt-2">
                                        <button class="btn btn-outline-danger btn-sm w-100" onclick="cancelarClaseProfesor('${reserva.reservation_id}')">Cancelar</button>
                                    </div>`
                                    : ''
                                }
                            </div>
                        `;
                        container.appendChild(card);
                    });
                } else {
                    throw new Error('Error al obtener datos del calendario');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        async function mostrarDetalleDiaProfesor(fecha) {
            try {
                const response = await fetch('/plataforma-clases-online/home/calendario');
                const data = await response.json();

                if (data.success) {
                    const reservasData = data.reservas;
                    const reservasDia = reservasData.filter(r => r.fecha === fecha);

                    if (reservasDia.length === 0) {
                        mostrarModalVacioProfesor(fecha);
                        return;
                    }

                    let detalle = `
                        <div class="day-detail-header">
                            <h5>📅 ${new Date(fecha + 'T00:00:00').toLocaleDateString('es-ES', {
                                weekday: 'long',
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            })}</h5>
                        </div>
                        <div class="day-reservations">
                    `;

                    reservasDia.forEach((reserva, index) => {
                        const horaInicio = reserva.start_time !== 'N/A' ? reserva.start_time : '08:00';
                        const horaFin = reserva.end_time !== 'N/A' ? reserva.end_time : '10:00';

                        detalle += `
                            <div class="reservation-detail-card">
                                <div class="reservation-header">
                                    <h6 class="reservation-teacher">👨‍🎓 ${reserva.estudiante_name}</h6>
                                    <span class="badge bg-${obtenerClaseEstadoProfesor(reserva.reservation_status).replace('reservation-', '')}">
                                        ${reserva.reservation_status.charAt(0).toUpperCase() + reserva.reservation_status.slice(1)}
                                    </span>
                                </div>
                                <div class="reservation-time">
                                    🕐 ${horaInicio} - ${horaFin}
                                </div>
                                ${reserva.notes ? `<div class="reservation-notes">📝 ${reserva.notes}</div>` : ''}
                                ${reserva.academic_level ? `<div class="reservation-level">🎓 ${reserva.academic_level}</div>` : ''}
                                ${reserva.hourly_rate ? `<div class="reservation-rate">💰 $${reserva.hourly_rate}/hora</div>` : ''}
                                <div class="reservation-link">
                                    ${reserva.meeting_link ? `🔗 Enlace de las reuniones: <a href="${reserva.meeting_link}" target="_blank" class="btn btn-primary btn-sm">Ir a la Reunión</a>` : '🔗 Enlace no disponible'}
                                </div>
                                <div class="reservation-actions">
                                    ${(reserva.reservation_status === 'pendiente' || reserva.reservation_status === 'confirmada') ?
                                        `<button class="btn btn-outline-danger btn-sm" onclick="cancelarClaseProfesor('${reserva.reservation_id}')">Cancelar Clase</button>`
                                        : ''
                                    }
                                </div>
                            </div>
                        `;
                    });

                    detalle += `</div>`;

                    // Crear y mostrar modal
                    const modal = document.createElement('div');
                    modal.className = 'modal fade show';
                    modal.innerHTML = `
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Detalles del Día</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    ${detalle}
                                </div>
                            </div>
                        </div>
                    `;

                    modal.style.display = 'block';
                    modal.style.backgroundColor = 'rgba(0,0,0,0.5)';
                    document.body.appendChild(modal);

                    // Cerrar modal al hacer clic fuera o en el botón de cerrar
                    modal.addEventListener('click', function(e) {
                        if (e.target === modal || e.target.classList.contains('btn-close')) {
                            document.body.removeChild(modal);
                        }
                    });
                } else {
                    throw new Error('Error al obtener datos del calendario');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        function mostrarModalVacioProfesor(fecha) {
            const modal = document.createElement('div');
            modal.className = 'modal fade show';
            modal.innerHTML = `
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Sin Reservas</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center">
                            <p>No tienes reservas programadas para ${new Date(fecha).toLocaleDateString('es-ES')}.</p>
                            <a href="/plataforma-clases-online/home/disponibilidad_create" class="btn btn-primary">Configurar Disponibilidad</a>
                        </div>
                    </div>
                </div>
            `;

            modal.style.display = 'block';
            modal.style.backgroundColor = 'rgba(0,0,0,0.5)';
            document.body.appendChild(modal);

            modal.addEventListener('click', function(e) {
                if (e.target === modal || e.target.classList.contains('btn-close')) {
                    document.body.removeChild(modal);
                }
            });
        }

        function cancelarClaseProfesor(reservationId) {
            if (confirm('¿Estás seguro de que quieres cancelar esta clase?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/plataforma-clases-online/home/cancelar_reserva';

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'reservation_id';
                input.value = reservationId;

                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Inicializar calendario cuando se carga la página
        document.addEventListener('DOMContentLoaded', function() {
            inicializarCalendarioClasesProfesor();
        });
    </script>
</body>
</html>