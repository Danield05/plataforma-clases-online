<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ℹ️ Acerca de - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php 
    // Definir la página actual para el header
    $currentPage = 'about';
    ?>
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">ℹ️ Acerca de</h1>
            <?php include 'nav.php'; ?>
        </div>
    </header>
    
    <main class="container my-5">
        <!-- Sección Hero -->
        <div class="about-hero mb-5">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="about-title">🌟 Conectando Estudiantes con Profesores</h2>
                    <p class="about-description">
                        Nuestra plataforma revoluciona la educación en línea, creando un espacio donde el conocimiento 
                        fluye libremente entre estudiantes y profesores de todo el mundo. 
                        <strong>Aprender nunca había sido tan accesible y flexible.</strong>
                    </p>
                </div>
                <div class="col-lg-4 text-center">
                    <div class="about-icon">
                        🎓
                    </div>
                </div>
            </div>
        </div>

        <!-- Características Principales -->
        <div class="features-section mb-5">
            <h3 class="section-title text-center mb-4">✨ Características Principales</h3>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">👥</div>
                        <h4>Gestión Completa</h4>
                        <p>Administra profesores y estudiantes con un sistema intuitivo y fácil de usar.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">📅</div>
                        <h4>Reservas Inteligentes</h4>
                        <p>Sistema de reservas automático que conecta disponibilidad de profesores con necesidades de estudiantes.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">💳</div>
                        <h4>Pagos Seguros</h4>
                        <p>Procesamiento de pagos confiable con múltiples métodos y reportes detallados.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">⭐</div>
                        <h4>Sistema de Reviews</h4>
                        <p>Calificaciones y comentarios que ayudan a mantener la calidad educativa.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">🕒</div>
                        <h4>Horarios Flexibles</h4>
                        <p>Disponibilidad adaptable que se ajusta a los horarios de estudiantes y profesores.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">📊</div>
                        <h4>Reportes Avanzados</h4>
                        <p>Análisis detallados y exportación de datos para mejor toma de decisiones.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nuestra Misión -->
        <div class="mission-section mb-5">
            <div class="row">
                <div class="col-lg-6">
                    <div class="mission-card">
                        <h3>🎯 Nuestra Misión</h3>
                        <p>
                            Democratizar el acceso a la educación de calidad, eliminando barreras geográficas 
                            y temporales. Creamos un ecosistema donde cada estudiante puede encontrar el 
                            profesor ideal y cada profesor puede compartir su conocimiento con quienes más lo necesiten.
                        </p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="vision-card">
                        <h3>🔮 Nuestra Visión</h3>
                        <p>
                            Ser la plataforma líder en educación en línea personalizada, reconocida por 
                            conectar talentos educativos con estudiantes ambiciosos, creando una comunidad 
                            global de aprendizaje colaborativo y crecimiento mutuo.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="stats-section">
            <h3 class="section-title text-center mb-4">📈 En Números</h3>
            <div class="row text-center">
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Profesores</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">2,000+</div>
                        <div class="stat-label">Estudiantes</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">10,000+</div>
                        <div class="stat-label">Clases Impartidas</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">4.8/5</div>
                        <div class="stat-label">Satisfacción</div>
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