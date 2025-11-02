let mesActualProfesor = new Date().getMonth();
let anioActualProfesor = new Date().getFullYear();

// Los datos se cargarán dinámicamente desde el controlador

// Función para inicializar el calendario de reservas del profesor
async function inicializarCalendarioClasesProfesor() {
    actualizarTituloCalendarioProfesor();
    await generarCalendarioProfesor(mesActualProfesor, anioActualProfesor);
    await cargarProximasClasesProfesor();
}

async function cambiarMesProfesor(direccion) {
    mesActualProfesor += direccion;

    if (mesActualProfesor < 0) {
        mesActualProfesor = 11;
        anioActualProfesor--;
    } else if (mesActualProfesor > 11) {
        mesActualProfesor = 0;
        anioActualProfesor++;
    }

    await generarCalendarioProfesor(mesActualProfesor, anioActualProfesor);
    actualizarTituloCalendarioProfesor();
}

function actualizarTituloCalendarioProfesor() {
    const nombresMeses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                         'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    document.getElementById('mesAnioActualProfesor').textContent = `${nombresMeses[mesActualProfesor]} ${anioActualProfesor}`;
}

async function generarCalendarioProfesor(mes, anio) {
    const calendarioContainer = document.getElementById('calendarioClases');
    const nombresMeses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                          'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

    const primerDia = new Date(anio, mes, 1);
    const ultimoDia = new Date(anio, mes + 1, 0);
    const diasEnMes = ultimoDia.getDate();
    const diaSemanaInicio = primerDia.getDay();

    // Preparar datos de reservas para el mes actual
    const reservasMes = await prepararDatosReservasProfesor(mes, anio);

    let html = `
        <div class="calendar-container">
            <table class="table table-bordered calendar-table">
                <thead class="table-light">
                    <tr>
                        <th class="text-center calendar-day">Dom</th>
                        <th class="text-center calendar-day">Lun</th>
                        <th class="text-center calendar-day">Mar</th>
                        <th class="text-center calendar-day">Mié</th>
                        <th class="text-center calendar-day">Jue</th>
                        <th class="text-center calendar-day">Vie</th>
                        <th class="text-center calendar-day">Sáb</th>
                    </tr>
                </thead>
                <tbody>
    `;

    let diaActual = 1;
    const hoy = new Date();
    const esMesActual = (anio === hoy.getFullYear() && mes === hoy.getMonth());

    for (let semana = 0; semana < 6; semana++) {
        html += '<tr>';
        for (let diaSemana = 0; diaSemana < 7; diaSemana++) {
            if ((semana === 0 && diaSemana < diaSemanaInicio) || diaActual > diasEnMes) {
                html += '<td class="calendar-cell calendar-empty"></td>';
            } else {
                const fechaActual = `${anio}-${String(mes + 1).padStart(2, '0')}-${String(diaActual).padStart(2, '0')}`;
                const esHoy = esMesActual && diaActual === hoy.getDate();
                const reservasDelDia = reservasMes[fechaActual] || [];

                const hoyClass = esHoy ? 'calendar-today' : '';

                html += `
                    <td class="calendar-cell ${hoyClass}" data-fecha="${fechaActual}">
                        <div class="calendar-day-number">${diaActual}</div>
                        <div class="calendar-reservations">
                `;

                // Mostrar hasta 2 reservas por día
                const reservasMostrar = reservasDelDia.slice(0, 2);
                reservasMostrar.forEach(reserva => {
                    const estadoClass = obtenerClaseEstadoProfesor(reserva.reservation_status);
                    html += `
                        <div class="calendar-reservation ${estadoClass}" title="${reserva.estudiante_name} - ${reserva.start_time || 'N/A'}">
                            <small>${reserva.start_time ? reserva.start_time.substring(0, 5) : 'N/A'}</small>
                        </div>
                    `;
                });

                // Si hay más de 2 reservas, mostrar indicador
                if (reservasDelDia.length > 2) {
                    html += `
                        <div class="calendar-more" title="${reservasDelDia.length - 2} más reservas">
                            <small>+${reservasDelDia.length - 2}</small>
                        </div>
                    `;
                }

                html += `
                        </div>
                    </td>
                `;
                diaActual++;
            }
        }
        html += '</tr>';
        if (diaActual > diasEnMes) break;
    }

    html += `
                </tbody>
            </table>
        </div>
    `;

    calendarioContainer.innerHTML = html;

    // Agregar eventos de clic a las celdas con reservas
    document.querySelectorAll('.calendar-cell[data-fecha]').forEach(celda => {
        if (celda.querySelector('.calendar-reservations').children.length > 0) {
            celda.style.cursor = 'pointer';
            celda.addEventListener('click', function() {
                mostrarDetalleDiaProfesor(this.dataset.fecha);
            });
        }
    });
}

