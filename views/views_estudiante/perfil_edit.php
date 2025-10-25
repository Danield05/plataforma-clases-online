<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">"
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
        
        .edit-profile-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .edit-profile-header {
            background: linear-gradient(135deg, #371783 0%, #8B5A96 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .edit-profile-header h2 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: bold;
        }
        
        .edit-profile-header p {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
            font-size: 1rem;
        }
        
        .edit-profile-body {
            padding: 2rem;
        }
        
        .form-section {
            margin-bottom: 2rem;
        }
        
        .form-section-title {
            font-size: 1.1rem;
            font-weight: bold;
            color: #371783;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 0.5rem;
        }
        
        .form-control {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #371783;
            box-shadow: 0 0 0 0.2rem rgba(55, 23, 131, 0.25);
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .btn-save-profile {
            background: linear-gradient(135deg, #371783 0%, #8B5A96 100%);
            border: none;
            border-radius: 25px;
            padding: 0.75rem 2rem;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-save-profile:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(55, 23, 131, 0.3);
            color: white;
        }
        
        .btn-cancel-profile {
            background: #6c757d;
            border: none;
            border-radius: 25px;
            padding: 0.75rem 2rem;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-cancel-profile:hover {
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
            color: white;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .edit-profile-body {
                padding: 1.5rem;
            }
            
            .form-section-title {
                font-size: 1rem;
            }
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
            <h1 class="header-title">🎓 Editar Perfil</h1>
            <?php include __DIR__ . '/../layouts/nav.php'; ?>
        </div>
    </header>

    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Tarjeta de Edición de Perfil -->
                <div class="edit-profile-card">
                    <div class="edit-profile-header">
                        <h2>✏️ Editar Mi Perfil</h2>
                        <p>Actualiza tu información personal</p>
                    </div>
                    
                    <div class="edit-profile-body">
                        <?php if (isset($_GET['status'])): ?>
                            <div class="alert alert-<?php echo $_GET['status'] === 'updated' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                                <?php echo $_GET['status'] === 'updated' ? '✅ Perfil actualizado correctamente.' : '❌ Error al actualizar el perfil.'; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form action="/plataforma-clases-online/home/perfil_update" method="POST">
                            <!-- Información Personal -->
                            <div class="form-section">
                                <div class="form-section-title">
                                    <i class="fas fa-user"></i>
                                    Información Personal
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="first_name" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" 
                                               value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" 
                                               placeholder="Ingresa tu nombre" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="last_name" class="form-label">Apellido</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" 
                                               value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" 
                                               placeholder="Ingresa tu apellido" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" 
                                           placeholder="tu@email.com" required>
                                </div>

                                <div class="mb-3">
                                    <label for="phone_number" class="form-label">Teléfono</label>
                                    <input type="tel" class="form-control" id="phone_number" name="phone_number" 
                                           value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" 
                                           placeholder="Tu número de teléfono">
                                </div>
                            </div>

                            <!-- Información Adicional -->
                            <div class="form-section">
                                <div class="form-section-title">
                                    <i class="fas fa-info-circle"></i>
                                    Información Adicional
                                </div>
                                
                                <div class="mb-3">
                                    <label for="personal_description" class="form-label">Descripción Personal</label>
                                    <textarea class="form-control" id="personal_description" name="personal_description" 
                                              rows="4" placeholder="Cuéntanos un poco sobre ti..."><?php echo htmlspecialchars($estudiante['personal_description'] ?? ''); ?></textarea>
                                </div>
                            </div>

                            <!-- Seguridad -->
                            <div class="form-section">
                                <div class="form-section-title">
                                    <i class="fas fa-lock"></i>
                                    Seguridad
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password" class="form-label">Nueva Contraseña</label>
                                    <input type="password" class="form-control" id="password" name="password" 
                                           placeholder="Dejar vacío para mantener la actual">
                                    <div class="form-text">Solo ingresa una nueva contraseña si deseas cambiarla</div>
                                </div>
                            </div>

                            <!-- Botones de Acción -->
                            <div class="d-flex gap-3 justify-content-center mt-4">
                                <button type="submit" class="btn-save-profile">
                                    <i class="fas fa-save me-2"></i>Guardar Cambios
                                </button>
                                <a href="/plataforma-clases-online/home/perfil_view" class="btn-cancel-profile">
                                    <i class="fas fa-times me-2"></i>Cancelar
                                </a>
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