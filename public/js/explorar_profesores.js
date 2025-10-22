// JavaScript específico para la página de explorar profesores
document.addEventListener('DOMContentLoaded', function() {
    // Función para manejar el envío del formulario de búsqueda
    const searchForm = document.querySelector('form[method="GET"]');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const searchInput = this.querySelector('input[name="search"]');
            if (searchInput && searchInput.value.trim() === '') {
                e.preventDefault();
                // Mostrar mensaje o simplemente no hacer nada
                searchInput.focus();
            }
        });
    }

    // Función para animar las tarjetas al hacer scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Aplicar observer a todas las tarjetas de profesores
    const profesorCards = document.querySelectorAll('.profesor-card');
    profesorCards.forEach((card, index) => {
        // Establecer estado inicial para animación
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;

        observer.observe(card);
    });

    // Función para filtrar tarjetas en tiempo real (opcional)
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        let typingTimer;
        searchInput.addEventListener('input', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                // Aquí podrías implementar filtrado en tiempo real si fuera necesario
                console.log('Búsqueda:', this.value);
            }, 300);
        });
    }

    // Función para mejorar la accesibilidad
    function improveAccessibility() {
        // Agregar aria-labels a los botones si no los tienen
        const buttons = document.querySelectorAll('.profesor-card .btn');
        buttons.forEach(button => {
            if (!button.getAttribute('aria-label')) {
                const profesorName = button.closest('.profesor-card').querySelector('.card-title').textContent.replace('👨‍🏫 ', '');
                button.setAttribute('aria-label', `Reservar clase con ${profesorName}`);
            }
        });

        // Agregar role="search" al contenedor de búsqueda
        const searchContainer = document.querySelector('.search-container');
        if (searchContainer) {
            searchContainer.setAttribute('role', 'search');
        }
    }

    improveAccessibility();
});