async function prepararDatosReservasProfesor(mes, anio) {
    try {
        const response = await fetch('/plataforma-clases-online/home/calendario');
        const data = await response.json();

        if (data.success) {
            const reservasData = data.reservas;
            const reservasMes = {};
            const mesActual = mes + 1; // JavaScript usa meses 0-11, necesitamos 1-12

            reservasData.forEach(reserva => {
                if (!reserva.fecha) return;
                const fechaReserva = new Date(reserva.fecha + 'T00:00:00');
                const coincide = fechaReserva.getMonth() + 1 === mesActual && fechaReserva.getFullYear() === anio;
                if (coincide) {
                    if (!reservasMes[reserva.fecha]) {
                        reservasMes[reserva.fecha] = [];
                    }
                    reservasMes[reserva.fecha].push(reserva);
                }
            });

            return reservasMes;
        } else {
            throw new Error('Error al obtener datos del calendario');
        }
    } catch (error) {
        console.error('Error:', error);
        return {};
    }
}

function obtenerClaseEstadoProfesor(estado) {
    switch(estado.toLowerCase()) {
        case 'confirmada': return 'reservation-confirmed';
        case 'pendiente': return 'reservation-pending';
        case 'completada': return 'reservation-completed';
        case 'cancelada': return 'reservation-cancelled';
        default: return 'reservation-default';
    }
}

