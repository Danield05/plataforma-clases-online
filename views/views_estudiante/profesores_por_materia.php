<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>👨‍🏫 Profesores de <?php echo htmlspecialchars($materia['subject_name']); ?> - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/profesores_por_materia.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php
    // Definir la página actual para el header
    $currentPage = 'profesores';
    ?>
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">👨‍🏫 Profesores de <?php echo htmlspecialchars($materia['subject_name']); ?></h1>
            <?php include __DIR__ . '/../layouts/nav.php'; ?>
        </div>
    </header>

    <main>
        <div class="mb-4">
            <a href="/plataforma-clases-online/home/explorar_materias" class="btn btn-primary">← Volver a Materias</a>
            <a href="/plataforma-clases-online/home/explorar_profesores" class="btn btn-secondary ms-2">Ver Todos los Profesores</a>
        </div>

        <div class="alert alert-info">
            <h5>📚 Información de la Materia</h5>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($materia['subject_name']); ?></p>
            <?php if (!empty($materia['description'])): ?>
                <p><strong>Descripción:</strong> <?php echo htmlspecialchars($materia['description']); ?></p>
            <?php endif; ?>

        <div class="search-container">
            <div class="row">
                <div class="col-md-8">
                    <input type="text" class="form-control search-input" id="searchInput" placeholder="🔍 Buscar profesor por nombre..." aria-label="Buscar profesor por nombre">
                </div>
                <div class="col-md-2">
                    <button class="btn search-btn w-100" onclick="filterProfesores()">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
                <div class="col-md-2">
                    <button class="btn clear-btn w-100" onclick="clearSearch()">
                        <i class="fas fa-times"></i> Limpiar
                    </button>
                </div>
            </div>
        </div>

        <?php if (empty($profesores)): ?>
            <div class="alert alert-warning text-center">
                <h4>No hay profesores disponibles</h4>
                <p>No se encontraron profesores que enseñen <?php echo htmlspecialchars($materia['subject_name']); ?> en este momento.</p>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach((array)$profesores as $pr): ?>
                    <div class="col-md-4 mb-4">
                        <div class="profesor-card">
                            <div class="card-body">
                                <h5 class="card-title">👨‍🏫 <?php echo htmlspecialchars($pr['first_name'] . ' ' . $pr['last_name']); ?></h5>
                                <p class="card-text">
                                    📧 <?php echo htmlspecialchars($pr['email']); ?>
                                </p>
                                <p class="card-text">
                                    <strong>🎓 Nivel Académico:</strong> <?php echo htmlspecialchars($pr['academic_level'] ?? 'N/A'); ?>
                                </p>
                                <p class="card-text">
                                    <strong>💰 Tarifa por Hora:</strong> <?php echo $pr['hourly_rate'] ? '$' . htmlspecialchars($pr['hourly_rate']) : 'N/A'; ?>
                                </p>
                                <?php if (!empty($pr['personal_description'])): ?>
                                    <p class="card-text">
                                        <strong>📝 Descripción:</strong> <?php echo htmlspecialchars(substr($pr['personal_description'], 0, 100)); ?><?php echo strlen($pr['personal_description']) > 100 ? '...' : ''; ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <div class="card-footer">
                                <a href="/plataforma-clases-online/home/reservar_clase?profesor_id=<?php echo $pr['user_id']; ?>" class="btn">📅 Reservar Clase</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
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
    <script src="/plataforma-clases-online/public/js/profesores_por_materia.js?v=<?php echo time(); ?>"></script>
</body>
</html>