<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ℹ️ Acerca de - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php 
    // Definir la página actual para el header
    $currentPage = 'about';
    ?>
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">ℹ️ Acerca de</h1>
            <?php include 'nav.php'; ?>
        </div>
    </header>
    
    <main>
        <h2>Sobre la Plataforma de Clases Online</h2>
        <p>Esta plataforma permite conectar estudiantes con profesores para clases en línea de manera eficiente y segura.</p>
        <p>Características principales:</p>
        <ul>
            <li>Gestión de profesores y estudiantes</li>
            <li>Reservas de clases</li>
            <li>Sistema de pagos</li>
            <li>Reviews y calificaciones</li>
            <li>Disponibilidad horaria</li>
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