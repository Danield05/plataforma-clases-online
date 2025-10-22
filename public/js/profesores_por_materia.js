// JavaScript específico para la página de profesores por materia con funcionalidad de búsqueda
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const profesorCards = document.querySelectorAll('.profesor-card');

    // Función para remover tildes y normalizar texto
    function normalizeText(text) {
        return text.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();
    }

    // Función para filtrar profesores por nombre
    function filterProfesores() {
        const searchTerm = normalizeText(searchInput.value.trim());
        console.log('Término de búsqueda normalizado:', searchTerm);

        let visibleCount = 0;
        profesorCards.forEach(card => {
            const cardTitle = card.querySelector('.card-title').textContent;
            const fullName = cardTitle.replace('👨‍🏫 ', ''); // Remover el emoji
            const normalizedFullName = normalizeText(fullName);
            console.log('Nombre completo normalizado:', normalizedFullName);

            // Buscar si el término está en el nombre completo o en partes (nombre o apellido)
            if (normalizedFullName.includes(searchTerm) ||
                normalizeText(fullName.split(' ')[0]).includes(searchTerm) || // Nombre
                normalizeText(fullName.split(' ')[1]).includes(searchTerm)) { // Apellido
                card.style.display = 'block';
                // Animar la aparición
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        console.log('Número de profesores visibles:', visibleCount);
        // Mostrar mensaje si no hay resultados
        updateNoResultsMessage();
    }

    // Función para limpiar la búsqueda
    function clearSearch() {
        searchInput.value = '';
        profesorCards.forEach(card => {
            card.style.display = 'block';
            // Restaurar animación
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        });
        updateNoResultsMessage();
    }

    // Función para actualizar el mensaje de no resultados
    function updateNoResultsMessage() {
        let noResultsMsg = document.getElementById('noResultsMessage');
        const visibleCards = Array.from(profesorCards).filter(card => card.style.display !== 'none');

        if (visibleCards.length === 0) {
            if (!noResultsMsg) {
                noResultsMsg = document.createElement('div');
                noResultsMsg.id = 'noResultsMessage';
                noResultsMsg.className = 'alert alert-warning text-center';
                noResultsMsg.innerHTML = '<h4>No se encontraron profesores</h4><p>No hay profesores que coincidan con tu búsqueda.</p>';
                // Insertar después del search-container
                const searchContainer = document.querySelector('.search-container');
                searchContainer.parentNode.insertBefore(noResultsMsg, searchContainer.nextSibling);
            }
            noResultsMsg.style.display = 'block';
        } else {
            if (noResultsMsg) {
                noResultsMsg.style.display = 'none';
            }
        }
    }

    // Event listeners
    if (searchInput) {
        // Búsqueda solo al presionar Enter
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                filterProfesores();
            }
        });
    }

    // Hacer las funciones globales para que puedan ser llamadas desde el HTML
    window.filterProfesores = filterProfesores;
    window.clearSearch = clearSearch;

    // Animaciones iniciales para las tarjetas
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

    profesorCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
        observer.observe(card);
    });

    // Mejorar accesibilidad
    function improveAccessibility() {
        const buttons = document.querySelectorAll('.profesor-card .btn');
        buttons.forEach(button => {
            if (!button.getAttribute('aria-label')) {
                const profesorName = button.closest('.profesor-card').querySelector('.card-title').textContent.replace('👨‍🏫 ', '');
                button.setAttribute('aria-label', `Reservar clase con ${profesorName}`);
            }
        });

        const searchContainer = document.querySelector('.search-container');
        if (searchContainer) {
            searchContainer.setAttribute('role', 'search');
        }
    }

    improveAccessibility();
});