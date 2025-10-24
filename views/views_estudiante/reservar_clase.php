<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📅 Reservar Clase - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/explorar_profesores.css?v=<?php echo time(); ?>">
    <style>
        .slot-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .slot-card:hover {
            border-color: #007bff;
            box-shadow: 0 2px 8px rgba(0,123,255,0.1);
        }
        .slot-card.selected {
            border-color: #28a745;
            background-color: #f8fff9;
        }
        .slot-time {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
        }
        .slot-day {
            color: #666;
            font-size: 0.9rem;
        }
        .date-section {
            margin-bottom: 30px;
        }
        .date-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .profesor-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
    </style>
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
                <p>Este profesor no tiene horarios disponibles en los próximos 7 días. Intenta contactarlo directamente o revisa más tarde.</p>
                <a href="/plataforma-clases-online/home/explorar_profesores" class="btn btn-primary">← Volver a Explorar Profesores</a>
            </div>
        <?php else: ?>
            <form id="reservationForm" method="post" action="/plataforma-clases-online/home/reservar_clase?profesor_id=<?php echo htmlspecialchars($profesor['user_id']); ?>">
                <input type="hidden" name="profesor_id" value="<?php echo htmlspecialchars($profesor['user_id']); ?>">
                <input type="hidden" id="selectedAvailabilityId" name="availability_id" value="">
                <input type="hidden" id="selectedClassDate" name="class_date" value="">
                <input type="hidden" id="selectedClassTime" name="class_time" value="">

                <?php foreach ($available_slots as $date => $dayData): ?>
                    <div class="date-section">
                        <div class="date-header">
                            <h4><?php echo htmlspecialchars($dayData['day_name'] . ' ' . $dayData['date_display']); ?></h4>
                        </div>

                        <div class="row">
                            <?php foreach ($dayData['slots'] as $slot): ?>
                                <div class="col-md-4 mb-3">
                                    <div class="slot-card" 
                                         data-availability-id="<?php echo $slot['availability_id']; ?>" 
                                         data-date="<?php echo $date; ?>"
                                         data-start-time="<?php echo $slot['start_time']; ?>">
                                        <div class="slot-time">
                                            🕒 <?php echo date('H:i', strtotime($slot['start_time'])) . ' - ' . date('H:i', strtotime($slot['end_time'])); ?>
                                        </div>
                                        <div class="slot-day">
                                            <?php echo htmlspecialchars($dayData['day_name']); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- Campos adicionales para la reserva -->
                <div class="row mt-4" id="additionalFields" style="display: none;">
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slotCards = document.querySelectorAll('.slot-card');
            const submitBtn = document.getElementById('submitBtn');
            const selectedAvailabilityId = document.getElementById('selectedAvailabilityId');
            const selectedClassDate = document.getElementById('selectedClassDate');
            const selectedClassTime = document.getElementById('selectedClassTime');
            const additionalFields = document.getElementById('additionalFields');
            const form = document.getElementById('reservationForm');
            let selectedCard = null;

            slotCards.forEach(card => {
                card.addEventListener('click', function() {
                    // Remover selección anterior
                    if (selectedCard) {
                        selectedCard.classList.remove('selected');
                    }

                    // Seleccionar nueva tarjeta
                    selectedCard = this;
                    this.classList.add('selected');

                    // Llenar campos hidden
                    const availabilityId = this.dataset.availabilityId;
                    const classDate = this.dataset.date;
                    const startTime = this.dataset.startTime;

                    selectedAvailabilityId.value = availabilityId;
                    selectedClassDate.value = classDate;
                    selectedClassTime.value = startTime;

                    // Mostrar campos adicionales y habilitar botón
                    additionalFields.style.display = 'block';
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '✅ Confirmar Reserva - ' + this.querySelector('.slot-time').textContent.trim();

                    console.log('Seleccionado:', {
                        availabilityId: availabilityId,
                        classDate: classDate,
                        startTime: startTime
                    });
                });

                // Mejorar accesibilidad
                const timeText = card.querySelector('.slot-time').textContent.trim();
                const dayText = card.querySelector('.slot-day').textContent.trim();
                card.setAttribute('aria-label', `Seleccionar horario ${timeText} del ${dayText}`);
                card.setAttribute('role', 'button');
                card.setAttribute('tabindex', '0');
            });

            // Debug del envío del formulario
            form.addEventListener('submit', function(e) {
                console.log('Enviando formulario con:', {
                    availability_id: selectedAvailabilityId.value,
                    class_date: selectedClassDate.value,
                    class_time: selectedClassTime.value,
                    notes: document.getElementById('notes').value
                });

                // Validar que los datos están presentes
                if (!selectedAvailabilityId.value || !selectedClassDate.value) {
                    e.preventDefault();
                    alert('Por favor selecciona un horario antes de continuar');
                    return false;
                }

                // Mostrar loading
                submitBtn.innerHTML = '⏳ Procesando...';
                submitBtn.disabled = true;
            });
        });
    </script>
</body>
</html>