async function cargarProximasClasesProfesor() {
    try {
        const response = await fetch('/plataforma-clases-online/home/calendario');
        const data = await response.json();

        if (data.success) {
            const reservasData = data.reservas;
            const hoy = new Date();
            const mesActual = hoy.getMonth() + 1;
            const anioActual = hoy.getFullYear();

            const reservasProximas = reservasData.filter(reserva => {
                if (!reserva.fecha) return false;
                const fechaReserva = new Date(reserva.fecha + 'T00:00:00');
                return fechaReserva >= hoy &&
                       fechaReserva.getMonth() + 1 === mesActual &&
                       fechaReserva.getFullYear() === anioActual;
            });

            reservasProximas.sort((a, b) => new Date(a.fecha + 'T' + a.start_time) - new Date(b.fecha + 'T' + b.start_time));

            const proximasReservas = reservasProximas.slice(0, 3);

            const container = document.getElementById('proximasClasesContainerProfesor');
            container.innerHTML = '';

            proximasReservas.forEach(reserva => {
                const card = document.createElement('div');
                card.className = 'col-md-4';
                card.innerHTML = `
                    <div class="next-class-card">
                        <div class="class-date">
                            ${reserva.fecha_display}
                        </div>
                        <div class="class-time">
                            ${reserva.start_time} - ${reserva.end_time}
                        </div>
                        <div class="class-teacher">
                            👨‍🎓 ${reserva.estudiante_name}
                        </div>
                        <div class="class-status">
                            <span class="badge bg-${reserva.reservation_status === 'confirmada' ? 'success' : (reserva.reservation_status === 'pendiente' ? 'warning' : (reserva.reservation_status === 'completada' ? 'info' : 'secondary'))}">
                                ${reserva.reservation_status.charAt(0).toUpperCase() + reserva.reservation_status.slice(1)}
                            </span>
                        </div>
                        ${(reserva.reservation_status === 'pendiente' || reserva.reservation_status === 'confirmada') ?
                            `<div class="class-actions mt-2">
                                <button class="btn btn-outline-danger btn-sm w-100" onclick="cancelarClaseProfesor('${reserva.reservation_id}')">Cancelar</button>
                            </div>`
                            : ''
                        }
                    </div>
                `;
                container.appendChild(card);
            });
        } else {
            throw new Error('Error al obtener datos del calendario');
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function mostrarDetalleDiaProfesor(fecha) {
    try {
        const response = await fetch('/plataforma-clases-online/home/calendario');
        const data = await response.json();

        if (data.success) {
            const reservasData = data.reservas;
            const reservasDia = reservasData.filter(r => r.fecha === fecha);

            if (reservasDia.length === 0) {
                mostrarModalVacioProfesor(fecha);
                return;
            }

            let detalle = `
                <div class="day-detail-header">
                    <h5>📅 ${new Date(fecha + 'T00:00:00').toLocaleDateString('es-ES', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    })}</h5>
                </div>
                <div class="day-reservations">
            `;

            reservasDia.forEach((reserva, index) => {
                const horaInicio = reserva.start_time !== 'N/A' ? reserva.start_time : '08:00';
                const horaFin = reserva.end_time !== 'N/A' ? reserva.end_time : '10:00';

                detalle += `
                    <div class="reservation-detail-card">
                        <div class="reservation-header">
                            <h6 class="reservation-teacher">👨‍🎓 ${reserva.estudiante_name}</h6>
                            <span class="badge bg-${obtenerClaseEstadoProfesor(reserva.reservation_status).replace('reservation-', '')}">
                                ${reserva.reservation_status.charAt(0).toUpperCase() + reserva.reservation_status.slice(1)}
                            </span>
                        </div>
                        <div class="reservation-time">
                            🕐 ${horaInicio} - ${horaFin}
                        </div>
                        ${reserva.notes ? `<div class="reservation-notes">📝 ${reserva.notes}</div>` : ''}
                        ${reserva.academic_level ? `<div class="reservation-level">🎓 ${reserva.academic_level}</div>` : ''}
                        ${reserva.hourly_rate ? `<div class="reservation-rate">💰 $${reserva.hourly_rate}/hora</div>` : ''}
                        <div class="reservation-link">
                            ${reserva.meeting_link ? `🔗 Enlace de las reuniones: <a href="${reserva.meeting_link}" target="_blank" class="btn btn-primary btn-sm">Ir a la Reunión</a>` : '🔗 Enlace no disponible'}
                        </div>
                        <div class="reservation-actions">
                            ${(reserva.reservation_status === 'pendiente' || reserva.reservation_status === 'confirmada') ?
                                `<button class="btn btn-outline-danger btn-sm" onclick="cancelarClaseProfesor('${reserva.reservation_id}')">Cancelar Clase</button>`
                                : ''
                            }
                        </div>
                    </div>
                `;
            });

            detalle += `</div>`;

            // Crear y mostrar modal
            const modal = document.createElement('div');
            modal.className = 'modal fade show';
            modal.innerHTML = `
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Detalles del Día</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            ${detalle}
                        </div>
                    </div>
                </div>
            `;

            modal.style.display = 'block';
            modal.style.backgroundColor = 'rgba(0,0,0,0.5)';
            document.body.appendChild(modal);

            // Cerrar modal al hacer clic fuera o en el botón de cerrar
            modal.addEventListener('click', function(e) {
                if (e.target === modal || e.target.classList.contains('btn-close')) {
                    document.body.removeChild(modal);
                }
            });
        } else {
            throw new Error('Error al obtener datos del calendario');
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

function mostrarModalVacioProfesor(fecha) {
    const modal = document.createElement('div');
    modal.className = 'modal fade show';
    modal.innerHTML = `
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sin Reservas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <p>No tienes reservas programadas para ${new Date(fecha).toLocaleDateString('es-ES')}.</p>
                    <a href="/plataforma-clases-online/home/disponibilidad_create" class="btn btn-primary">Configurar Disponibilidad</a>
                </div>
            </div>
        </div>
    `;

    modal.style.display = 'block';
    modal.style.backgroundColor = 'rgba(0,0,0,0.5)';
    document.body.appendChild(modal);

    modal.addEventListener('click', function(e) {
        if (e.target === modal || e.target.classList.contains('btn-close')) {
            document.body.removeChild(modal);
        }
    });
}

function cancelarClaseProfesor(reservationId) {
    if (confirm('¿Estás seguro de que quieres cancelar esta clase?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/plataforma-clases-online/home/cancelar_reserva';

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'reservation_id';
        input.value = reservationId;

        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}

// Inicializar calendario cuando se carga la página
document.addEventListener('DOMContentLoaded', function() {
    inicializarCalendarioClasesProfesor();
});