// Archivo JavaScript para la página reportes
// Gráfico de clases por mes
const clasesCtx = document.getElementById('clasesChart');
if (clasesCtx) {
    const clasesChart = clasesCtx.getContext('2d');
    new Chart(clasesChart, {
        type: 'line',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
            datasets: [{
                label: 'Clases',
                data: [12, 19, 15, 25, 22, 30],
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        }
    });
}

// Gráfico de ingresos por mes
const ingresosCtx = document.getElementById('ingresosChart');
if (ingresosCtx) {
    const ingresosChart = ingresosCtx.getContext('2d');
    new Chart(ingresosChart, {
        type: 'bar',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
            datasets: [{
                label: 'Ingresos ($)',
                data: [1200, 1900, 1500, 2500, 2200, 3000],
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        }
    });
}

// Función para exportar reportes
function exportarReporte(tipo) {
    // Obtener filtros del formulario
    const fechaInicio = document.getElementById('fecha_inicio').value;
    const fechaFin = document.getElementById('fecha_fin').value;
    const tipoReporte = document.getElementById('tipo_reporte').value;

    // Construir URL con parámetros - usar el controlador de reportes
    let url = `/plataforma-clases-online/reportes/exportar?tipo=${tipo}&tipo_reporte=${tipoReporte}`;

    if (fechaInicio) url += `&fecha_inicio=${fechaInicio}`;
    if (fechaFin) url += `&fecha_fin=${fechaFin}`;

    switch(tipo) {
        case 'pdf':
            // Abrir en nueva ventana para ver y poder imprimir/guardar como PDF
            window.open(url, '_blank');
            break;
        case 'excel':
        case 'csv':
            // Descargar archivo
            const link = document.createElement('a');
            link.href = url;
            link.download = '';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            break;
        case 'email':
            // Enviar por email usando AJAX
            enviarPorEmail(url);
            break;
    }
}

// Función para enviar reporte por email
function enviarPorEmail(url) {
    if (confirm('¿Desea enviar el reporte por email?')) {
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Reporte enviado exitosamente por email');
            } else {
                alert('Error al enviar el reporte: ' + (data.message || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al enviar el reporte por email');
        });
    }
}