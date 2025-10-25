
<?php
// Incluir configuración desde la raíz del proyecto
require_once __DIR__ . '/../../config/config.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔐 Login - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
</head>
<body class="login-page">
    <!-- Estrellas espaciales -->
    <div class="stars">
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
    </div>
    
    <?php if (isset($error)): ?>
         <div class="alert alert-danger alert-dismissible fade show notification-alert" role="alert" id="errorAlert">
                 <strong>¡Error!</strong> <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
     <?php endif; ?>
    <div class="login-container">
        <!-- Panel izquierdo con el formulario -->
        <div class="login-panel">
            <!-- Formulario de login moderno -->
            <form action="<?php echo BASE_URL; ?>/auth/login" method="POST" id="loginForm" class="modern-login-form">
                <div class="form-header">
                    <div class="logo-container">
                        <span class="logo-icon">📝</span>
                    </div>
                    <h2>Iniciar Sesión</h2>
                    <p>¿No tienes una cuenta? <a href="#" class="register-link">Regístrate aquí</a></p>
                </div>

                <div class="form-group-modern">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" required placeholder="Ingresa tu correo electrónico">
                </div>

                <div class="form-group-modern">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required placeholder="Ingresa tu contraseña">
                </div>

                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" id="remember">
                        <span class="checkmark"></span>
                        Recordarme
                    </label>
                    <a href="#" class="forgot-password">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" class="btn-login">Iniciar Sesión</button>

                <div class="form-footer">
                    <p>© <?php echo date('Y'); ?> Plataforma Clases Online. Todos los derechos reservados.</p>
                </div>
            </form>
        </div>

        <!-- Panel derecho con ilustración educativa -->
        <div class="image-panel">
            <div class="hero-illustration">
                <div class="illustration-container">
                    <div class="education-elements">
                        <div class="book book-1">📖</div>
                        <div class="book book-2">📚</div>
                        <div class="book book-3">📗</div>
                         <div class="pencil pencil-1">✏️</div>
                        <div class="pencil pencil-2">🖍️</div>
                        <div class="pencil pencil-3">🖼️</div>
                        <div class="item item-1">🎨</div>
                        <div class="item item-2">📐</div>
                        <div class="item item-3">📏</div>
                        <div class="item item-4">🖊️</div>
                        <div class="school school-1">🏫</div>
                        <div class="students students-1">👨‍🎓</div>
                        <div class="students students-2">👩‍🎓</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-ocultar la notificación de error después de 5 segundos
        document.addEventListener('DOMContentLoaded', function() {
            const errorAlert = document.getElementById('errorAlert');
            if (errorAlert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(errorAlert);
                    bsAlert.close();
                }, 5000); // 5 segundos
            }
        });
    </script>
</body>
</html>