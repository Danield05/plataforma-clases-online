<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📅 Reservas - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
    <style>
        .reservas-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            border-radius: 10px 10px 0 0;
            padding: 20px;
            margin-bottom: 0;
        }
        .reservas-table {
            background: white;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .estado-pendiente {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        .estado-confirmada {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        .estado-cancelada {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        .estado-completada {
            background: linear-gradient(135deg, #6f42c1, #8e44ad);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        .btn-cancelar {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        .btn-completar {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        .table th {
            background: #f8f9fa;
            border: none;
            font-weight: 600;
            color: #495057;
        }
        .table td {
            border: none;
            vertical-align: middle;
        }
    </style>
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
                <?php elseif ($_GET['error'] === 'not_authorized'): ?>
                    ❌ No tienes permisos para realizar esta acción
                <?php elseif ($_GET['error'] === 'invalid_status'): ?>
                    ❌ Solo puedes completar clases confirmadas
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
                                            <?php if ($_SESSION['role'] === 'estudiante' || $_SESSION['role'] === 'profesor'): ?>
                                            <th>Acciones</th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($reservas as $reserva): ?>
                                            <tr>
                                                <td><span class="badge bg-light text-dark">#<?php echo $reserva['reservation_id']; ?></span></td>
                                                <?php if ($_SESSION['role'] === 'estudiante'): ?>
                                                <td><?php echo htmlspecialchars($reserva['profesor_name']); ?></td>
                                                <?php elseif ($_SESSION['role'] === 'profesor'): ?>
                                                <td><?php echo htmlspecialchars($reserva['estudiante_name']); ?></td>
                                                <?php else: ?>
                                                <td><?php echo htmlspecialchars($reserva['profesor_name']); ?></td>
                                                <td><?php echo htmlspecialchars($reserva['estudiante_name']); ?></td>
                                                <?php endif; ?>
                                                <td><?php echo date('d/m/Y H:i', strtotime($reserva['class_date'])); ?></td>
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
                                                    <?php if ($reserva['reservation_status'] === 'pendiente' || $reserva['reservation_status'] === 'confirmada'): ?>
                                                    <form method="post" action="/plataforma-clases-online/home/cancelar_reserva" style="display: inline;" onsubmit="return confirm('¿Estás seguro de que quieres cancelar esta clase?');">
                                                        <input type="hidden" name="reservation_id" value="<?php echo htmlspecialchars($reserva['reservation_id']); ?>">
                                                        <button type="submit" class="btn btn-cancelar btn-sm">❌ Cancelar</button>
                                                    </form>
                                                    <?php else: ?>
                                                    <span class="badge bg-secondary">No cancelable</span>
                                                    <small class="text-muted d-block">Estado: <?php echo htmlspecialchars($reserva['reservation_status']); ?> (ID: <?php echo $reserva['reservation_status_id']; ?>)</small>
                                                    <?php endif; ?>
                                                </td>
                                                <?php elseif ($_SESSION['role'] === 'profesor'): ?>
                                                <td>
                                                    <div class="d-flex gap-1 flex-wrap">
                                                        <?php if ($reserva['reservation_status'] === 'confirmada'): ?>
                                                        <form method="post" action="/plataforma-clases-online/home/completar_reserva" style="display: inline;" onsubmit="return confirm('¿Estás seguro de que quieres marcar esta clase como completada?');">
                                                            <input type="hidden" name="reservation_id" value="<?php echo htmlspecialchars($reserva['reservation_id']); ?>">
                                                            <button type="submit" class="btn btn-completar btn-sm">✅ Completar</button>
                                                        </form>
                                                        <?php endif; ?>

                                                        <?php if ($reserva['reservation_status'] === 'pendiente' || $reserva['reservation_status'] === 'confirmada'): ?>
                                                        <form method="post" action="/plataforma-clases-online/home/cancelar_reserva" style="display: inline;" onsubmit="return confirm('¿Estás seguro de que quieres cancelar esta clase? Esto permitirá reagendarla si es necesario.');">
                                                            <input type="hidden" name="reservation_id" value="<?php echo htmlspecialchars($reserva['reservation_id']); ?>">
                                                            <button type="submit" class="btn btn-cancelar btn-sm">❌ Cancelar</button>
                                                        </form>
                                                        <?php endif; ?>

                                                        <?php if ($reserva['reservation_status'] === 'completada'): ?>
                                                        <span class="badge bg-success">✅ Completada</span>
                                                        <small class="text-muted d-block">Clase finalizada</small>
                                                        <?php elseif ($reserva['reservation_status'] === 'cancelada'): ?>
                                                        <span class="badge bg-danger">❌ Cancelada</span>
                                                        <small class="text-muted d-block">Clase cancelada</small>
                                                        <?php else: ?>
                                                        <span class="badge bg-secondary"><?php echo htmlspecialchars($reserva['reservation_status']); ?></span>
                                                        <?php endif; ?>
                                                    </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/plataforma-clases-online/public/js/script.js"></script>
</body>
</html>