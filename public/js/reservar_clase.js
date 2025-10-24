// JavaScript para la página de reserva de clases
document.addEventListener('DOMContentLoaded', function() {
    const submitBtn = document.getElementById('submitBtn');
    const selectedAvailabilityId = document.getElementById('selectedAvailabilityId');
    const selectedClassDate = document.getElementById('selectedClassDate');
    const selectedClassTime = document.getElementById('selectedClassTime');
    const form = document.getElementById('reservationForm');
    const timeSlotsCard = document.getElementById('time-slots');
    const selectedDateDisplay = document.getElementById('selected-date-display');
    const slotsContainer = document.getElementById('slots-container');

    // Datos de slots disponibles desde PHP (se pasarán desde el HTML)
    const availableSlots = window.availableSlots || {};

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
                        <div class="slot-duration">
                            <small class="text-muted">2 horas de clase</small>
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