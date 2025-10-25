// JavaScript básico para el proyecto MVC
document.addEventListener('DOMContentLoaded', function() {
    console.log('Página cargada correctamente.');

    // Función para actualizar la hora en tiempo real del cliente
    function updateClock() {
        const now = new Date();

        // Mostrar la hora local del navegador del usuario
        const timeString = now.toLocaleTimeString('es-ES', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        });

        const clockElement = document.getElementById('current-time');
        if (clockElement) {
            clockElement.textContent = timeString;
        }
    }

    // Actualizar la hora inmediatamente y luego cada minuto
    updateClock();
    setInterval(updateClock, 60000); // Actualizar cada 60 segundos

    // Función para manejar dropdowns (mantener funcionalidad existente)
    function toggleDropdown() {
        const dropdown = document.getElementById('navDropdown');
        if (dropdown) {
            dropdown.classList.toggle('show');
        }
    }

    // Hacer la función global para que sea accesible desde HTML
    window.toggleDropdown = toggleDropdown;

    // Ejemplo: Agregar funcionalidad a los enlaces
    const links = document.querySelectorAll('nav a');
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            // Aquí puedes agregar lógica, como confirmaciones o animaciones
            console.log('Navegando a: ' + this.href);
        });
    });
});