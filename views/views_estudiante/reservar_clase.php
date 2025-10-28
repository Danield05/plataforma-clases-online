<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📅 Reservar Clase - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/explorar_profesores.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/reservar_clase.css?v=<?php echo time(); ?>">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
</head>
<body>
    <?php
    // Definir la página actual para el header
    $currentPage = 'reservar';
    ?>
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">📅 Reservar Clase</h1>
            <?php include __DIR__ . '/../layouts/nav.php'; ?>
        </div>
    </header>

    <main class="container mt-4">
        <!-- Información del profesor -->
        <div class="profesor-info">
            <h3>👨‍🏫 Información del Profesor</h3>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nombre:</strong> <?php echo htmlspecialchars($profesor['first_name'] . ' ' . $profesor['last_name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($profesor['email']); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Nivel Académico:</strong> <?php echo htmlspecialchars($profesor['academic_level'] ?? 'N/A'); ?></p>
                    <p><strong>Tarifa por Hora:</strong> <?php echo $profesor['hourly_rate'] ? '$' . htmlspecialchars($profesor['hourly_rate']) : 'N/A'; ?></p>
                </div>
            </div>
            <?php if (!empty($profesor['personal_description'])): ?>
                <div class="mt-3">
                    <strong>Descripción:</strong>
                    <p><?php echo htmlspecialchars($profesor['personal_description']); ?></p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Mostrar errores si existen -->
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <?php
                $errorMessages = [
                    'missing_data' => 'Faltan datos para procesar la reserva.',
                    'not_available' => 'El horario seleccionado ya no está disponible.',
                    'creation_failed' => 'Error al crear la reserva. Inténtalo de nuevo.',
                    'missing_profesor' => 'Profesor no especificado.',
                    'profesor_not_found' => 'Profesor no encontrado.'
                ];
                echo $errorMessages[$_GET['error']] ?? 'Ha ocurrido un error.';
                ?>
            </div>
        <?php endif; ?>

        <h3 class="mb-4">🕒 Selecciona un Horario Disponible</h3>

        <?php if (empty($available_slots)): ?>
            <div class="alert alert-warning">
                <h5>⚠️ No hay horarios disponibles</h5>
                <p>Este profesor no tiene horarios disponibles en los próximos 90 días. Intenta contactarlo directamente o revisa más tarde.</p>
                <a href="/plataforma-clases-online/home/explorar_profesores" class="btn btn-primary">← Volver a Explorar Profesores</a>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-md-8">
                    <div id="calendar"></div>
                </div>
                <div class="col-md-4">
                    <div id="time-slots" class="card" style="display: none;">
                        <div class="card-header">
                            <h5>🕒 Horarios Disponibles</h5>
                            <div id="selected-date-display"></div>
                        </div>
                        <div class="card-body" id="slots-container">
                            <!-- Los slots se cargarán aquí dinámicamente -->
                        </div>
                    </div>
                </div>
            </div>

            <form id="reservationForm" method="post" action="/plataforma-clases-online/home/reservar_clase?profesor_id=<?php echo htmlspecialchars($profesor['user_id']); ?>" style="display: none;">
                <input type="hidden" name="profesor_id" value="<?php echo htmlspecialchars($profesor['user_id']); ?>">
                <input type="hidden" id="selectedAvailabilityId" name="availability_id" value="">
                <input type="hidden" id="selectedClassDate" name="class_date" value="">
                <input type="hidden" id="selectedClassTime" name="class_time" value="">

                <!-- Campos adicionales para la reserva -->
                <div class="row mt-4" id="additionalFields">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>📝 Detalles Adicionales de la Reserva</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notas o comentarios especiales (opcional):</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3"
                                              placeholder="Ejemplo: Enfoque en álgebra, necesito ayuda con ejercicios específicos, etc."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" id="submitBtn" class="btn btn-success btn-lg px-5 py-3" disabled>
                        ✅ Confirmar Reserva
                    </button>
                    <a href="/plataforma-clases-online/home/explorar_profesores" class="btn btn-secondary btn-lg ms-3">
                        ← Cancelar
                    </a>
                </div>
            </form>
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
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <script>
        // Pasar datos de PHP a JavaScript
        window.availableSlots = <?php echo json_encode($available_slots); ?>;
    </script>
    <script src="/plataforma-clases-online/public/js/reservar_clase.js?v=<?php echo time(); ?>"></script>
</body>
</html>