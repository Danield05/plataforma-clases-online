<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🏠 Inicio - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php 
    // Definir la página actual para el header
    $currentPage = 'home';
    ?>
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">🏠 Inicio</h1>
            <?php include 'nav.php'; ?>
        </div>
    </header>
    
    <main>
        <h2>Dashboard</h2>
        <div class="stats">
            <div class="stat">
                <h3>Profesores</h3>
                <p><?php echo count($data['profesores'] ?? []); ?> registrados</p>
            </div>
            <div class="stat">
                <h3>Estudiantes</h3>
                <p><?php echo count($data['estudiantes'] ?? []); ?> registrados</p>
            </div>
            <div class="stat">
                <h3>Reservas</h3>
                <p><?php echo count($data['reservas'] ?? []); ?> activas</p>
            </div>
        </div>
        <h3>Profesores Recientes</h3>
        <ul>
            <?php foreach(array_slice($data['profesores'] ?? [], 0, 5) as $profesor): ?>
                <li><?php echo $profesor['first_name'] . ' ' . $profesor['last_name']; ?> - <?php echo $profesor['academic_level'] ?? 'Sin nivel'; ?></li>
            <?php endforeach; ?>
        </ul>
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
</body>
</html>