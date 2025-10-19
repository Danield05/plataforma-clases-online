<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil del Estudiante - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php
    // Definir la página actual para el header
    $currentPage = 'ver_estudiante';
    ?>
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">👤 Perfil del Estudiante</h1>
            <?php include __DIR__ . '/nav.php'; ?>
        </div>
    </header>

    <main class="container my-5">
        <div class="row g-4">
            <!-- Información del Estudiante -->
            <div class="col-lg-4">
                <div class="dashboard-card text-center">
                    <div class="card-body">
                        <div class="avatar-large mb-3">
                            <?php echo strtoupper(substr($estudiante['first_name'], 0, 1) . substr($estudiante['last_name'], 0, 1)); ?>
                        </div>
                        <h4><?php echo htmlspecialchars($estudiante['first_name'] . ' ' . $estudiante['last_name']); ?></h4>
                        <p class="text-muted"><?php echo htmlspecialchars($estudiante['email']); ?></p>
                        <div class="mt-3">
                            <span class="badge bg-success">Estudiante Activo</span>
                        </div>
                    </div>
                </div>

                <!-- Información de Contacto -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h5>📞 Información de Contacto</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <strong>Email:</strong><br>
                            <small><?php echo htmlspecialchars($estudiante['email']); ?></small>
                        </div>
                        <div class="mb-2">
                            <strong>Fecha de Registro:</strong><br>
                            <small><?php echo date('d/m/Y', strtotime($estudiante['created_at'] ?? 'now')); ?></small>
                        </div>
                        <div class="mb-2">
                            <strong>Última Actividad:</strong><br>
                            <small><?php echo date('d/m/Y H:i', strtotime($estudiante['last_login'] ?? 'now')); ?></small>
                        </div>
                    </div>
                </div>

                <!-- Acciones Rápidas -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h5>⚡ Acciones</h5>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-outline-primary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#mensajeModal">
                            💬 Enviar Mensaje
                        </button>
                        <button class="btn btn-outline-success w-100 mb-2" data-bs-toggle="modal" data-bs-target="#claseModal">
                            📅 Programar Clase
                        </button>
                        <button class="btn btn-outline-info w-100">
                            📊 Ver Historial
                        </button>
                    </div>
                </div>
            </div>

            <!-- Detalles y Estadísticas -->
            <div class="col-lg-8">
                <!-- Estadísticas del Estudiante -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="dashboard-card text-center">
                            <div class="stat-icon">📚</div>
                            <div class="stat-value text-primary"><?php echo $estadisticas['clases_totales'] ?? 0; ?></div>
                            <p class="stat-label">Clases Totales</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="dashboard-card text-center">
                            <div class="stat-icon">✅</div>
                            <div class="stat-value text-success"><?php echo $estadisticas['clases_completadas'] ?? 0; ?></div>
                            <p class="stat-label">Completadas</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="dashboard-card text-center">
                            <div class="stat-icon">⏳</div>
                            <div class="stat-value text-warning"><?php echo $estadisticas['clases_pendientes'] ?? 0; ?></div>
                            <p class="stat-label">Pendientes</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="dashboard-card text-center">
                            <div class="stat-icon">💰</div>
                            <div class="stat-value text-info">$<?php echo number_format($estadisticas['total_invertido'] ?? 0, 2); ?></div>
                            <p class="stat-label">Total Invertido</p>
                        </div>
                    </div>
                </div>

                <!-- Historial de Clases -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h4>📅 Historial de Clases</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($historial_clases)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Hora</th>
                                            <th>Materia</th>
                                            <th>Estado</th>
                                            <th>Calificación</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($historial_clases as $clase): ?>
                                            <tr>
                                                <td><?php echo date('d/m/Y', strtotime($clase['class_date'])); ?></td>
                                                <td><?php echo date('H:i', strtotime($clase['start_time'])); ?> - <?php echo date('H:i', strtotime($clase['end_time'])); ?></td>
                                                <td><?php echo htmlspecialchars($clase['materia'] ?? 'General'); ?></td>
                                                <td>
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
                                                <td>
                                                    <?php if(isset($clase['rating'])): ?>
                                                        <span class="badge bg-warning">⭐ <?php echo $clase['rating']; ?>/5</span>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary">Ver Detalles</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-icon">📚</div>
                                <p>Este estudiante no tiene clases registradas</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Calificaciones y Comentarios -->
                <div class="dashboard-card mt-4">
                    <div class="card-header">
                        <h4>⭐ Calificaciones y Comentarios</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($calificaciones)): ?>
                            <div class="row g-3">
                                <?php foreach($calificaciones as $cal): ?>
                                    <div class="col-12">
                                        <div class="review-card">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="me-3">
                                                    <span class="badge bg-warning">⭐ <?php echo $cal['rating']; ?>/5</span>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <small class="text-muted">
                                                        <?php echo date('d/m/Y', strtotime($cal['created_at'])); ?>
                                                    </small>
                                                </div>
                                            </div>
                                            <p class="mb-0"><?php echo htmlspecialchars($cal['comment'] ?? 'Sin comentario'); ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-icon">⭐</div>
                                <p>No hay calificaciones disponibles</p>
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