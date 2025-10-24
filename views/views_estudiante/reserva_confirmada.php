<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📋 Reserva Confirmada - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
    <style>
        .confirm-container {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border-radius: 20px;
            padding: 3rem;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            margin: 2rem auto;
            max-width: 600px;
        }

        .confirm-icon {
            font-size: 5rem;
            color: #856404;
            margin-bottom: 1.5rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }

        .confirm-title {
            color: #856404;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .confirm-subtitle {
            color: #6c757d;
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 25px;
            font-weight: 600;
            color: #212529;
        }

        .btn-outline-secondary {
            border: 2px solid #6c757d;
            padding: 0.75rem 2rem;
            border-radius: 25px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <?php
    $currentPage = 'reservar';
    ?>
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">📋 Reserva Confirmada</h1>
            <?php include __DIR__ . '/../layouts/nav.php'; ?>
        </div>
    </header>

    <main class="container mt-4">
        <div class="confirm-container">
            <div class="confirm-icon">⏰</div>
            <h1 class="confirm-title">¡Reserva Guardada!</h1>
            <p class="confirm-subtitle">
                Tu reserva ha sido guardada exitosamente. Puedes completar el pago más tarde.
            </p>
            
            <div class="alert alert-warning" role="alert">
                <h5 class="alert-heading">⚠️ Pago Pendiente</h5>
                <p class="mb-2">
                    Tu reserva está en estado <strong>"Pendiente de Pago"</strong>. 
                    Para confirmar definitivamente tu clase, debes completar el pago.
                </p>
                <p class="mb-0">
                    <strong>Importante:</strong> La clase no estará confirmada hasta que se complete el pago.
                </p>
            </div>

            <div class="alert alert-info" role="alert">
                <h5 class="alert-heading">📅 ¿Cómo completar el pago?</h5>
                <ol class="text-start mb-0">
                    <li>Ve a tu <strong>Dashboard de Estudiante</strong></li>
                    <li>Busca tu reserva en la sección <strong>"Mis Reservas"</strong></li>
                    <li>Haz clic en <strong>"Completar Pago"</strong></li>
                    <li>Procesa el pago con PayPal</li>
                </ol>
            </div>

            <div class="action-buttons">
                <a href="/plataforma-clases-online/home/pagos" class="btn btn-warning">
                    💳 Ir a Completar Pago
                </a>
                <a href="/plataforma-clases-online/home/estudiante_dashboard" class="btn btn-outline-secondary">
                    🏠 Ir al Dashboard
                </a>
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
</body>
</html>