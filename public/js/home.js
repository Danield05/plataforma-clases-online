// Archivo JavaScript para la página home
// Animación de conteo para las estadísticas
document.addEventListener('DOMContentLoaded', function() {
    // Función para animar números
    function animateNumber(element, target, duration = 2000) {
        const start = 0;
        const increment = target / (duration / 16);
        let current = start;

        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current);
        }, 16);
    }

    // Animar estadísticas cuando sean visibles
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const statValue = entry.target;
                const target = parseInt(statValue.getAttribute('data-target'));
                animateNumber(statValue, target);
                observer.unobserve(statValue);
            }
        });
    });

    // Aplicar animación a todas las estadísticas
    document.querySelectorAll('.stat-value').forEach(stat => {
        const target = parseInt(stat.textContent);
        if (target > 0) {
            stat.setAttribute('data-target', target);
            stat.textContent = '0';
            observer.observe(stat);
        }
    });

    // Efecto hover en tarjetas de estadísticas
    document.querySelectorAll('.modern-stat-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) scale(1.02)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // Actualizar fecha y hora en tiempo real
    function updateDateTime() {
        const now = new Date();
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            timeZone: 'America/El_Salvador'
        };

        // Si hay algún elemento que muestre la fecha, actualizarlo
        const dateElements = document.querySelectorAll('.current-date');
        dateElements.forEach(el => {
            el.textContent = now.toLocaleDateString('es-SV', options);
        });
    }

    // Actualizar cada minuto
    updateDateTime();
    setInterval(updateDateTime, 60000);
});