<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil del Estudiante - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/ver_estudiante.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php 
    // Definir la página actual para el header
    $currentPage = 'estudiantes';
    ?>
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">👤 Perfil del Estudiante</h1>
            <?php include __DIR__ . '/nav.php'; ?>
        </div>
    </header>
    
    <main>
        <div class="container my-5">
            <div class="row g-4">
            <!-- Información del Estudiante -->
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-body text-center">
                        <div class="avatar-large mb-3">
                            <?php echo strtoupper(substr($estudiante['first_name'], 0, 1) . substr($estudiante['last_name'], 0, 1)); ?>
                        </div>
                        <h4 class="mb-2"><?php echo htmlspecialchars($estudiante['first_name'] . ' ' . $estudiante['last_name']); ?></h4>
                        <p class="text-muted mb-3"><?php echo htmlspecialchars($estudiante['email']); ?></p>
                        <span class="badge bg-success fs-6 px-3 py-2">
                            <i class="fas fa-circle me-1"></i>Estudiante Activo
                        </span>
                    </div>
                </div>

                <!-- Información de Contacto -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">📞 Información de Contacto</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-4">
                                <strong>Email:</strong>
                            </div>
                            <div class="col-8">
                                <small class="text-muted"><?php echo htmlspecialchars($estudiante['email']); ?></small>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4">
                                <strong>Registro:</strong>
                            </div>
                            <div class="col-8">
                                <small class="text-muted"><?php echo date('d/m/Y', strtotime($estudiante['created_at'] ?? 'now')); ?></small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <strong>Última conexión:</strong>
                            </div>
                            <div class="col-8">
                                <small class="text-muted"><?php echo date('d/m/Y H:i', strtotime($estudiante['last_login'] ?? 'now')); ?></small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones Rápidas -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">⚡ Acciones Rápidas</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#mensajeModal">
                                <i class="fas fa-comment me-2"></i>💬 Enviar Mensaje
                            </button>
                            <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#claseModal">
                                <i class="fas fa-calendar-plus me-2"></i>📅 Programar Clase
                            </button>
                            <button class="btn btn-outline-info">
                                <i class="fas fa-chart-line me-2"></i>📊 Ver Historial Completo
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detalles y Estadísticas -->
            <div class="col-lg-8">
                <!-- Estadísticas del Estudiante -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="card text-center border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="text-primary fs-1 mb-2">📚</div>
                                <h3 class="text-primary mb-1"><?php echo $estadisticas['clases_totales'] ?? 0; ?></h3>
                                <p class="text-muted small mb-0">Clases Totales</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="text-success fs-1 mb-2">✅</div>
                                <h3 class="text-success mb-1"><?php echo $estadisticas['clases_completadas'] ?? 0; ?></h3>
                                <p class="text-muted small mb-0">Completadas</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="text-warning fs-1 mb-2">⏳</div>
                                <h3 class="text-warning mb-1"><?php echo $estadisticas['clases_pendientes'] ?? 0; ?></h3>
                                <p class="text-muted small mb-0">Pendientes</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="text-info fs-1 mb-2">💰</div>
                                <h3 class="text-info mb-1">$<?php echo number_format($estadisticas['total_invertido'] ?? 0, 2); ?></h3>
                                <p class="text-muted small mb-0">Total Invertido</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Historial de Clases -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">📅 Historial de Clases</h4>
                        <span class="badge bg-primary"><?php echo count($historial_clases ?? []); ?> clases</span>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($historial_clases)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="border-0 py-3">Fecha</th>
                                            <th class="border-0 py-3">Horario</th>
                                            <th class="border-0 py-3">Materia</th>
                                            <th class="border-0 py-3">Estado</th>
                                            <th class="border-0 py-3">Calificación</th>
                                            <th class="border-0 py-3">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($historial_clases as $clase): ?>
                                            <tr>
                                                <td class="py-3">
                                                    <strong><?php echo date('d/m/Y', strtotime($clase['class_date'])); ?></strong>
                                                </td>
                                                <td class="py-3">
                                                    <span class="text-muted">
                                                        <?php echo date('H:i', strtotime($clase['start_time'])); ?> - 
                                                        <?php echo date('H:i', strtotime($clase['end_time'])); ?>
                                                    </span>
                                                </td>
                                                <td class="py-3">
                                                    <span class="badge bg-light text-dark">
                                                        <?php echo htmlspecialchars($clase['materia'] ?? 'General'); ?>
                                                    </span>
                                                </td>
                                                <td class="py-3">
                                                    <?php
                                                    $estado = $clase['reservation_status'];
                                                    $badgeClass = 'bg-secondary';
                                                    if ($estado === 'completada') $badgeClass = 'bg-success';
                                                    elseif ($estado === 'confirmada') $badgeClass = 'bg-primary';
                                                    elseif ($estado === 'cancelada') $badgeClass = 'bg-danger';
                                                    ?>
                                                    <span class="badge <?php echo $badgeClass; ?>">
                                                        <?php echo htmlspecialchars($estado); ?>
                                                    </span>
                                                </td>
                                                <td class="py-3">
                                                    <?php if(isset($clase['rating'])): ?>
                                                        <div class="d-flex align-items-center">
                                                            <span class="text-warning me-1">⭐</span>
                                                            <span class="fw-bold"><?php echo $clase['rating']; ?>/5</span>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="text-muted">Sin calificar</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="py-3">
                                                    <button class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <div class="text-muted fs-1 mb-3">📚</div>
                                <h5 class="text-muted">No hay clases registradas</h5>
                                <p class="text-muted">Este estudiante aún no tiene clases programadas</p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#claseModal">
                                    <i class="fas fa-plus me-2"></i>Programar Primera Clase
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Calificaciones y Comentarios -->
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">⭐ Calificaciones y Comentarios</h4>
                        <span class="badge bg-warning"><?php echo count($calificaciones ?? []); ?> reviews</span>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($calificaciones)): ?>
                            <div class="row g-3">
                                <?php foreach($calificaciones as $cal): ?>
                                    <div class="col-12">
                                        <div class="border rounded p-3 bg-light">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3">
                                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                                            <span class="<?php echo $i <= $cal['rating'] ? 'text-warning' : 'text-muted'; ?>">★</span>
                                                        <?php endfor; ?>
                                                    </div>
                                                    <span class="badge bg-warning text-dark fw-bold">
                                                        <?php echo $cal['rating']; ?>/5
                                                    </span>
                                                </div>
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    <?php echo date('d/m/Y', strtotime($cal['created_at'])); ?>
                                                </small>
                                            </div>
                                            <blockquote class="mb-0">
                                                <p class="mb-0 fst-italic">
                                                    "<?php echo htmlspecialchars($cal['comment'] ?? 'Sin comentario adicional'); ?>"
                                                </p>
                                            </blockquote>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <div class="text-muted fs-1 mb-3">⭐</div>
                                <h5 class="text-muted">No hay calificaciones disponibles</h5>
                                <p class="text-muted">Este estudiante aún no ha recibido calificaciones</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Enviar Mensaje -->
    <div class="modal fade" id="mensajeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">💬 Enviar Mensaje</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="/plataforma-clases-online/home/enviar_mensaje_estudiante" method="POST">
                    <input type="hidden" name="estudiante_id" value="<?php echo $estudiante['user_id']; ?>">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="asunto" class="form-label">Asunto</label>
                            <input type="text" class="form-control" id="asunto" name="asunto" required>
                        </div>
                        <div class="mb-3">
                            <label for="mensaje" class="form-label">Mensaje</label>
                            <textarea class="form-control" id="mensaje" name="mensaje" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">📤 Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Programar Clase -->
    <div class="modal fade" id="claseModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">📅 Programar Clase</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="/plataforma-clases-online/home/crear_clase_estudiante" method="POST">
                    <input type="hidden" name="estudiante_id" value="<?php echo $estudiante['user_id']; ?>">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="fecha_clase" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="fecha_clase" name="class_date" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="hora_inicio" class="form-label">Hora Inicio</label>
                                <input type="time" class="form-control" id="hora_inicio" name="start_time" required>
                            </div>
                            <div class="col-md-6">
                                <label for="hora_fin" class="form-label">Hora Fin</label>
                                <input type="time" class="form-control" id="hora_fin" name="end_time" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">📅 Programar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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