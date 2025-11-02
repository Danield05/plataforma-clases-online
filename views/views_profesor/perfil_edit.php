<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/perfil_edit.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php
    // Definir la página actual para el header
    $currentPage = 'perfil_edit';
    ?>
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">📝 Editar Perfil</h1>
            <?php include __DIR__ . '/../layouts/nav.php'; ?>
        </div>
    </header>

    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card edit-card">
                    <div class="card-header edit-header">
                        <h3>
                            <i class="fas fa-chalkboard-teacher me-2"></i>Editar Perfil de Profesor
                        </h3>
                    </div>
                    <div class="card-body edit-body">
                        <?php if (isset($_GET['status'])): ?>
                            <div class="alert alert-<?php echo $_GET['status'] === 'updated' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                                <i class="fas fa-<?php echo $_GET['status'] === 'updated' ? 'check-circle' : 'exclamation-triangle'; ?> me-2"></i>
                                <?php echo $_GET['status'] === 'updated' ? 'Perfil actualizado correctamente.' : 'Error al actualizar el perfil.'; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form action="/plataforma-clases-online/home/perfil_update" method="POST">
                            <!-- Información Personal -->
                            <div class="form-section">
                                <div class="section-title">
                                    <i class="fas fa-user"></i>
                                    Información Personal
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="first_name" class="form-label">Nombre</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                <input type="text" class="form-control" id="first_name" name="first_name" 
                                                       value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="last_name" class="form-label">Apellido</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                <input type="text" class="form-control" id="last_name" name="last_name" 
                                                       value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Correo Electrónico</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Teléfono</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input type="tel" class="form-control" id="phone" name="phone" 
                                               value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" 
                                               placeholder="Ej: +1 234 567 8900">
                                    </div>
                                </div>
                            </div>

                            <!-- Seguridad -->
                            <div class="form-section">
                                <div class="section-title">
                                    <i class="fas fa-shield-alt"></i>
                                    Seguridad
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Nueva Contraseña</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control" id="password" name="password" 
                                               placeholder="Dejar en blanco para mantener la actual">
                                    </div>
                                    <div class="form-text">Solo llena este campo si deseas cambiar tu contraseña.</div>
                                </div>
                            </div>

                            <!-- Información Profesional -->
                            <div class="form-section">
                                <div class="section-title">
                                    <i class="fas fa-briefcase"></i>
                                    Información Profesional
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="academic_level" class="form-label">Nivel Académico</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-graduation-cap"></i></span>
                                                <input type="text" class="form-control" id="academic_level" name="academic_level" 
                                                       value="<?php echo htmlspecialchars($profesor['academic_level'] ?? ''); ?>" 
                                                       placeholder="Ej: Licenciatura, Maestría, Doctorado">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="hourly_rate" class="form-label">Tarifa por Hora ($)</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                                <input type="number" class="form-control" id="hourly_rate" name="hourly_rate" 
                                                       value="<?php echo htmlspecialchars($profesor['hourly_rate'] ?? ''); ?>" 
                                                       step="0.01" min="0" placeholder="0.00">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="meeting_link" class="form-label">Enlace de Reunión</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-video"></i></span>
                                        <input type="url" class="form-control" id="meeting_link" name="meeting_link" 
                                               value="<?php echo htmlspecialchars($profesor['meeting_link'] ?? ''); ?>" 
                                               placeholder="https://meet.google.com/... o https://zoom.us/...">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="personal_description" class="form-label">Descripción Personal</label>
                                    <textarea class="form-control" id="personal_description" name="personal_description" 
                                              rows="4" placeholder="Describe tu experiencia, metodología de enseñanza, especialidades y lo que hace único tu enfoque educativo..."><?php echo htmlspecialchars($profesor['personal_description'] ?? ''); ?></textarea>
                                    <div class="form-text">Esta descripción será visible para los estudiantes interesados en tus clases.</div>
                                </div>
                            </div>

                            <!-- Botones de Acción -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="/plataforma-clases-online/home/perfil_view" class="btn-cancel">
                                    <i class="fas fa-times me-2"></i>Cancelar
                                </a>
                                <button type="submit" class="btn-save">
                                    <i class="fas fa-save me-2"></i>Guardar Cambios
                                </button>
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
    <script src="/plataforma-clases-online/public/js/perfil_edit.js?v=<?php echo time(); ?>"></script>
</body>
</html>