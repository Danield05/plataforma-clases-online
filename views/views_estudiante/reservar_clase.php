<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📅 Reservar Clase - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/explorar_profesores.css?v=<?php echo time(); ?>">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
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
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            transform: scale(1.02);
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
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
        /* Estilos personalizados para FullCalendar */
        #calendar {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            overflow: hidden;
            border: 1px solid #e9ecef;
        }

        .fc {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .fc-header-toolbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            margin-bottom: 0 !important;
        }

        .fc-toolbar-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: white !important;
        }

        .fc-button {
            background: rgba(255,255,255,0.2) !important;
            border: 1px solid rgba(255,255,255,0.3) !important;
            color: white !important;
            border-radius: 6px !important;
            font-weight: 500 !important;
            transition: all 0.3s ease !important;
        }

        .fc-button:hover {
            background: rgba(255,255,255,0.3) !important;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .fc-button-active {
            background: white !important;
            color: #667eea !important;
            font-weight: 600 !important;
        }

        .fc-daygrid-day {
            transition: all 0.3s ease;
        }

        .fc-daygrid-day:hover {
            background-color: #f8f9fa !important;
            transform: scale(1.02);
        }

        .fc-daygrid-day:has(.available-day) {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%) !important;
            border: 2px solid #28a745 !important;
            cursor: pointer;
            position: relative;
        }

        .fc-daygrid-day:has(.available-day):hover {
            background: linear-gradient(135deg, #c3e6cb 0%, #a8d5ba 100%) !important;
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        .fc-daygrid-day:has(.available-day)::before {
            content: "✓";
            position: absolute;
            top: 5px;
            right: 5px;
            background: #28a745;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: bold;
            z-index: 10;
        }

        .available-day {
            cursor: pointer !important;
            font-weight: 600 !important;
            color: #155724 !important;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .available-day:hover {
            color: #0f5132 !important;
        }

        .fc-day-today {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%) !important;
            border: 2px solid #ffc107 !important;
        }

        .fc-daygrid-day-number {
            font-weight: 600;
            color: #495057;
        }

        .fc-col-header {
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        .fc-col-header-cell {
            font-weight: 600;
            color: #495057;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        /* Estilos para el panel lateral */
        #time-slots .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        #time-slots .card-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            border-bottom: none;
        }

        /* Animaciones */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #time-slots {
            animation: fadeInUp 0.5s ease-out;
        }

        .slot-card {
            animation: fadeInUp 0.3s ease-out;
            animation-fill-mode: both;
        }

        .slot-card:nth-child(1) { animation-delay: 0.1s; }
        .slot-card:nth-child(2) { animation-delay: 0.2s; }
        .slot-card:nth-child(3) { animation-delay: 0.3s; }
        .slot-card:nth-child(4) { animation-delay: 0.4s; }
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
        document.addEventListener('DOMContentLoaded', function() {
            const submitBtn = document.getElementById('submitBtn');
            const selectedAvailabilityId = document.getElementById('selectedAvailabilityId');
            const selectedClassDate = document.getElementById('selectedClassDate');
            const selectedClassTime = document.getElementById('selectedClassTime');
            const form = document.getElementById('reservationForm');
            const timeSlotsCard = document.getElementById('time-slots');
            const selectedDateDisplay = document.getElementById('selected-date-display');
            const slotsContainer = document.getElementById('slots-container');

            // Datos de slots disponibles desde PHP
            const availableSlots = <?php echo json_encode($available_slots); ?>;

            // Inicializar FullCalendar
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana'
                },
                dayMaxEvents: true,
                height: 'auto',
                contentHeight: 600,
                aspectRatio: 1.5,
                eventDisplay: 'block',
                eventMouseEnter: function(info) {
                    info.el.style.transform = 'scale(1.05)';
                    info.el.style.transition = 'transform 0.2s ease';
                },
                eventMouseLeave: function(info) {
                    info.el.style.transform = 'scale(1)';
                },
                events: function(fetchInfo, successCallback, failureCallback) {
                    // Crear eventos para el calendario basados en slots disponibles
                    const events = [];

                    console.log('Available slots:', availableSlots);

                    Object.keys(availableSlots).forEach(date => {
                        const dayData = availableSlots[date];
                        if (dayData.slots && dayData.slots.length > 0) {
                            events.push({
                                title: `${dayData.slots.length} horario(s) disponible(s)`,
                                start: date,
                                allDay: true,
                                backgroundColor: '#28a745',
                                borderColor: '#28a745',
                                className: 'available-day',
                                extendedProps: {
                                    date: date,
                                    slots: dayData.slots,
                                    dayName: dayData.day_name,
                                    dateDisplay: dayData.date_display
                                }
                            });
                        }
                    });

                    console.log('Calendar events:', events);
                    successCallback(events);
                },
                dateClick: function(info) {
                    console.log('Date clicked:', info.dateStr);
                    // Cuando se hace clic en una fecha, mostrar los slots disponibles
                    const dateStr = info.dateStr;
                    const dayData = availableSlots[dateStr];

                    console.log('Day data for', dateStr, ':', dayData);

                    if (dayData && dayData.slots && dayData.slots.length > 0) {
                        selectedDateDisplay.innerHTML = `<strong>${dayData.day_name} ${dayData.date_display}</strong>`;
                        slotsContainer.innerHTML = '';

                        dayData.slots.forEach(slot => {
                            console.log('Creating slot:', slot);
                            const slotDiv = document.createElement('div');
                            slotDiv.className = 'slot-card mb-2';
                            slotDiv.setAttribute('data-availability-id', slot.availability_id);
                            slotDiv.setAttribute('data-date', dateStr);
                            slotDiv.setAttribute('data-start-time', slot.start_time);

                            slotDiv.innerHTML = `
                                <div class="slot-time">
                                    🕒 ${new Date('1970-01-01T' + slot.start_time).toLocaleTimeString('es-ES', {hour: '2-digit', minute: '2-digit'})} -
                                    ${new Date('1970-01-01T' + slot.end_time).toLocaleTimeString('es-ES', {hour: '2-digit', minute: '2-digit'})}
                                </div>
                                <div class="slot-duration">
                                    <small class="text-muted">2 horas de clase</small>
                                </div>
                            `;

                            slotDiv.addEventListener('click', function() {
                                console.log('Slot clicked:', slot);
                                // Remover selección anterior
                                document.querySelectorAll('.slot-card').forEach(card => {
                                    card.classList.remove('selected');
                                });

                                // Seleccionar nueva
                                this.classList.add('selected');

                                // Llenar campos del formulario
                                selectedAvailabilityId.value = slot.availability_id;
                                selectedClassDate.value = dateStr;
                                selectedClassTime.value = slot.start_time;

                                // Mostrar formulario y habilitar botón
                                form.style.display = 'block';
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = `✅ Confirmar Reserva - ${this.querySelector('.slot-time').textContent.trim()}`;

                                // Scroll al formulario
                                form.scrollIntoView({ behavior: 'smooth' });
                            });

                            slotsContainer.appendChild(slotDiv);
                        });

                        timeSlotsCard.style.display = 'block';
                    } else {
                        console.log('No slots available for', dateStr);
                        selectedDateDisplay.innerHTML = `<strong>No hay horarios disponibles para esta fecha</strong>`;
                        slotsContainer.innerHTML = '<p class="text-muted">Selecciona una fecha con indicador verde.</p>';
                        timeSlotsCard.style.display = 'block';
                    }
                },
                eventClick: function(info) {
                    console.log('Event clicked:', info.event);
                    // También permitir hacer clic en los eventos del calendario
                    const dateStr = info.event.startStr;
                    const dayData = availableSlots[dateStr];

                    if (dayData && dayData.slots && dayData.slots.length > 0) {
                        selectedDateDisplay.innerHTML = `<strong>${dayData.day_name} ${dayData.date_display}</strong>`;
                        slotsContainer.innerHTML = '';

                        dayData.slots.forEach(slot => {
                            const slotDiv = document.createElement('div');
                            slotDiv.className = 'slot-card mb-2';
                            slotDiv.setAttribute('data-availability-id', slot.availability_id);
                            slotDiv.setAttribute('data-date', dateStr);
                            slotDiv.setAttribute('data-start-time', slot.start_time);

                            slotDiv.innerHTML = `
                                <div class="slot-time">
                                    🕒 ${new Date('1970-01-01T' + slot.start_time).toLocaleTimeString('es-ES', {hour: '2-digit', minute: '2-digit'})} -
                                    ${new Date('1970-01-01T' + slot.end_time).toLocaleTimeString('es-ES', {hour: '2-digit', minute: '2-digit'})}
                                </div>
                            `;

                            slotDiv.addEventListener('click', function() {
                                // Remover selección anterior
                                document.querySelectorAll('.slot-card').forEach(card => {
                                    card.classList.remove('selected');
                                });

                                // Seleccionar nueva
                                this.classList.add('selected');

                                // Llenar campos del formulario
                                selectedAvailabilityId.value = slot.availability_id;
                                selectedClassDate.value = dateStr;
                                selectedClassTime.value = slot.start_time;

                                // Mostrar formulario y habilitar botón
                                form.style.display = 'block';
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = `✅ Confirmar Reserva - ${this.querySelector('.slot-time').textContent.trim()}`;

                                // Scroll al formulario
                                form.scrollIntoView({ behavior: 'smooth' });
                            });

                            slotsContainer.appendChild(slotDiv);
                        });

                        timeSlotsCard.style.display = 'block';
                    }
                }
            });

            calendar.render();

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