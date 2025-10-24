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
        
        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid white;
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: bold;
            color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
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
                        // Generar color de avatar basado en el ID del usuario
                        $avatarClass = 'avatar-' . (($usuario['user_id'] % 8) + 1);
                        $initials = strtoupper(substr($usuario['first_name'], 0, 1) . substr($usuario['last_name'], 0, 1));
                        ?>
                        <div class="profile-avatar <?php echo $avatarClass; ?>">
                            <?php echo $initials; ?>
                        </div>
                        <div class="profile-name">
                            <?php echo htmlspecialchars($usuario['first_name'] . ' ' . $usuario['last_name']); ?>
                        </div>
                        <div class="profile-role">
                            <i class="fas fa-graduation-cap me-2"></i>Estudiante
                        </div>
                    </div>
                    
                    <div class="profile-body">
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
                                <span class="info-value"><?php echo htmlspecialchars($usuario['phone_number'] ?? 'No especificado'); ?></span>
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
</body>
</html>