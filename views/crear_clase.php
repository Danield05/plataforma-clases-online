<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Clase - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php
    // Definir la página actual para el header
    $currentPage = 'crear_clase';
    ?>
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">📝 Crear Nueva Clase</h1>
            <?php include __DIR__ . '/nav.php'; ?>
        </div>
    </header>

    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>📅 Programar Clase</h3>
                        <span class="badge bg-primary">Nueva Reserva</span>
                    </div>
                    <div class="card-body">
                        <form action="/plataforma-clases-online/home/crear_clase_store" method="POST">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="class_date" class="form-label">Fecha de la Clase</label>
                                    <input type="date" class="form-control" id="class_date" name="class_date" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="start_time" class="form-label">Hora de Inicio</label>
                                    <input type="time" class="form-control" id="start_time" name="start_time" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="end_time" class="form-label">Hora de Fin</label>
                                    <input type="time" class="form-control" id="end_time" name="end_time" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="student_user_id" class="form-label">Estudiante</label>
                                    <select class="form-select" id="student_user_id" name="student_user_id" required>
                                        <option value="">Seleccionar estudiante</option>
                                        <?php if (!empty($estudiantes)): ?>
                                            <?php foreach($estudiantes as $est): ?>
                                                <option value="<?php echo $est['user_id']; ?>">
                                                    <?php echo htmlspecialchars($est['first_name'] . ' ' . $est['last_name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="class_description" class="form-label">Descripción de la Clase</label>
                                    <textarea class="form-control" id="class_description" name="class_description" rows="3" placeholder="Describe el tema o contenido de la clase"></textarea>
                                </div>
                                <div class="col-12">
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            📅 Crear Clase
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Información adicional -->
                <div class="dashboard-card mt-4">
                    <div class="card-header">
                        <h4>💡 Información Importante</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2">• Las clases deben programarse con al menos 24 horas de anticipación</li>
                            <li class="mb-2">• Verifica tu disponibilidad antes de crear la clase</li>
                            <li class="mb-2">• El estudiante recibirá una notificación automática</li>
                            <li>• Puedes cancelar o modificar la clase hasta 12 horas antes</li>
                        </ul>
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
</body>
</html>