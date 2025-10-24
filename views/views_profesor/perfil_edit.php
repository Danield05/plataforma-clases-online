<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php
    // Definir la página actual para el header
    $currentPage = 'perfil_edit';
    ?>
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">👨‍🏫 Editar Perfil</h1>
            <?php include __DIR__ . '/../layouts/nav.php'; ?>
        </div>
    </header>

    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>✏️ Editar Mi Perfil</h3>
                        <span class="badge bg-primary">Información Personal</span>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_GET['status'])): ?>
                            <div class="alert alert-<?php echo $_GET['status'] === 'updated' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                                <?php echo $_GET['status'] === 'updated' ? 'Perfil actualizado correctamente.' : 'Error al actualizar el perfil.'; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form action="/plataforma-clases-online/home/perfil_update" method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Apellido</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Nueva Contraseña (dejar vacío para mantener la actual)</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Nueva contraseña">
                            </div>

                            <div class="mb-3">
                                <label for="personal_description" class="form-label">Descripción Personal</label>
                                <textarea class="form-control" id="personal_description" name="personal_description" rows="3"><?php echo htmlspecialchars($profesor['personal_description'] ?? ''); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="academic_level" class="form-label">Nivel Académico</label>
                                <input type="text" class="form-control" id="academic_level" name="academic_level" value="<?php echo htmlspecialchars($profesor['academic_level'] ?? ''); ?>">
                            </div>

                            <div class="mb-3">
                                <label for="hourly_rate" class="form-label">Tarifa por Hora ($)</label>
                                <input type="number" class="form-control" id="hourly_rate" name="hourly_rate" step="0.01" value="<?php echo htmlspecialchars($profesor['hourly_rate'] ?? ''); ?>">
                            </div>

                            <div class="mb-3">
                                <label for="meeting_link" class="form-label">Enlace de Reunión (Zoom, Meet, etc.)</label>
                                <input type="url" class="form-control" id="meeting_link" name="meeting_link" placeholder="https://meet.google.com/..." value="<?php echo htmlspecialchars($profesor['meeting_link'] ?? ''); ?>">
                                <div class="form-text">Ingresa el enlace permanente para tus reuniones virtuales</div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">💾 Guardar Cambios</button>
                                <a href="/plataforma-clases-online/home" class="btn btn-secondary">❌ Cancelar</a>
                            </div>
                        </form>
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