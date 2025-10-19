<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔍 Explorar Profesores - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php
    // Definir la página actual para el header
    $currentPage = 'explorar_profesores';
    ?>
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">🔍 Explorar Profesores</h1>
            <?php include __DIR__ . '/../nav.php'; ?>
        </div>
    </header>

    <main class="container my-5">
        <!-- Filtros de búsqueda -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>🔍 Filtros de Búsqueda</h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="searchName" placeholder="Buscar por nombre...">
                            </div>
                            <div class="col-md-4">
                                <select class="form-select" id="filterRating">
                                    <option value="">Todas las calificaciones</option>
                                    <option value="4.5">⭐⭐⭐⭐⭐ 4.5+</option>
                                    <option value="4">⭐⭐⭐⭐ 4.0+</option>
                                    <option value="3">⭐⭐⭐ 3.0+</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="number" class="form-control" id="maxPrice" placeholder="Precio máximo por hora">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Profesores -->
        <div class="row g-4" id="profesoresContainer">
            <?php foreach ($profesores as $profesor): ?>
            <div class="col-lg-6 col-xl-4">
                <div class="dashboard-card profesor-card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div class="profesor-avatar">
                                <?php echo strtoupper(substr($profesor['first_name'], 0, 1) . substr($profesor['last_name'], 0, 1)); ?>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-0"><?php echo htmlspecialchars($profesor['first_name'] . ' ' . $profesor['last_name']); ?></h5>
                                <small class="text-muted"><?php echo htmlspecialchars($profesor['academic_level'] ?? 'Sin especificar'); ?></small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="profesor-info">
                            <div class="rating mb-2">
                                <?php
                                $rating = round($profesor['rating'], 1);
                                $fullStars = floor($rating);
                                $halfStar = $rating - $fullStars >= 0.5;
                                $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);

                                for ($i = 0; $i < $fullStars; $i++) echo '⭐';
                                if ($halfStar) echo '⭐';
                                for ($i = 0; $i < $emptyStars; $i++) echo '☆';
                                ?>
                                <span class="ms-2"><?php echo $rating; ?> (<?php echo $profesor['review_count']; ?> reseñas)</span>
                            </div>

                            <p class="profesor-description mb-3">
                                <?php echo htmlspecialchars($profesor['personal_description'] ?? 'Sin descripción disponible.'); ?>
                            </p>

                            <div class="profesor-details">
                                <div class="detail-item">
                                    <span class="detail-label">💰 Tarifa por hora:</span>
                                    <span class="detail-value">$<?php echo htmlspecialchars($profesor['hourly_rate'] ?? 'N/A'); ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">📧 Email:</span>
                                    <span class="detail-value"><?php echo htmlspecialchars($profesor['email']); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="profesor-actions mt-3">
                            <a href="/plataforma-clases-online/home/disponibilidad?profesor=<?php echo $profesor['user_id']; ?>"
                               class="btn btn-primary btn-sm me-2">
                                📅 Ver Disponibilidad
                            </a>
                            <button class="btn btn-outline-success btn-sm contact-profesor"
                                    data-profesor-id="<?php echo $profesor['user_id']; ?>"
                                    data-profesor-name="<?php echo htmlspecialchars($profesor['first_name'] . ' ' . $profesor['last_name']); ?>">
                                💬 Contactar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Mensaje si no hay profesores -->
        <?php if (empty($profesores)): ?>
        <div class="text-center py-5">
            <div class="empty-icon" style="font-size: 4rem;">👨‍🏫</div>
            <h3>No hay profesores disponibles</h3>
            <p>Por el momento no tenemos profesores registrados en la plataforma.</p>
        </div>
        <?php endif; ?>
    </main>

    <footer class="modern-footer">
        <div class="footer-content">
            <div class="footer-info">
                <div class="footer-brand">
                    <span>💎</span>
                    <span>Plataforma Clases Online</span>
                </div>
                <div class="footer-links">
                    <a href="#privacidad">Privacidad</a>
                    <a href="#terminos">Términos</a>
                    <a href="#soporte">Soporte</a>
                    <a href="#contacto">Contacto</a>
                </div>
            </div>
            <div class="footer-copy">
                © <?php echo date('Y'); ?> Plataforma Clases Online. Todos los derechos reservados.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/plataforma-clases-online/public/js/script.js"></script>
    <script>
        // Funcionalidad de filtros
        document.getElementById('searchName').addEventListener('input', filterProfesores);
        document.getElementById('filterRating').addEventListener('change', filterProfesores);
        document.getElementById('maxPrice').addEventListener('input', filterProfesores);

        function filterProfesores() {
            const searchTerm = document.getElementById('searchName').value.toLowerCase();
            const minRating = parseFloat(document.getElementById('filterRating').value) || 0;
            const maxPrice = parseFloat(document.getElementById('maxPrice').value) || Infinity;

            const cards = document.querySelectorAll('.profesor-card');

            cards.forEach(card => {
                const name = card.querySelector('h5').textContent.toLowerCase();
                const ratingText = card.querySelector('.rating span').textContent;
                const rating = parseFloat(ratingText.split(' ')[0]) || 0;
                const priceText = card.querySelector('.detail-value').textContent;
                const price = parseFloat(priceText.replace('$', '')) || 0;

                const matchesSearch = name.includes(searchTerm);
                const matchesRating = rating >= minRating;
                const matchesPrice = price <= maxPrice || isNaN(price);

                if (matchesSearch && matchesRating && matchesPrice) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Funcionalidad de contacto
        document.querySelectorAll('.contact-profesor').forEach(button => {
            button.addEventListener('click', function() {
                const profesorName = this.getAttribute('data-profesor-name');
                alert(`Función de contacto próximamente disponible para ${profesorName}`);
            });
        });
    </script>
</body>
</html>