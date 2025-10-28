<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Plataforma de Clases Online</title>
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
        
        .profile-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .profile-header {
            background: linear-gradient(135deg, #371783 0%, #8B5A96 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .profile-avatar-container {
            position: relative;
            width: 100px;
            height: 100px;
            margin: 0 auto 1rem;
            cursor: pointer;
            border-radius: 50%;
            overflow: hidden;
        }
        
        .profile-avatar-container:hover .profile-photo-overlay {
            opacity: 1;
        }
        
        .profile-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border: 4px solid white;
            border-radius: 50%;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid white;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: bold;
            color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .profile-photo-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 50%;
            color: white;
            font-size: 0.9rem;
        }
        
        .profile-photo-overlay i {
            font-size: 1.5rem;
            margin-bottom: 0.3rem;
        }
        
        /* Mejorar la accesibilidad y UX */
        .profile-avatar-container:focus {
            outline: 2px solid #fff;
            outline-offset: 2px;
        }
        
        .profile-avatar-container:active {
            transform: scale(0.98);
        }
        
        /* Animación suave para cambios */
        .profile-photo, .profile-avatar {
            transition: all 0.3s ease;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .profile-avatar-container, .profile-photo, .profile-avatar {
                width: 80px;
                height: 80px;
            }
            
            .profile-avatar {
                font-size: 2rem;
            }
        }
        
        .profile-name {
            font-size: 1.6rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        
        .profile-role {
            font-size: 1rem;
            opacity: 0.9;
        }
        
        .profile-body {
            padding: 2rem;
        }
        
        .info-section {
            margin-bottom: 2rem;
        }
        
        .info-title {
            font-size: 1.1rem;
            font-weight: bold;
            color: #371783;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 500;
            color: #666;
        }
        
        .info-value {
            font-weight: 600;
            color: #333;
        }
        
        .btn-edit-profile {
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
        
        .btn-edit-profile:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(55, 23, 131, 0.3);
            color: white;
        }
        
        /* Avatar con colores generados por ID */
        .avatar-1 { background: linear-gradient(135deg, #371783 0%, #8B5A96 100%); }
        .avatar-2 { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .avatar-3 { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .avatar-4 { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
        .avatar-5 { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
        .avatar-6 { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); }
        .avatar-7 { background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); }
        .avatar-8 { background: linear-gradient(135deg, #ff8a80 0%, #ffb74d 100%); }
    </style>
</head>
<body>
    <?php
    // Definir la página actual para el header
    $currentPage = 'perfil_view';
    ?>
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">👤 Mi Perfil</h1>
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
                            <i class="fas fa-graduation-cap me-2"></i>Estudiante
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
    <script>
        // Manejar subida de foto de perfil
        document.addEventListener('DOMContentLoaded', function() {
            const photoContainer = document.querySelector('.profile-avatar-container');
            const photoInput = document.getElementById('profilePhotoInput');
            const photoDisplay = document.getElementById('profilePhotoDisplay');
            const avatarDisplay = document.getElementById('profileAvatarDisplay');

            // Clic en el contenedor abre el selector de archivos
            photoContainer.addEventListener('click', function() {
                photoInput.click();
            });

            // Cuando se selecciona un archivo
            photoInput.addEventListener('change', function() {
                const file = this.files[0];
                if (!file) return;

                // Validar tipo de archivo
                const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Por favor selecciona una imagen válida (JPG, PNG, GIF o WebP)');
                    return;
                }

                // Validar tamaño (5MB máximo)
                if (file.size > 5 * 1024 * 1024) {
                    alert('La imagen es demasiado grande. Máximo 5MB');
                    return;
                }

                // Mostrar preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Si existe foto, actualizar src; si no, crear elemento img
                    if (photoDisplay) {
                        photoDisplay.src = e.target.result;
                    } else if (avatarDisplay) {
                        // Reemplazar avatar con imagen
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = 'Foto de perfil';
                        img.className = 'profile-photo';
                        img.id = 'profilePhotoDisplay';
                        avatarDisplay.parentNode.replaceChild(img, avatarDisplay);
                    }
                };
                reader.readAsDataURL(file);

                // Subir archivo
                uploadProfilePhoto(file);
            });

            function uploadProfilePhoto(file) {
                const formData = new FormData();
                formData.append('profile_photo', file);

                // Mostrar loading
                const overlay = document.querySelector('.profile-photo-overlay');
                if (overlay) {
                    overlay.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Subiendo...</span>';
                    overlay.style.opacity = '1';
                }

                fetch('/plataforma-clases-online/home/upload_profile_photo', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    // Verificar si la respuesta es válida
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    // Verificar el tipo de contenido
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        // Si no es JSON, probablemente es un error HTML
                        return response.text().then(text => {
                            console.error('Respuesta no JSON:', text);
                            throw new Error('El servidor devolvió una respuesta no válida');
                        });
                    }
                    
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Actualizar imagen con la URL del servidor
                        const img = document.getElementById('profilePhotoDisplay');
                        if (img) {
                            img.src = data.photo_url;
                        }
                        
                        // Actualizar también la foto en la navegación
                        updateNavProfilePhoto(data.photo_url);
                        
                        // Mostrar mensaje de éxito
                        showMessage('Foto de perfil actualizada correctamente', 'success');
                    } else {
                        showMessage(data.message || 'Error al subir la imagen', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('Error al subir la imagen', 'error');
                })
                .finally(() => {
                    // Restaurar overlay
                    if (overlay) {
                        overlay.innerHTML = '<i class="fas fa-camera"></i><span>Cambiar foto</span>';
                        overlay.style.opacity = '0';
                    }
                });
            }

            function showMessage(message, type) {
                // Crear elemento de notificación
                const notification = document.createElement('div');
                notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
                notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 300px;';
                notification.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;

                document.body.appendChild(notification);

                // Auto-remover después de 3 segundos
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 3000);
            }

            function updateNavProfilePhoto(photoUrl) {
                // Buscar el avatar en la navegación
                const navAvatar = document.querySelector('.user-avatar-small');
                if (navAvatar) {
                    // Verificar si ya hay una imagen
                    const existingImg = navAvatar.querySelector('.nav-profile-photo');
                    if (existingImg) {
                        // Actualizar la imagen existente
                        existingImg.src = photoUrl;
                    } else {
                        // Reemplazar el contenido del avatar con una imagen
                        navAvatar.innerHTML = `<img src="${photoUrl}" alt="Foto de perfil" class="nav-profile-photo">`;
                        // Remover clases de color de avatar
                        navAvatar.className = navAvatar.className.replace(/nav-avatar-\d+/g, '');
                    }
                }
            }
        });
    </script>
</body>
</html>