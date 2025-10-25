<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
    <style>
        /* Asegurar que el footer sea visible */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        main {
            flex: 1;
        }
        
        .modern-footer {
            margin-top: auto;
            background: linear-gradient(135deg, #371783 0%, #8B5A96 100%);
            color: white;
            padding: 2rem 1rem;
            width: 100%;
            position: relative;
            z-index: 10;
        }
        
        .edit-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: none;
        }
        
        .edit-header {
            background: linear-gradient(135deg, #371783 0%, #8B5A96 100%);
            color: white;
            padding: 2rem;
            text-align: center;
            border-radius: 0;
            border: none;
        }
        
        .edit-header h3 {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .edit-body {
            padding: 2.5rem;
        }
        
        .form-section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 15px;
            border-left: 4px solid #371783;
        }
        
        .section-title {
            color: #371783;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .input-group-text {
            background: linear-gradient(135deg, #371783 0%, #8B5A96 100%);
            color: white;
            border: none;
            min-width: 45px;
            justify-content: center;
        }
        
        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #371783;
            box-shadow: 0 0 0 0.2rem rgba(55, 23, 131, 0.25);
            transform: translateY(-2px);
        }
        
        .input-group .form-control {
            border-left: none;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }
        
        .btn-save {
            background: linear-gradient(135deg, #371783 0%, #8B5A96 100%);
            border: none;
            border-radius: 25px;
            padding: 0.75rem 2rem;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            min-width: 160px;
        }
        
        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(55, 23, 131, 0.3);
            color: white;
        }
        
        .btn-cancel {
            background: #6c757d;
            border: none;
            border-radius: 25px;
            padding: 0.75rem 2rem;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            min-width: 160px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-cancel:hover {
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
            color: white;
            text-decoration: none;
        }
        
        .alert {
            border-radius: 15px;
            border: none;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        /* Animaciones */
        .form-section {
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Mejoras responsive */
        @media (max-width: 768px) {
            .edit-body {
                padding: 1.5rem;
            }
            
            .form-section {
                padding: 1rem;
            }
            
            .btn-save, .btn-cancel {
                width: 100%;
                margin-bottom: 0.5rem;
            }
        }
        
        /* Mejorar la apariencia del textarea */
        textarea.form-control {
            resize: vertical;
            min-height: 120px;
        }
        
        /* Placeholder styling */
        .form-control::placeholder {
            color: #6c757d;
            font-style: italic;
        }
        
        /* Custom number input styling */
        input[type="number"].form-control {
            appearance: textfield;
            -moz-appearance: textfield;
        }
        
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
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
</body>
</html>