<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🧪 Métodos de Pago de Prueba - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/info_pagos_prueba.css?v=<?php echo time(); ?>">
    <style>
        .test-info-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            border-left: 5px solid #28a745;
        }
        .method-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .method-title {
            color: #371783;
            font-weight: 600;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">🧪 Métodos de Pago de Prueba</h1>
        </div>
    </header>

    <main class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="test-info-card">
                    <h2 class="text-center mb-4">💡 Cómo Probar Pagos Sin Dinero Real</h2>
                    <p class="text-center lead">
                        Para probar la funcionalidad de pagos sin usar dinero real, tienes varias opciones:
                    </p>
                </div>

                <!-- Método 1: Pago de Prueba -->
                <div class="method-card">
                    <h3 class="method-title">🧪 Método 1: Pago Simulado (RECOMENDADO)</h3>
                    <div class="row">
                        <div class="col-md-8">
                            <p><strong>Descripción:</strong> Usa el botón "Pago de Prueba" que aparece en la página de confirmación.</p>
                            <p><strong>Ventajas:</strong></p>
                            <ul>
                                <li>✅ No requiere cuentas externas</li>
                                <li>✅ Inmediato y simple</li>
                                <li>✅ Simula el flujo completo</li>
                                <li>✅ Genera un transaction ID de prueba</li>
                            </ul>
                            <p><strong>Cómo usarlo:</strong></p>
                            <ol>
                                <li>En la página de confirmación, selecciona "Pago de Prueba"</li>
                                <li>Haz clic en "🧪 Simular Pago Exitoso"</li>
                                <li>Confirma la simulación</li>
                                <li>¡Listo! El pago se marcará como completado</li>
                            </ol>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="p-3 bg-light rounded">
                                <div style="font-size: 3rem;">🧪</div>
                                <strong>Pago de Prueba</strong>
                                <br>
                                <small class="text-muted">Método más fácil</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Método 2: PayPal Sandbox -->
                <div class="method-card">
                    <h3 class="method-title">💳 Método 2: PayPal Sandbox</h3>
                    <div class="row">
                        <div class="col-md-8">
                            <p><strong>Descripción:</strong> Usa el ambiente de pruebas oficial de PayPal.</p>
                            <p><strong>Cuentas de Prueba que puedes usar:</strong></p>
                            <div class="bg-light p-3 rounded">
                                <strong>Cuenta de Comprador (Buyer):</strong><br>
                                <code>sb-buyer@example.com</code><br>
                                <strong>Contraseña:</strong> <code>123456789</code>
                            </div>
                            <p class="mt-3"><strong>Cómo usarlo:</strong></p>
                            <ol>
                                <li>Selecciona "Pagar con PayPal"</li>
                                <li>Se abrirá la ventana de PayPal</li>
                                <li>Usa las credenciales de prueba de arriba</li>
                                <li>Completa el pago simulado</li>
                            </ol>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="p-3 bg-light rounded">
                                <img src="https://www.paypalobjects.com/webstatic/mktg/Logo/pp-logo-100px.png" alt="PayPal" width="80">
                                <br>
                                <strong>PayPal Sandbox</strong>
                                <br>
                                <small class="text-muted">Ambiente oficial</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Método 3: Pagar Más Tarde -->
                <div class="method-card">
                    <h3 class="method-title">⏰ Método 3: Pagar Más Tarde</h3>
                    <div class="row">
                        <div class="col-md-8">
                            <p><strong>Descripción:</strong> Reserva la clase y deja el pago como pendiente.</p>
                            <p><strong>Funcionalidad probada:</strong></p>
                            <ul>
                                <li>✅ Sistema de pagos pendientes</li>
                                <li>✅ Alertas en el dashboard</li>
                                <li>✅ Recordatorios de pago</li>
                                <li>✅ Gestión de estados</li>
                            </ul>
                            <p><strong>Cómo usarlo:</strong></p>
                            <ol>
                                <li>Selecciona "Pagar Más Tarde"</li>
                                <li>Confirma la reserva</li>
                                <li>Ve al dashboard para ver el pago pendiente</li>
                                <li>Usa "Pagar Ahora" cuando quieras completarlo</li>
                            </ol>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="p-3 bg-light rounded">
                                <div style="font-size: 3rem;">⏰</div>
                                <strong>Pagar Más Tarde</strong>
                                <br>
                                <small class="text-muted">Gestión de pendientes</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información adicional -->
                <div class="alert alert-info">
                    <h5>💡 Consejos para Pruebas</h5>
                    <ul class="mb-0">
                        <li><strong>Usa el Método 1 (Pago de Prueba)</strong> para pruebas rápidas y simples</li>
                        <li><strong>Usa el Método 2 (PayPal Sandbox)</strong> si quieres probar la integración completa</li>
                        <li><strong>Usa el Método 3 (Pagar Más Tarde)</strong> para probar la gestión de pagos pendientes</li>
                        <li>Todos los pagos de prueba se marcan claramente en la base de datos</li>
                        <li>Puedes verificar los resultados en la sección "Pagos" del dashboard</li>
                    </ul>
                </div>

                <div class="text-center mt-4">
                    <a href="/plataforma-clases-online/home/estudiante_dashboard" class="btn btn-primary btn-lg">
                        ← Volver al Dashboard
                    </a>
                    <a href="/plataforma-clases-online/home/reservar_clase?profesor_id=2" class="btn btn-success btn-lg ms-3">
                        🎯 Probar Reserva y Pago
                    </a>
                </div>
            </div>
        </div>
    </main>

    <footer class="modern-footer mt-5">
        <div class="footer-content">
            <div class="footer-info">
                <div class="footer-brand">
                    <span>💎</span>
                    <span>Plataforma Clases Online</span>
                </div>
            </div>
            <div class="footer-copy">
                © <?php echo date('Y'); ?> Plataforma Clases Online - Ambiente de Pruebas
            </div>
        </div>
    </footer>
</body>
</html>