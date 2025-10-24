<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensajes - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php
    // Definir la página actual para el header
    $currentPage = 'mensajes';
    ?>
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">💬 Centro de Mensajes</h1>
            <?php include __DIR__ . '/nav.php'; ?>
        </div>
    </header>

    <main class="container my-5">
        <div class="row g-4">
            <!-- Lista de Conversaciones -->
            <div class="col-lg-4">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h4>👥 Conversaciones</h4>
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#nuevoMensajeModal">
                            ✉️ Nuevo Mensaje
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <?php if (!empty($conversaciones)): ?>
                                <?php foreach($conversaciones as $conv): ?>
                                    <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar-circle">
                                                <?php echo strtoupper(substr($conv['nombre'], 0, 1)); ?>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold"><?php echo htmlspecialchars($conv['nombre']); ?></div>
                                            <small class="text-muted"><?php echo htmlspecialchars(substr($conv['ultimo_mensaje'], 0, 30)); ?>...</small>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-muted"><?php echo date('H:i', strtotime($conv['fecha_ultimo'])); ?></small>
                                            <?php if($conv['no_leidos'] > 0): ?>
                                                <span class="badge bg-danger rounded-pill"><?php echo $conv['no_leidos']; ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <div class="empty-icon">💬</div>
                                    <p class="text-muted">No tienes conversaciones activas</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat Actual -->
            <div class="col-lg-8">
                <div class="dashboard-card">
                    <div class="card-header d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar-circle">A</div>
                        </div>
                        <div>
                            <h5 class="mb-0">Ana García</h5>
                            <small class="text-muted">Estudiante • En línea</small>
                        </div>
                    </div>
                    <div class="card-body chat-messages" id="chatMessages">
                        <!-- Mensajes del chat -->
                        <div class="message received">
                            <div class="message-content">
                                <p>Hola profesor, tengo una duda sobre la tarea de matemáticas.</p>
                                <small class="text-muted">10:30 AM</small>
                            </div>
                        </div>
                        <div class="message sent">
                            <div class="message-content">
                                <p>¡Hola Ana! Claro, ¿en qué puedo ayudarte?</p>
                                <small class="text-muted">10:32 AM</small>
                            </div>
                        </div>
                        <div class="message received">
                            <div class="message-content">
                                <p>Es sobre el ejercicio 5, no entiendo cómo resolverlo.</p>
                                <small class="text-muted">10:33 AM</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <form class="d-flex">
                            <input type="text" class="form-control me-2" placeholder="Escribe tu mensaje..." id="messageInput">
                            <button class="btn btn-primary" type="submit">
                                📤 Enviar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historial de Mensajes -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h4>📚 Historial de Mensajes</h4>
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" name="filtro" id="todos" autocomplete="off" checked>
                            <label class="btn btn-outline-secondary btn-sm" for="todos">Todos</label>
                            <input type="radio" class="btn-check" name="filtro" id="no-leidos" autocomplete="off">
                            <label class="btn btn-outline-secondary btn-sm" for="no-leidos">No Leídos</label>
                            <input type="radio" class="btn-check" name="filtro" id="importantes" autocomplete="off">
                            <label class="btn btn-outline-secondary btn-sm" for="importantes">Importantes</label>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($historial_mensajes)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>De</th>
                                            <th>Asunto</th>
                                            <th>Mensaje</th>
                                            <th>Fecha</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($historial_mensajes as $msg): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($msg['remitente']); ?></td>
                                                <td><?php echo htmlspecialchars($msg['asunto']); ?></td>
                                                <td><?php echo htmlspecialchars(substr($msg['contenido'], 0, 50)); ?>...</td>
                                                <td><?php echo date('d/m/Y H:i', strtotime($msg['fecha'])); ?></td>
                                                <td>
                                                    <?php if($msg['leido']): ?>
                                                        <span class="badge bg-success">Leído</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning">No Leído</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary">Ver</button>
                                                    <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-icon">📨</div>
                                <p>No tienes mensajes en el historial</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Nuevo Mensaje -->
    <div class="modal fade" id="nuevoMensajeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">✉️ Nuevo Mensaje</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="/plataforma-clases-online/home/enviar_mensaje" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="destinatario" class="form-label">Destinatario</label>
                            <select class="form-select" id="destinatario" name="destinatario" required>
                                <option value="">Seleccionar estudiante</option>
                                <?php if (!empty($estudiantes)): ?>
                                    <?php foreach($estudiantes as $est): ?>
                                        <option value="<?php echo $est['user_id']; ?>">
                                            <?php echo htmlspecialchars($est['first_name'] . ' ' . $est['last_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
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
                        <button type="submit" class="btn btn-primary">📤 Enviar Mensaje</button>
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
    <script>
        // Auto-scroll al final del chat
        const chatMessages = document.getElementById('chatMessages');
        chatMessages.scrollTop = chatMessages.scrollHeight;

        // Enviar mensaje con Enter
        document.getElementById('messageInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                // Aquí iría la lógica para enviar el mensaje
                console.log('Mensaje enviado:', this.value);
                this.value = '';
            }
        });
    </script>
</body>
</html>