<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>👨‍🏫 Dashboard Profesor - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
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
                <div class="col-lg-8">
                    <h2 class="welcome-title">¡Hola, <?php echo htmlspecialchars($_SESSION['user_name']); ?>! 👋</h2>
                    <p class="welcome-subtitle">Gestiona tus clases y estudiantes desde aquí</p>
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

        <!-- Estadísticas del Profesor -->
        <div class="modern-stats-grid mb-5">
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

        <!-- Secciones del Dashboard -->
        <div class="row g-4">
            <!-- Mis Reservas -->
            <div class="col-lg-6">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>📅 Mis Reservas</h3>
                        <span class="badge bg-primary">Clases Programadas</span>
                    </div>
                    <div class="card-body">
                            <?php if (!empty($reservas)): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Reserva</th>
                                                <th>Estudiante</th>
                                                <th>Fecha</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach((array)$reservas as $r): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($r['reservation_id'] ?? ''); ?></td>
                                                    <td><?php echo htmlspecialchars($r['estudiante_name'] ?? ''); ?></td>
                                                    <td><?php echo date('d/m/Y', strtotime($r['class_date'] ?? '')); ?> (<?php echo $r['start_time'] ?? 'N/A'; ?> - <?php echo $r['end_time'] ?? 'N/A'; ?>)</td>
                                                    <td><?php echo htmlspecialchars($r['reservation_status'] ?? ''); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">
                                    <div class="empty-icon">📚</div>
                                    <p>No tienes reservas asignadas</p>
                                    <a href="/plataforma-clases-online/home/disponibilidad_create" class="btn btn-primary btn-sm">Configurar Disponibilidad</a>
                                </div>
                            <?php endif; ?>
                    </div>
                </div>
            </div>

            

            <!-- Calendario de Clases -->
            <div class="col-lg-6">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>📅 Calendario de Clases</h3>
                        <span class="badge bg-info">Reservas de Estudiantes</span>
                    </div>
                    <div class="card-body">
                        <div id="calendarioClases" class="mb-3" style="overflow-x: auto;">
                            <!-- Calendario se cargará aquí -->
                        </div>
                        <?php if (!empty($reservas)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover table-sm">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Estudiante</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Ordenar reservas por fecha
                                        usort($reservas, function($a, $b) {
                                            return strtotime($a['class_date']) - strtotime($b['class_date']);
                                        });
                                        $proximasReservas = array_slice($reservas, 0, 3);
                                        ?>
                                        <?php foreach($proximasReservas as $reserva): ?>
                                            <tr>
                                                <td><?php echo date('d/m/Y', strtotime($reserva['class_date'])); ?></td>
                                                <td><?php echo htmlspecialchars($reserva['estudiante_name']); ?></td>
                                                <td>
                                                    <span class="badge bg-<?php echo $reserva['reservation_status'] === 'confirmada' ? 'success' : ($reserva['reservation_status'] === 'pendiente' ? 'warning' : 'secondary'); ?>">
                                                        <?php echo htmlspecialchars($reserva['reservation_status']); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-3">
                                <a href="/plataforma-clases-online/home/reservas" class="btn btn-outline-info btn-sm">Ver Todas las Reservas</a>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-icon">📅</div>
                                <p>No tienes reservas programadas</p>
                                <a href="/plataforma-clases-online/home/disponibilidad_create" class="btn btn-info btn-sm">Configurar Disponibilidad</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Estudiantes -->
            <div class="col-lg-6" id="estudiantes-section">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>🎓 Mis Estudiantes</h3>
                        <span class="badge bg-success">Activos</span>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($estudiantes)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Estudiante</th>
                                            <th>Email</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($estudiantes as $est): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($est['first_name'] . ' ' . $est['last_name']); ?></td>
                                                <td><?php echo htmlspecialchars($est['email']); ?></td>
                                                <td>
                                                    <a href="/plataforma-clases-online/home/ver_estudiante?id=<?php echo $est['user_id']; ?>" class="btn btn-sm btn-outline-primary">Ver Perfil</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-3">
                                <a href="/plataforma-clases-online/home/estudiantes" class="btn btn-success btn-sm">Ver Todos los Estudiantes</a>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-icon">👥</div>
                                <p>Aún no tienes estudiantes registrados contigo</p>
                                <a href="/plataforma-clases-online/home/estudiantes" class="btn btn-success btn-sm">Ver Todos</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Mis Pagos -->
            <div class="col-lg-6">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>💰 Mis Pagos</h3>
                        <span class="badge bg-warning">Ingresos</span>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($pagos)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>💳 Pago</th>
                                            <th>📋 Reserva</th>
                                            <th>💵 Monto</th>
                                            <th>📅 Fecha</th>
                                            <th>📊 Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach(array_slice($pagos, 0, 5) as $pago): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($pago['payment_id']); ?></td>
                                                <td><?php echo htmlspecialchars($pago['reservation_id']); ?></td>
                                                <td class="fw-bold text-success">$<?php echo number_format($pago['amount'], 2); ?></td>
                                                <td><?php echo date('d/m/Y', strtotime($pago['payment_date'])); ?></td>
                                                <td>
                                                    <span class="badge bg-success">
                                                        <?php echo htmlspecialchars($pago['payment_status']); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-3">
                                <a href="/plataforma-clases-online/home/pagos" class="btn btn-outline-warning btn-sm">Ver Todos los Pagos</a>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-icon">💸</div>
                                <p>No hay pagos registrados</p>
                                <a href="/plataforma-clases-online/home/pagos" class="btn btn-warning btn-sm">Ver Historial</a>
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
        // Función para inicializar el calendario de clases del profesor
        function inicializarCalendarioClases() {
            const calendarioContainer = document.getElementById('calendarioClases');
            const hoy = new Date();
            const mesActual = hoy.getMonth();
            const anioActual = hoy.getFullYear();

            // Crear calendario simple
            const calendarioHTML = generarCalendarioClases(mesActual, anioActual);
            calendarioContainer.innerHTML = calendarioHTML;
        }

        function generarCalendarioClases(mes, anio) {
            const nombresMeses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

            const primerDia = new Date(anio, mes, 1);
            const ultimoDia = new Date(anio, mes + 1, 0);
            const diasEnMes = ultimoDia.getDate();
            const diaSemanaInicio = primerDia.getDay();

            let html = `
                <div class="text-center mb-3">
                    <h5>${nombresMeses[mes]} ${anio}</h5>
                </div>
                <table class="table table-bordered table-sm" style="min-width: 100%; font-size: 0.875rem;">
                    <thead>
                        <tr>
                            <th class="text-center">Dom</th>
                            <th class="text-center">Lun</th>
                            <th class="text-center">Mar</th>
                            <th class="text-center">Mié</th>
                            <th class="text-center">Jue</th>
                            <th class="text-center">Vie</th>
                            <th class="text-center">Sáb</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            let diaActual = 1;
            for (let semana = 0; semana < 6; semana++) {
                html += '<tr>';
                for (let diaSemana = 0; diaSemana < 7; diaSemana++) {
                    if ((semana === 0 && diaSemana < diaSemanaInicio) || diaActual > diasEnMes) {
                        html += '<td></td>';
                    } else {
                        const fechaActual = `${anio}-${String(mes + 1).padStart(2, '0')}-${String(diaActual).padStart(2, '0')}`;
                        const reservasDia = contarReservasEnFecha(fechaActual);
                        let claseDia = '';
                        let indicador = '';

                        if (reservasDia > 0) {
                            claseDia = 'table-success';
                            indicador = `<small class="text-muted">${reservasDia}</small>`;
                        }

                        const hoyClass = fechaActual === '2025-10-22' ? 'fw-bold' : '';

                        html += `<td class="text-center ${claseDia} ${hoyClass}" style="cursor: pointer;" onclick="mostrarDetalleDiaProfesor('${fechaActual}')">${diaActual}${indicador}</td>`;
                        diaActual++;
                    }
                }
                html += '</tr>';
                if (diaActual > diasEnMes) break;
            }

            html += `
                    </tbody>
                </table>
                <div class="text-muted small">
                    <span class="badge bg-success me-2">●</span> Día con reservas
                    <span class="ms-3">Los números indican cantidad de reservas</span>
                </div>
            `;

            return html;
        }

        function contarReservasEnFecha(fecha) {
            // Contar reservas en esta fecha
            <?php
            $reservasPorFecha = [];
            foreach ($reservas as $reserva) {
                $fechaReserva = date('Y-m-d', strtotime($reserva['class_date']));
                if (!isset($reservasPorFecha[$fechaReserva])) {
                    $reservasPorFecha[$fechaReserva] = 0;
                }
                $reservasPorFecha[$fechaReserva]++;
            }
            echo 'const reservasPorFecha = ' . json_encode($reservasPorFecha) . ';';
            ?>
            return reservasPorFecha[fecha] || 0;
        }

        function mostrarDetalleDiaProfesor(fecha) {
            const reservasDia = <?php echo json_encode($reservas); ?>.filter(r => new Date(r.class_date).toISOString().split('T')[0] === fecha);

            if (reservasDia.length > 0) {
                let detalle = `Reservas para ${fecha}:\n\n`;
                reservasDia.forEach(reserva => {
                    const fechaFormateada = new Date(fecha).toLocaleDateString('es-ES');
                    const horaInicio = reserva.start_time || 'N/A';
                    const horaFin = reserva.end_time || 'N/A';
                    detalle += `• ${reserva.estudiante_name} - ${reserva.reservation_status} (${fechaFormateada}, ${horaInicio} - ${horaFin})\n`;
                });
                alert(detalle);
            } else {
                alert(`No hay reservas programadas para ${fecha}`);
            }
        }

        // Función para hacer scroll a la sección de estudiantes
        function scrollToEstudiantes() {
            const estudiantesSection = document.getElementById('estudiantes-section');
            if (estudiantesSection) {
                estudiantesSection.scrollIntoView({ behavior: 'smooth' });
            }
        }

        // Inicializar calendario cuando se carga la página
        document.addEventListener('DOMContentLoaded', function() {
            inicializarCalendarioClases();
        });
    </script>
</body>
</html>