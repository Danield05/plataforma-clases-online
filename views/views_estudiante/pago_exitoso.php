<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>✅ Pago Exitoso - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
    <style>
        .success-container {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 20px;
            padding: 3rem;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            margin: 2rem auto;
            max-width: 600px;
        }

        .success-icon {
            font-size: 5rem;
            color: #28a745;
            margin-bottom: 1.5rem;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }

        .success-title {
            color: #28a745;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .success-subtitle {
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

        .btn-primary {
            background: linear-gradient(135deg, #371783 0%, #8B5A96 100%);
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 25px;
            font-weight: 600;
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
            <h1 class="header-title">✅ Pago Exitoso</h1>
            <?php include __DIR__ . '/../layouts/nav.php'; ?>
        </div>
    </header>

    <main class="container mt-4">
        <div class="success-container">
            <div class="success-icon">✅</div>
            <h1 class="success-title">¡Pago Completado!</h1>
            <p class="success-subtitle">
                Tu reserva ha sido confirmada y el pago se ha procesado exitosamente.
            </p>
            
            <div class="alert alert-success" role="alert">
                <h5 class="alert-heading">📧 Confirmación Enviada</h5>
                <p class="mb-0">
                    Se ha enviado un email de confirmación con todos los detalles de tu clase.
                    Tu reserva está ahora <strong>confirmada</strong> y lista.
                </p>
            </div>

            <div class="action-buttons">
                <a href="/plataforma-clases-online/home/estudiante_dashboard" class="btn btn-primary">
                    🏠 Ir al Dashboard
                </a>
                <a href="/plataforma-clases-online/home/reservas" class="btn btn-outline-secondary">
                    📅 Ver Mis Reservas
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