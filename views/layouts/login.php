<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔐 Login - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">🔐 Iniciar Sesión</h1>
        </div>
    </header>
    
    <div class="login-container">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <strong>Error:</strong> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Debug info -->
        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <div class="alert alert-info" role="alert">
            <strong>Debug Info:</strong><br>
            REQUEST_METHOD: <?php echo $_SERVER['REQUEST_METHOD']; ?><br>
            Email recibido: "<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"<br>
            Password recibido: <?php echo isset($_POST['password']) && !empty($_POST['password']) ? 'Presente' : 'Vacío'; ?>
        </div>
        <?php endif; ?>

        <!-- Formulario de login simplificado -->
        <form action="http://localhost:8080/plataforma-clases-online/auth/login" method="POST" id="loginForm">
            <div class="form-group">
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" value="admin@plataforma.com" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" value="admin123" required>
            </div>
            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
        </form>

    </div>
    
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
</body>
</html>