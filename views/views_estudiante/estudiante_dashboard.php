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

        <!-- Secciones del Dashboard -->
        <div class="row g-4">
            <!-- Calendario de Reservas -->
            <div class="col-lg-6">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>📅 Mi Calendario de Clases</h3>
                        <span class="badge bg-primary">Próximas Clases</span>
                    </div>
                    <div class="card-body">
                        <div id="calendarioReservas" class="mb-3" style="overflow-x: auto;">
                            <!-- Calendario se cargará aquí -->
                        </div>
                        <?php if (empty($reservas)): ?>
                        <div class="empty-state">
                            <div class="empty-icon">📚</div>
                            <p>No tienes clases reservadas aún</p>
                            <a href="/plataforma-clases-online/home/explorar_profesores" class="btn btn-primary btn-sm">Explorar Profesores</a>
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
                        <div class="list-group">
                            <?php
                            // Ordenar reservas por fecha
                            usort($reservas, function($a, $b) {
                                return strtotime($a['class_date']) - strtotime($b['class_date']);
                            });
                            $proximasReservas = array_slice($reservas, 0, 3);
                            ?>
                            <?php foreach ($proximasReservas as $reserva): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1"><?php echo htmlspecialchars($reserva['profesor_name']); ?></h6>
                                        <small class="text-muted">
                                            📅 <?php echo date('d/m/Y', strtotime($reserva['class_date'])); ?> -
                                            <?php echo htmlspecialchars($reserva['reservation_status']); ?>
                                        </small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-<?php echo $reserva['reservation_status'] === 'confirmada' ? 'success' : ($reserva['reservation_status'] === 'pendiente' ? 'warning' : 'secondary'); ?> me-2">
                                            <?php echo htmlspecialchars($reserva['reservation_status']); ?>
                                        </span>
                                        <?php if ($reserva['reservation_status'] === 'pendiente' || $reserva['reservation_status'] === 'confirmada'): ?>
                                        <form method="post" action="/plataforma-clases-online/home/cancelar_reserva" style="display: inline;" onsubmit="return confirm('¿Estás seguro de que quieres cancelar esta clase?');">
                                            <input type="hidden" name="reservation_id" value="<?php echo htmlspecialchars($reserva['reservation_id']); ?>">
                                            <button type="submit" class="btn btn-outline-danger btn-sm">❌ Cancelar</button>
                                        </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
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
    <script>
        // Función para inicializar el calendario de reservas
        function inicializarCalendarioReservas() {
            const calendarioContainer = document.getElementById('calendarioReservas');
            const hoy = new Date();
            const mesActual = hoy.getMonth();
            const anioActual = hoy.getFullYear();

            // Crear calendario simple
            const calendarioHTML = generarCalendario(mesActual, anioActual);
            calendarioContainer.innerHTML = calendarioHTML;
        }

        function generarCalendario(mes, anio) {
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
                        const tieneReserva = verificarReservaEnFecha(fechaActual);
                        const claseDia = tieneReserva ? 'table-primary' : '';
                        const hoyClass = fechaActual === new Date().toISOString().split('T')[0] ? 'fw-bold' : '';

                        html += `<td class="text-center ${claseDia} ${hoyClass}" style="cursor: pointer;" onclick="mostrarDetalleDia('${fechaActual}')">${diaActual}</td>`;
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
                    <span class="badge bg-primary me-2">●</span> Día con reserva
                </div>
            `;

            return html;
        }

        function verificarReservaEnFecha(fecha) {
            // Verificar si hay reserva en esta fecha (comparar solo la fecha, no la hora)
            <?php
            $fechasReservas = array_map(function($reserva) {
                return date('Y-m-d', strtotime($reserva['class_date']));
            }, $reservas);
            echo 'const fechasReservas = ' . json_encode($fechasReservas) . ';';
            ?>
            return fechasReservas.includes(fecha);
        }

        function mostrarDetalleDia(fecha) {
            // Verificar si hay reserva en esta fecha
            const reservasDia = <?php echo json_encode($reservas); ?>.filter(r => {
                const fechaReserva = new Date(r.class_date);
                const fechaComparar = new Date(fecha);
                return fechaReserva.toDateString() === fechaComparar.toDateString();
            });

            if (reservasDia.length > 0) {
                let detalle = `Reservas para ${fecha}:\n\n`;
                reservasDia.forEach(reserva => {
                    detalle += `• ${reserva.profesor_name} - ${reserva.reservation_status}\n`;
                });
                alert(detalle);
            } else {
                alert(`No hay reservas programadas para ${fecha}`);
            }
        }

        // Inicializar calendario cuando se carga la página
        document.addEventListener('DOMContentLoaded', function() {
            inicializarCalendarioReservas();
        });
    </script>
</body>
</html>