// Archivo JavaScript para la navegación nav
function updateClock() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('es-ES', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false
    });

    const clockElement = document.getElementById('current-time');
    if (clockElement) {
        clockElement.textContent = timeString;
    }
}

// Actualizar el reloj inmediatamente al cargar la página
updateClock();

// Actualizar el reloj cada segundo
setInterval(updateClock, 1000);