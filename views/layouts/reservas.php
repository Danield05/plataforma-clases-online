<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📅 Reservas - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/reservas.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php
    // Definir la página actual para el header
    $currentPage = 'reservas';
    ?>
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">📅 Reservas</h1>
            <?php include __DIR__ . '/nav.php'; ?>
        </div>
    </header>

    <main class="container my-5">

        <!-- Mostrar mensaje de éxito o error si existe -->
        <?php if (!empty($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php if ($_GET['success'] === 'cancelled'): ?>
                    ✅ Clase cancelada exitosamente
                <?php elseif ($_GET['success'] === 'completed'): ?>
                    ✅ Clase marcada como completada exitosamente
                <?php elseif ($_GET['success'] === 'rescheduled'): ?>
                    ✅ Clase reagendada exitosamente
                <?php elseif ($_GET['success'] === 'estados_fixed'): ?>
                    ✅ Estados de reserva corregidos exitosamente
                <?php endif; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php if ($_GET['error'] === 'cancel_failed'): ?>
                    ❌ Error al cancelar la clase
                    <?php if (!empty($_GET['debug'])): ?>
                        <br><small>Debug: <?php echo htmlspecialchars($_GET['debug']); ?></small>
                    <?php endif; ?>
                <?php elseif ($_GET['error'] === 'complete_failed'): ?>
                    ❌ Error al completar la clase
                <?php elseif ($_GET['error'] === 'reschedule_failed'): ?>
                    ❌ Error al reagendar la clase
                    <?php if (!empty($_GET['debug'])): ?>
                        <br><small>Debug: <?php echo htmlspecialchars($_GET['debug']); ?></small>
                    <?php endif; ?>
                <?php elseif ($_GET['error'] === 'not_authorized'): ?>
                    ❌ No tienes permisos para realizar esta acción
                <?php elseif ($_GET['error'] === 'invalid_status'): ?>
                    ❌ Solo puedes completar clases confirmadas
                <?php elseif ($_GET['error'] === 'invalid_status_reschedule'): ?>
                    ❌ Solo puedes reagendar clases pendientes o confirmadas
                <?php endif; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (empty($reservas)): ?>
            <div class="text-center py-5">
                <div class="mb-4" style="font-size: 4rem; color: #6c757d;">📋</div>
                <h3>No hay reservas registradas</h3>
                <p class="text-muted">Las reservas aparecerán aquí cuando se creen.</p>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-12">
                <div class="col-12">
                    <div class="reservas-table">
                        <div class="reservas-header d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-1">📋 Gestión de Reservas</h3>
                                <span class="badge" style="background: rgba(255,255,255,0.2);">Lista completa de reservas</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>ID Reserva</th>
                                            <?php if ($_SESSION['role'] === 'estudiante'): ?>
                                            <th>Profesor</th>
                                            <?php elseif ($_SESSION['role'] === 'profesor'): ?>
                                            <th>Estudiante</th>
                                            <?php else: ?>
                                            <th>Profesor</th>
                                            <th>Estudiante</th>
                                            <?php endif; ?>
                                            <th>Fecha de Clase</th>
                                            <th>Estado</th>
                                            <?php if (isset($_SESSION['role']) && ($_SESSION['role'] === 'estudiante' || $_SESSION['role'] === 'profesor')): ?>
                                            <th>Acciones</th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($reservas as $reserva): ?>
                                            <tr>
                                                <td><span class="badge bg-light text-dark">#<?php echo $reserva['reservation_id']; ?></span></td>
                                                <?php if ($_SESSION['role'] === 'estudiante'): ?>
                                                <td><?php echo htmlspecialchars($reserva['profesor_name'] . ' ' . $reserva['profesor_last_name']); ?></td>
                                                <?php elseif ($_SESSION['role'] === 'profesor'): ?>
                                                <td><?php echo htmlspecialchars($reserva['estudiante_name'] . ' ' . $reserva['estudiante_last_name']); ?></td>
                                                <?php else: ?>
                                                <td><?php echo htmlspecialchars($reserva['profesor_name'] . ' ' . $reserva['profesor_last_name']); ?></td>
                                                <td><?php echo htmlspecialchars($reserva['estudiante_name'] . ' ' . $reserva['estudiante_last_name']); ?></td>
                                                <?php endif; ?>
                                                <td><?php echo date('d/m/Y', strtotime($reserva['class_date'])); ?> (<?php echo isset($reserva['start_time']) ? date('H:i', strtotime($reserva['start_time'])) : 'N/A'; ?> - <?php echo isset($reserva['end_time']) ? date('H:i', strtotime($reserva['end_time'])) : 'N/A'; ?>)</td>
                                                <td>
                                                    <?php
                                                    $estado = strtolower($reserva['reservation_status']);
                                                    $estadoClass = '';

                                                    switch($estado) {
                                                        case 'pendiente':
                                                            $estadoClass = 'estado-pendiente';
                                                            break;
                                                        case 'confirmada':
                                                            $estadoClass = 'estado-confirmada';
                                                            break;
                                                        case 'cancelada':
                                                            $estadoClass = 'estado-cancelada';
                                                            break;
                                                        case 'completada':
                                                            $estadoClass = 'estado-completada';
                                                            break;
                                                        default:
                                                            $estadoClass = 'estado-pendiente';
                                                    }
                                                    ?>
                                                    <span class="<?php echo $estadoClass; ?>">
                                                        <?php echo htmlspecialchars($reserva['reservation_status']); ?>
                                                    </span>
                                                </td>
                                                <?php if ($_SESSION['role'] === 'estudiante'): ?>
                                                <td>
                                                    <?php if (strtolower($reserva['reservation_status']) === 'pendiente' || strtolower($reserva['reservation_status']) === 'confirmada'): ?>
                                                    <form method="post" action="/plataforma-clases-online/home/cancelar_reserva" style="display: inline;" onsubmit="return confirm('¿Estás seguro de que quieres cancelar esta clase?');">
                                                        <input type="hidden" name="reservation_id" value="<?php echo htmlspecialchars($reserva['reservation_id']); ?>">
                                                        <button type="submit" class="btn btn-cancelar btn-sm">❌ Cancelar</button>
                                                    </form>
                                                    <?php else: ?>
                                                    <span class="badge bg-secondary">No cancelable</span>
                                                    <?php endif; ?>
                                                </td>
                                                <?php elseif ($_SESSION['role'] === 'profesor'): ?>
                                                <td>
                                                    <?php if (strtolower($reserva['reservation_status']) === 'completada'): ?>
                                                        <!-- Clase completada - mostrar icono de detalle -->
                                                        <div class="text-center">
                                                            <span class="badge bg-success" style="font-size: 0.9rem; padding: 8px 12px;">
                                                                ✅ Clase completada
                                                            </span>
                                                        </div>
                                                    <?php else: ?>
                                                        <!-- Clase no completada - mostrar botones de acción -->
                                                        <div class="d-flex gap-1 flex-wrap">
                                                            <?php if (strtolower($reserva['reservation_status']) === 'confirmada'): ?>
                                                                <form method="post" action="/plataforma-clases-online/home/completar_reserva" id="completeForm" style="display: inline;">
                                                                    <input type="hidden" name="reservation_id" value="<?php echo htmlspecialchars($reserva['reservation_id']); ?>">
                                                                    <button type="button" class="btn btn-completar btn-sm" onclick="showCompleteModal(<?php echo htmlspecialchars($reserva['reservation_id']); ?>)">✅ Completar</button>
                                                                </form>
                                                            <?php endif; ?>

                                                            <?php if (strtolower($reserva['reservation_status']) === 'pendiente' || strtolower($reserva['reservation_status']) === 'confirmada'): ?>
                                                                <form method="post" action="/plataforma-clases-online/home/cancelar_reserva" style="display: inline;" onsubmit="return confirm('¿Estás seguro de que quieres cancelar esta clase? Esto permitirá reagendarla si es necesario.');">
                                                                    <input type="hidden" name="reservation_id" value="<?php echo htmlspecialchars($reserva['reservation_id']); ?>">
                                                                    <button type="submit" class="btn btn-cancelar btn-sm">❌ Cancelar</button>
                                                                </form>

                                                                <!-- Botón Reagendar -->
                                                                <button type="button" class="btn btn-reagendar btn-sm" onclick="openRescheduleModal('<?php echo htmlspecialchars($reserva['reservation_id']); ?>', '<?php echo htmlspecialchars(($reserva['estudiante_name'] ?? 'Estudiante') . ' ' . ($reserva['estudiante_last_name'] ?? '')); ?>', '<?php echo date('Y-m-d', strtotime($reserva['class_date'])); ?>')">📅 Reagendar</button>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <?php endif; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
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

    <!-- Modal para reagendar -->
    <div class="modal fade" id="rescheduleModal" tabindex="-1" aria-labelledby="rescheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rescheduleModalLabel">📅 Reagendar Clase</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="/plataforma-clases-online/home/reagendar_reserva">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="estudianteInfo" class="form-label">Estudiante</label>
                            <input type="text" class="form-control" id="estudianteInfo" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="diasTrabajo" class="form-label">📅 Mi horario de trabajo</label>
                            <div id="diasTrabajo" class="alert alert-light">
                                <small>Cargando horario de trabajo...</small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="newDate" class="form-label">Nueva Fecha *</label>
                            <input type="date" class="form-control" id="newDate" name="new_date" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="newAvailability" class="form-label">Horario Disponible</label>
                            <select class="form-control" id="newAvailability" name="new_availability_id">
                                <option value="">Primero selecciona una fecha...</option>
                                <!-- Los horarios disponibles se cargarán dinámicamente -->
                            </select>
                        </div>
                        <input type="hidden" id="reservationId" name="reservation_id">
                        <div class="alert alert-info">
                            <small>💡 Selecciona una fecha para ver los horarios disponibles. Si no hay horarios disponibles, significa que ese día no trabajo o ya tengo clases programadas.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">📅 Reagendar Clase</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/plataforma-clases-online/public/js/script.js"></script>
    <script src="/plataforma-clases-online/public/js/reservas.js"></script>
    <!-- Modal para confirmar completar reserva -->
    <div class="modal fade" id="completeReservationModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">¿Estás seguro?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que quieres marcar esta clase como completada?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-completar" id="confirmComplete">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>