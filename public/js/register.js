// Archivo JavaScript para la página register
function toggleFields() {
    const role = document.getElementById('id_role').value;
    const profesorFields = document.getElementById('profesor-fields');
    const descriptionField = document.getElementById('description-field');

    if (role === '2') { // Profesor
        profesorFields.style.display = 'block';
        descriptionField.style.display = 'block';
    } else if (role === '3') { // Estudiante
        profesorFields.style.display = 'none';
        descriptionField.style.display = 'block';
    } else {
        profesorFields.style.display = 'none';
        descriptionField.style.display = 'none';
    }
}

// Auto-ocultar la notificación de error después de 5 segundos
document.addEventListener('DOMContentLoaded', function () {
    const errorAlert = document.getElementById('errorAlert');
    if (errorAlert) {
        setTimeout(function () {
            const bsAlert = new bootstrap.Alert(errorAlert);
            bsAlert.close();
        }, 5000); // 5 segundos
    }
});