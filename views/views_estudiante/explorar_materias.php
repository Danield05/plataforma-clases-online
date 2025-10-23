<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📚 Explorar Materias - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/explorar_materias.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php
    // Definir la página actual para el header
    $currentPage = 'materias';
    ?>
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">📚 Explorar Materias</h1>
            <?php include __DIR__ . '/../layouts/nav.php'; ?>
        </div>
    </header>

    <main>
        <div class="mb-4">
            <a href="/plataforma-clases-online/home/explorar_profesores" class="btn materia-back-btn">← Volver a Profesores</a>
        </div>

        <div class="materia-search-container">
            <form method="GET" action="/plataforma-clases-online/home/explorar_materias" class="d-flex">
                <input type="text" name="search" class="form-control me-3 materia-search-input" placeholder="Buscar por nombre de materia..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                <button type="submit" class="btn materia-search-btn">🔍 Buscar</button>
                <?php if (!empty($_GET['search'])): ?>
                    <a href="/plataforma-clases-online/home/explorar_materias" class="btn materia-clear-btn ms-2">🧹 Limpiar</a>
                <?php endif; ?>
            </form>
        </div>

        <?php
        $materias = isset($materias) ? $materias : [];
        ?>

        <div class="row">
            <?php if (empty($materias)): ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <h4>No hay materias disponibles</h4>
                        <p>No se encontraron materias registradas en el sistema.</p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach((array)$materias as $materia): ?>
                    <div class="col-md-4 mb-4">
                        <div class="materia-card">
                            <div class="card-body">
                                <h5 class="card-title">📚 <?php echo htmlspecialchars($materia['subject_name']); ?></h5>
                                <p class="card-text">
                                    <strong>👨‍🏫 Profesores:</strong> <?php echo htmlspecialchars($materia['profesor_count'] ?? 0); ?>
                                </p>
                                <?php if (!empty($materia['description'])): ?>
                                    <p class="card-text">
                                        <strong>Descripción:</strong> <?php echo htmlspecialchars(substr($materia['description'], 0, 100)); ?><?php echo strlen($materia['description']) > 100 ? '...' : ''; ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <div class="card-footer">
                                <a href="/plataforma-clases-online/home/profesores_por_materia?materia_id=<?php echo $materia['subject_id']; ?>" class="btn">👨‍🏫 Ver Profesores</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
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
    <script src="/plataforma-clases-online/public/js/explorar_materias.js?v=<?php echo time(); ?>"></script>
</body>
</html>