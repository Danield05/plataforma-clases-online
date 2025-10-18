<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📅 Reservas - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php 
    // Definir la página actual para el header
    $currentPage = 'reservas';
    ?>
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">📅 Reservas</h1>
            <?php include 'nav.php'; ?>
        </div>
    </header>
    
    <main>
        <h2>Lista de Reservas</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Reserva</th>
                    <th>Profesor</th>
                    <th>Estudiante</th>
                    <th>Fecha de Clase</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($reservas as $reserva): ?>
                    <tr>
                        <td><?php echo $reserva['reservation_id']; ?></td>
                        <td><?php echo $reserva['profesor_name']; ?></td>
                        <td><?php echo $reserva['estudiante_name']; ?></td>
                        <td><?php echo $reserva['class_date']; ?></td>
                        <td><?php echo $reserva['reservation_status']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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