<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/perfil_view.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php
    $currentPage = 'perfil_view';
    ?>
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">👨‍🏫 Mi Perfil</h1>
            <?php include __DIR__ . '/../layouts/nav.php'; ?>
        </div>
    </header>

    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Tarjeta Principal del Perfil -->
                <div class="profile-card">
                    <div class="profile-header">
                        <?php 
                        // Incluir funciones helper para avatares
                        require_once __DIR__ . '/../../helpers/avatar_helper.php';
                        
                        // Verificar si el usuario tiene foto de perfil
                        $profilePhotoUrl = getProfilePhotoUrl($usuario['user_id']);
                        
                        // Generar color de avatar basado en el ID del usuario
                        $avatarClass = 'avatar-' . (($usuario['user_id'] % 8) + 1);
                        $initials = strtoupper(substr($usuario['first_name'], 0, 1) . substr($usuario['last_name'], 0, 1));
                        ?>
                        <div class="profile-avatar-container">
                            <?php if ($profilePhotoUrl): ?>
                                <img src="<?php echo $profilePhotoUrl; ?>" alt="Foto de perfil" class="profile-photo" id="profilePhotoDisplay">
                            <?php else: ?>
                                <div class="profile-avatar <?php echo $avatarClass; ?>" id="profileAvatarDisplay">
                                    <?php echo $initials; ?>
                                </div>
                            <?php endif; ?>
                            <div class="profile-photo-overlay">
                                <i class="fas fa-camera"></i>
                                <span>Cambiar foto</span>
                            </div>
                            <input type="file" id="profilePhotoInput" accept="image/*" style="display: none;">
                        </div>
                        <div class="profile-name">
                            <?php echo htmlspecialchars($usuario['first_name'] . ' ' . $usuario['last_name']); ?>
                        </div>
                        <div class="profile-role">
                            <i class="fas fa-chalkboard-teacher me-2"></i>Profesor
                        </div>
                    </div>
                    
                    <div class="profile-body">
                        <?php if (isset($_GET['status']) && $_GET['status'] === 'updated'): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                ¡Perfil actualizado correctamente!
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Información Personal -->
                        <div class="info-section">
                            <div class="info-title">
                                <i class="fas fa-user"></i>
                                Información Personal
                            </div>
                            <div class="info-item">
                                <span class="info-label">Nombre Completo</span>
                                <span class="info-value"><?php echo htmlspecialchars($usuario['first_name'] . ' ' . $usuario['last_name']); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Correo Electrónico</span>
                                <span class="info-value"><?php echo htmlspecialchars($usuario['email']); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Teléfono</span>
                                <span class="info-value"><?php echo htmlspecialchars($usuario['phone'] ?? 'No especificado'); ?></span>
                            </div>
                        </div>

                        <!-- Información Profesional -->
                        <div class="info-section">
                            <div class="info-title">
                                <i class="fas fa-briefcase"></i>
                                Información Profesional
                            </div>
                            <div class="info-item">
                                <span class="info-label">Nivel Académico</span>
                                <span class="info-value"><?php echo htmlspecialchars($profesor['academic_level'] ?? 'No especificado'); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Tarifa por Hora</span>
                                <span class="info-value">
                                    <?php if (!empty($profesor['hourly_rate'])): ?>
                                        <span class="rate-badge">$<?php echo number_format($profesor['hourly_rate'], 2); ?></span>
                                    <?php else: ?>
                                        No especificado
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Enlace de Reunión</span>
                                <span class="info-value">
                                    <?php if (!empty($profesor['meeting_link'])): ?>
                                        <a href="<?php echo htmlspecialchars($profesor['meeting_link']); ?>" target="_blank" class="text-primary">
                                            <i class="fas fa-external-link-alt me-1"></i>Ver enlace
                                        </a>
                                    <?php else: ?>
                                        No especificado
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>

                        <!-- Descripción Personal -->
                        <?php if (!empty($profesor['personal_description'])): ?>
                        <div class="info-section">
                            <div class="info-title">
                                <i class="fas fa-quote-left"></i>
                                Acerca de mí
                            </div>
                            <div class="alert alert-light border-0" style="background: #f8f9fa;">
                                <p class="mb-0"><?php echo nl2br(htmlspecialchars($profesor['personal_description'])); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Información del Sistema -->
                        <div class="info-section">
                            <div class="info-title">
                                <i class="fas fa-info-circle"></i>
                                Información del Sistema
                            </div>
                            <div class="info-item">
                                <span class="info-label">Fecha de Registro</span>
                                <span class="info-value"><?php echo date('d/m/Y', strtotime($usuario['created_at'])); ?></span>
                            </div>
                        </div>

                        <!-- Botón de Editar -->
                        <div class="text-center mt-4">
                            <a href="/plataforma-clases-online/home/perfil_edit" class="btn-edit-profile">
                                <i class="fas fa-edit me-2"></i>Editar Perfil
                            </a>
                        </div>
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
    <script src="/plataforma-clases-online/public/js/perfil_view.js?v=<?php echo time(); ?>"></script>
</body>
</html>