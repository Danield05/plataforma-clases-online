<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎓 Dashboard Estudiante - Plataforma de Clases Online</title>
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

        @media (max-width: 768px) {
            .calendar-cell {
                min-height: 60px;
            }

            .next-class-card {
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <?php 
    // Definir la página actual para el header
    $currentPage = 'estudiante_dashboard';
    ?>
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">🎓 Dashboard Estudiante</h1>
            <?php include __DIR__ . '/../layouts/nav.php'; ?>
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

        <!-- Pagos Pendientes (Solo si hay pagos pendientes) -->
        <?php 
        $pagosPendientes = array_filter($pagos, function($pago) {
            return strtolower($pago['payment_status'] ?? 'pendiente') === 'pendiente';
        });
        if (!empty($pagosPendientes)): 
        ?>
        <div class="alert alert-warning mb-4" style="border-left: 4px solid #ffc107;">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h5 class="alert-heading mb-2">⚠️ Tienes <?php echo count($pagosPendientes); ?> pago(s) pendiente(s)</h5>
                    <p class="mb-0">Completa tus pagos para confirmar tus clases reservadas.</p>
                </div>
                <div class="col-lg-4 text-end">
                    <a href="/plataforma-clases-online/home/pagos" class="btn btn-warning btn-sm me-2">
                        💳 Ver Pagos Pendientes
                    </a>
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="collapse" data-bs-target="#pagosPendientesDetalle">
                        📋 Ver Detalles
                    </button>
                </div>
            </div>
            
            <!-- Detalles de pagos pendientes (colapsable) -->
            <div class="collapse mt-3" id="pagosPendientesDetalle">
                <div class="card card-body">
                    <h6>Pagos Pendientes:</h6>
                    <div class="row g-2">
                        <?php foreach ($pagosPendientes as $pago): ?>
                        <div class="col-md-6">
                            <div class="card border-warning h-100">
                                <div class="card-body p-3">
                                    <h6 class="card-title text-warning mb-2">
                                        💰 $<?php echo number_format($pago['amount'], 2); ?>
                                    </h6>
                                    <p class="card-text small mb-2">
                                        <strong>Método:</strong> <?php echo htmlspecialchars($pago['payment_method'] ?? 'PayPal'); ?><br>
                                        <strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($pago['payment_date'])); ?>
                                    </p>
                                    <?php if (!empty($pago['description'])): ?>
                                    <p class="card-text small text-muted mb-2">
                                        <?php echo htmlspecialchars($pago['description']); ?>
                                    </p>
                                    <?php endif; ?>
                                    <a href="/plataforma-clases-online/home/pagar_pendiente?payment_id=<?php echo $pago['payment_id']; ?>" 
                                       class="btn btn-warning btn-sm w-100">
                                        💳 Pagar Ahora
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Secciones del Dashboard -->
        <div class="row g-4">
            <!-- Calendario de Reservas -->
            <div class="col-lg-8">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>📅 Mi Calendario de Clases</h3>
                        <span class="badge bg-primary">Vista Mensual</span>
                    </div>
                    <div class="card-body">
                        <!-- Controles de navegación del calendario -->
                        <div class="calendar-navigation mb-3">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <button class="btn btn-outline-primary btn-sm" onclick="cambiarMes(-1)">‹ Mes Anterior</button>
                                </div>
                                <div class="col-md-4 text-center">
                                    <h5 id="mesAnioActual" class="mb-0"></h5>
                                </div>
                                <div class="col-md-4 text-end">
                                    <button class="btn btn-outline-primary btn-sm" onclick="cambiarMes(1)">Mes Siguiente ›</button>
                                </div>
                            </div>
                        </div>

                        <div id="calendarioReservas" class="mb-3">
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
                            <p>No tienes clases reservadas aún</p>
                            <a href="/plataforma-clases-online/home/explorar_profesores" class="btn btn-primary btn-sm">Explorar Profesores</a>
                            <a href="/plataforma-clases-online/home/info_pagos_prueba" class="btn btn-info btn-sm ms-2">🧪 Métodos de Pago de Prueba</a>
                        </div>
                        <?php else: ?>
                        <!-- Mostrar mensaje de éxito o error si existe -->
                        <?php if (!empty($_GET['success'])): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?php if ($_GET['success'] === 'cancelled'): ?>
                                    ✅ Clase cancelada exitosamente
                                <?php endif; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($_GET['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php if ($_GET['error'] === 'cancel_failed'): ?>
                                    ❌ Error al cancelar la clase
                                <?php endif; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Próximas clases destacadas -->
                        <div class="mt-4">
                            <h6 class="mb-3">🎯 Próximas Clases</h6>
                            <div class="row g-2" id="proximasClasesContainer">
                                <!-- Las próximas clases se cargarán aquí -->
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <a href="/plataforma-clases-online/home/reservas" class="btn btn-outline-primary btn-sm">Ver Todas las Reservas</a>
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
                                        <small class="text-muted"><?php echo strpos($pago['reservation_id'], 'Pago-') === 0 ? 'Pago' : 'Reserva'; ?> #<?php echo htmlspecialchars(str_replace('Pago-', '', $pago['reservation_id'])); ?></small>
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
    <script>
        let mesActual = new Date().getMonth();
        let anioActual = new Date().getFullYear();

        // Función para inicializar el calendario de reservas
        async function inicializarCalendarioReservas() {
            actualizarTituloCalendario();
            await generarCalendario(mesActual, anioActual);
            await cargarProximasClases();
        }

        // Función para cargar próximas clases
        async function cargarProximasClases() {
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

                    const container = document.getElementById('proximasClasesContainer');
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
                                    👨‍🏫 ${reserva.profesor_name}
                                </div>
                                <div class="class-status">
                                    <span class="badge bg-${reserva.reservation_status === 'confirmada' ? 'success' : (reserva.reservation_status === 'pendiente' ? 'warning' : (reserva.reservation_status === 'completada' ? 'info' : 'secondary'))}">
                                        ${reserva.reservation_status.charAt(0).toUpperCase() + reserva.reservation_status.slice(1)}
                                    </span>
                                </div>
                                ${(reserva.reservation_status === 'pendiente' || reserva.reservation_status === 'confirmada') ?
                                    `<div class="class-actions mt-2">
                                        <form method="post" action="/plataforma-clases-online/home/cancelar_reserva" style="display: inline;" onsubmit="return confirm('¿Estás seguro de que quieres cancelar esta clase?');">
                                            <input type="hidden" name="reservation_id" value="${reserva.reservation_id}">
                                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">Cancelar</button>
                                        </form>
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

        function cambiarMes(direccion) {
            mesActual += direccion;

            if (mesActual < 0) {
                mesActual = 11;
                anioActual--;
            } else if (mesActual > 11) {
                mesActual = 0;
                anioActual++;
            }

            generarCalendario(mesActual, anioActual);
            actualizarTituloCalendario();
        }

        function actualizarTituloCalendario() {
            const nombresMeses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            document.getElementById('mesAnioActual').textContent = `${nombresMeses[mesActual]} ${anioActual}`;
        }

        async function generarCalendario(mes, anio) {
            const calendarioContainer = document.getElementById('calendarioReservas');
            const nombresMeses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                                 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

            const primerDia = new Date(anio, mes, 1);
            const ultimoDia = new Date(anio, mes + 1, 0);
            const diasEnMes = ultimoDia.getDate();
            const diaSemanaInicio = primerDia.getDay();

            // Preparar datos de reservas para el mes actual
            const reservasMes = await prepararDatosReservas(mes, anio);

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
                        const estadoReserva = obtenerEstadoReserva(reservasDelDia);

                        html += `
                            <td class="calendar-cell ${hoyClass}" data-fecha="${fechaActual}">
                                <div class="calendar-day-number">${diaActual}</div>
                                <div class="calendar-reservations">
                        `;

                        // Mostrar hasta 2 reservas por día
                        const reservasMostrar = reservasDelDia.slice(0, 2);
                        reservasMostrar.forEach(reserva => {
                            const estadoClass = obtenerClaseEstado(reserva.reservation_status);
                            html += `
                                <div class="calendar-reservation ${estadoClass}" title="${reserva.profesor_name} - ${reserva.start_time || 'N/A'}">
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
                        mostrarDetalleDia(this.dataset.fecha);
                    });
                }
            });
        }

        async function prepararDatosReservas(mes, anio) {
            try {
                const response = await fetch('/plataforma-clases-online/home/calendario');
                const data = await response.json();

                if (data.success) {
                    const reservasData = data.reservas;
                    const reservasMes = {};

                    // Filtrar reservas solo para el mes y año actuales
                    const mesActual = mes + 1; // JavaScript usa meses 0-11, necesitamos 1-12
                    const reservasFiltradas = reservasData.filter(reserva => {
                        if (!reserva.fecha) return false;
                        const fechaReserva = new Date(reserva.fecha + 'T00:00:00');
                        const coincide = fechaReserva.getMonth() + 1 === mesActual && fechaReserva.getFullYear() === anio;
                        return coincide;
                    });

                    reservasFiltradas.forEach(reserva => {
                        if (!reservasMes[reserva.fecha]) {
                            reservasMes[reserva.fecha] = [];
                        }
                        reservasMes[reserva.fecha].push(reserva);
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

        function obtenerEstadoReserva(reservasDelDia) {
            if (reservasDelDia.length === 0) return '';

            const estados = reservasDelDia.map(r => r.reservation_status);
            if (estados.includes('confirmada')) return 'confirmada';
            if (estados.includes('pendiente')) return 'pendiente';
            if (estados.includes('completada')) return 'completada';
            return 'cancelada';
        }

        function obtenerClaseEstado(estado) {
            switch(estado.toLowerCase()) {
                case 'confirmada': return 'reservation-confirmed';
                case 'pendiente': return 'reservation-pending';
                case 'completada': return 'reservation-completed';
                case 'cancelada': return 'reservation-cancelled';
                default: return 'reservation-default';
            }
        }

        async function mostrarDetalleDia(fecha) {
            try {
                const response = await fetch('/plataforma-clases-online/home/calendario');
                const data = await response.json();

                if (data.success) {
                    const reservasData = data.reservas;
                    const reservasDia = reservasData.filter(r => r.fecha === fecha);

                    if (reservasDia.length === 0) {
                        mostrarModalVacio(fecha);
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
                                    <h6 class="reservation-teacher">👨‍🏫 ${reserva.profesor_name}</h6>
                                    <span class="badge bg-${obtenerClaseEstado(reserva.reservation_status).replace('reservation-', '')}">
                                        ${reserva.reservation_status.charAt(0).toUpperCase() + reserva.reservation_status.slice(1)}
                                    </span>
                                </div>
                                <div class="reservation-time">
                                    🕐 ${horaInicio} - ${horaFin}
                                </div>
                                ${reserva.notes ? `<div class="reservation-notes">📝 ${reserva.notes}</div>` : ''}
                                ${reserva.academic_level ? `<div class="reservation-level">🎓 ${reserva.academic_level}</div>` : ''}
                                ${reserva.hourly_rate ? `<div class="reservation-rate">💰 $${reserva.hourly_rate}/hora</div>` : ''}
                                <div class="reservation-actions">
                                    ${(reserva.reservation_status === 'pendiente' || reserva.reservation_status === 'confirmada') ?
                                        `<button class="btn btn-outline-danger btn-sm" onclick="cancelarClase('${reserva.reservation_id}')">Cancelar Clase</button>`
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

        function mostrarModalVacio(fecha) {
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
                            <a href="/plataforma-clases-online/home/explorar_profesores" class="btn btn-primary">Explorar Profesores</a>
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

        function cancelarClase(reservationId) {
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
            inicializarCalendarioReservas();
        });
    </script>
</body>
</html>