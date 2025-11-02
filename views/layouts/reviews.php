<?php
// Definir la página actual para el header
$currentPage = 'reviews';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>⭐ Reviews - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=1760937939">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/reviews.css?v=<?php echo time(); ?>">
</head>
<body>
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">⭐ Reviews</h1>
            <?php include __DIR__ . '/nav.php'; ?>
        </div>
    </header>

    <main class="container my-5">
        <div class="row">
            <div class="col-12">
                <div class="reviews-table">
                    <div class="reviews-header d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1">⭐ Comentarios y Calificaciones</h3>
                            <span class="badge" style="background: rgba(255,255,255,0.2);">Sistema de Reviews</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (empty($reviews)): ?>
                            <div class="text-center py-5">
                                <div class="mb-4" style="font-size: 4rem; color: #6c757d;">⭐</div>
                                <h3>No hay reviews registradas</h3>
                                <p class="text-muted">Las reviews aparecerán aquí cuando se creen.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Profesor</th>
                                            <th>Estudiante</th>
                                            <th>Calificación</th>
                                            <th>Comentario</th>
                                            <th>Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($reviews as $review): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars(($review['profesor_name'] ?? '') . ' ' . ($review['profesor_last_name'] ?? '')); ?></td>
                                                <td><?php echo htmlspecialchars(($review['estudiante_name'] ?? '') . ' ' . ($review['estudiante_last_name'] ?? '')); ?></td>
                                                <td>
                                                    <span class="badge bg-warning text-dark">
                                                        <?php echo $review['rating'] ?? 0; ?>/5 ⭐
                                                    </span>
                                                </td>
                                                <td><?php echo htmlspecialchars($review['comment'] ?? 'Sin comentario'); ?></td>
                                                <td><?php echo htmlspecialchars($review['created_at'] ?? date('Y-m-d H:i:s')); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
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
    <script src="/plataforma-clases-online/public/js/reviews.js?v=<?php echo time(); ?>"></script>
</body>
</html>