<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>💰 Explorar por Precio por Hora - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/explorar_materias.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php
    // Definir la página actual para el header
    $currentPage = 'precio_hora';
    ?>
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">💰 Explorar por Precio por Hora</h1>
            <?php include __DIR__ . '/../layouts/nav.php'; ?>
        </div>
    </header>

    <main>
        <div class="mb-4">
            <a href="/plataforma-clases-online/home/explorar_profesores" class="btn btn-primary">← Volver a Profesores</a>
            <a href="/plataforma-clases-online/home/explorar_materias" class="btn btn-secondary ms-2">Explorar Materias</a>
        </div>

        <div class="materia-search-container">
            <form method="GET" action="/plataforma-clases-online/home/explorar_precio_hora" class="d-flex">
                <input type="text" name="search" class="form-control me-3 materia-search-input" placeholder="Buscar por rango de precio..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                <button type="submit" class="btn materia-search-btn">🔍 Buscar</button>
                <?php if (!empty($_GET['search'])): ?>
                    <a href="/plataforma-clases-online/home/explorar_precio_hora" class="btn materia-clear-btn ms-2">🧹 Limpiar</a>
                <?php endif; ?>
            </form>
        </div>

        <?php
        $precioRangos = isset($precioRangos) ? $precioRangos : [];
        ?>

        <div class="row">
            <?php if (empty($precioRangos)): ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <h4>No hay rangos de precio disponibles</h4>
                        <p>No se encontraron rangos de precio con profesores registrados.</p>
                    </div>
                </div>
            <?php else: ?>
                <?php
                $rangoParams = [
                    'Menos de $10' => 'menos-10',
                    '$10 - $20' => '10-20',
                    '$20 - $30' => '20-30',
                    '$30 - $40' => '30-40',
                    'Más de $40' => 'mas-40'
                ];
                foreach ($precioRangos as $rango => $profesoresEnRango): ?>
                    <?php if (!empty($profesoresEnRango)): ?>
                        <div class="col-md-4 mb-4">
                            <div class="materia-card">
                                <div class="card-body">
                                    <h5 class="card-title">💰 <?php echo htmlspecialchars($rango); ?></h5>
                                    <p class="card-text">
                                        <strong>👨‍🏫 Profesores:</strong> <?php echo count($profesoresEnRango); ?>
                                    </p>
                                </div>
                                <div class="card-footer">
                                    <a href="/plataforma-clases-online/home/profesores_por_precio?precio_rango=<?php echo urlencode($rangoParams[$rango]); ?>" class="btn">👨‍🏫 Ver Profesores</a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
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
    <script src="/plataforma-clases-online/public/js/explorar_precio_hora.js?v=<?php echo time(); ?>"></script>
</body>
</html>