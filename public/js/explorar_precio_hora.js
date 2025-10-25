// JavaScript específico para la página de explorar precio por hora
document.addEventListener('DOMContentLoaded', function() {
    // Función para manejar el envío del formulario de búsqueda
    const searchForm = document.querySelector('form[method="GET"]');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const searchInput = this.querySelector('input[name="search"]');
            if (searchInput && searchInput.value.trim() === '') {
                e.preventDefault();
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

    // Aplicar observer a todas las tarjetas de rangos de precio
    const precioCards = document.querySelectorAll('.materia-card');
    precioCards.forEach((card, index) => {
        // Establecer estado inicial para animación
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;

        observer.observe(card);
    });

    // Función para filtrar tarjetas en tiempo real (opcional)
    const searchInput = document.querySelector('.materia-search-input');
    if (searchInput) {
        let typingTimer;
        searchInput.addEventListener('input', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                console.log('Búsqueda de precio:', this.value);
            }, 300);
        });
    }

    // Función para mejorar la accesibilidad
    function improveAccessibility() {
        // Agregar aria-labels a los botones si no los tienen
        const buttons = document.querySelectorAll('.materia-card .btn');
        buttons.forEach(button => {
            if (!button.getAttribute('aria-label')) {
                const rangoName = button.closest('.materia-card').querySelector('.card-title').textContent.replace('💰 ', '');
                button.setAttribute('aria-label', `Ver profesores en rango ${rangoName}`);
            }
        });

        // Agregar role="search" al contenedor de búsqueda
        const searchContainer = document.querySelector('.materia-search-container');
        if (searchContainer) {
            searchContainer.setAttribute('role', 'search');
        }

        // Agregar aria-label al botón de volver
        const backButton = document.querySelector('.btn[href*="explorar_profesores"]');
        if (backButton) {
            backButton.setAttribute('aria-label', 'Volver a la página de profesores');
        }
    }

    improveAccessibility();

    // Función para mostrar contador de profesores en tiempo real
    function updateProfesorCounts() {
        const profesorCountElements = document.querySelectorAll('.materia-card .card-text strong');
        profesorCountElements.forEach(element => {
            if (element.textContent.includes('👨‍🏫 Profesores:')) {
                const count = element.nextSibling.textContent.trim();
                if (parseInt(count) === 0) {
                    element.closest('.materia-card').style.opacity = '0.7';
                    element.closest('.materia-card').title = 'No hay profesores disponibles para este rango de precio';
                }
            }
        });
    }

    updateProfesorCounts();
});