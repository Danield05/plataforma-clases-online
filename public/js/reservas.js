function openRescheduleModal(reservationId, estudianteName, currentDate) {
    document.getElementById('reservationId').value = reservationId;
    document.getElementById('estudianteInfo').value = estudianteName;
    document.getElementById('newDate').value = '';

    // Limpiar opciones de horario
    const availabilitySelect = document.getElementById('newAvailability');
    availabilitySelect.innerHTML = '<option value="">Primero selecciona una fecha...</option>';

    // Mostrar días de trabajo al abrir el modal
    const diasTrabajoDiv = document.getElementById('diasTrabajo');
    diasTrabajoDiv.innerHTML = '<small>Cargando días de trabajo...</small>';

    // Obtener horario de trabajo del profesor
    fetch('/plataforma-clases-online/home/get_available_slots_profesor?fecha=' + new Date().toISOString().split('T')[0])
        .then(response => response.json())
        .then(data => {
            if (data.success && data.horarios_trabajo) {
                const horariosHtml = data.horarios_trabajo.length > 0
                    ? '<small>' + data.horarios_trabajo.join('<br>') + '</small>'
                    : '<small>No tienes horario de trabajo configurado</small>';
                diasTrabajoDiv.innerHTML = horariosHtml;
            } else {
                diasTrabajoDiv.innerHTML = '<small>Error al cargar horario de trabajo</small>';
            }
        })
        .catch(error => {
            console.error('Error al cargar horario de trabajo:', error);
            diasTrabajoDiv.innerHTML = '<small>Error al cargar horario de trabajo</small>';
        });

    const modal = new bootstrap.Modal(document.getElementById('rescheduleModal'));
    modal.show();
}

// Cargar horarios disponibles cuando se selecciona una fecha
document.getElementById('newDate').addEventListener('change', function() {
    const selectedDate = this.value;
    const availabilitySelect = document.getElementById('newAvailability');

    if (selectedDate) {
        // Hacer llamada AJAX para obtener los horarios disponibles del profesor
        fetch(`/plataforma-clases-online/home/get_available_slots_profesor?fecha=${selectedDate}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    availabilitySelect.innerHTML = '<option value="">Seleccionar horario...</option>';
                    data.slots.forEach(slot => {
                        const option = document.createElement('option');
                        option.value = slot.availability_id;
                        option.textContent = `${slot.start_time} - ${slot.end_time} (${slot.day})`;
                        availabilitySelect.appendChild(option);
                    });
                } else {
                    availabilitySelect.innerHTML = '<option value="">No hay horarios disponibles</option>';
                }
            })
            .catch(error => {
                console.error('Error al cargar horarios:', error);
                availabilitySelect.innerHTML = '<option value="">Error al cargar horarios</option>';
            });
    } else {
        availabilitySelect.innerHTML = '<option value="">Seleccionar horario...</option>';
    }
});

// Debug: Agregar información de sesión en consola (comentado para evitar errores en JS puro)
// console.log('Debug sesión:', {
//     user_id: 'no definido',
//     role: 'no definido',
//     session_exists: false
// });