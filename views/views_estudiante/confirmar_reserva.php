<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>💳 <?php echo isset($payment_id) ? 'Completar Pago Pendiente' : 'Confirmar Reserva'; ?> - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
    
    <!-- PayPal SDK - Sandbox (Pruebas) -->
    <script src="https://www.paypal.com/sdk/js?client-id=sb&currency=USD"></script>
    
    <style>
        .reserva-summary {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .detail-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #371783;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .detail-card h5 {
            color: #371783;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #f1f3f4;
        }

        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .detail-label {
            font-weight: 600;
            color: #495057;
        }

        .detail-value {
            color: #212529;
        }

        .price-total {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 2rem;
            box-shadow: 0 8px 25px rgba(40,167,69,0.3);
        }

        .price-total h3 {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0.5rem 0;
        }

        .payment-section {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .payment-option {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .payment-option:hover {
            border-color: #371783;
            box-shadow: 0 4px 15px rgba(55, 23, 131, 0.1);
        }

        .payment-option.selected {
            border-color: #371783;
            background: rgba(55, 23, 131, 0.05);
        }

        .payment-option input[type="radio"] {
            position: absolute;
            opacity: 0;
        }

        .paypal-button-container {
            margin-top: 1rem;
            display: none;
        }

        .paypal-button-container.active {
            display: block;
        }

        .later-payment-section {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 1rem;
            display: none;
        }

        .later-payment-section.active {
            display: block;
        }

        .status-badge {
            padding: 0.35rem 0.65rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pendiente {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
    </style>
</head>
<body>
    <?php
    // Definir la página actual para el header
    $currentPage = 'reservar';
    ?>
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">💳 <?php echo isset($payment_id) ? 'Completar Pago Pendiente' : 'Confirmar Reserva'; ?></h1>
            <?php include __DIR__ . '/../layouts/nav.php'; ?>
        </div>
    </header>

    <main class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="reserva-summary">
                    <h2 class="text-center mb-4">
                        <?php if (isset($payment_id)): ?>
                            💳 Completar Pago Pendiente
                        <?php else: ?>
                            📋 Resumen de tu Reserva
                        <?php endif; ?>
                    </h2>
                    
                    <!-- Información del Profesor -->
                    <div class="detail-card">
                        <h5>👨‍🏫 Información del Profesor</h5>
                        <div class="detail-row">
                            <span class="detail-label">Nombre:</span>
                            <span class="detail-value">
                                <?php 
                                if (isset($reservaData) && $reservaData) {
                                    echo htmlspecialchars($reservaData['profesor_nombre']);
                                } elseif (isset($reservaAsociada) && $reservaAsociada) {
                                    echo htmlspecialchars($reservaAsociada['profesor_name'] . ' ' . $reservaAsociada['profesor_last_name']);
                                } else {
                                    echo 'Información no disponible';
                                }
                                ?>
                            </span>
                        </div>
                        <?php if (isset($reservaData) && $reservaData): ?>
                        <div class="detail-row">
                            <span class="detail-label">Email:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($reservaData['profesor_email'] ?? 'No disponible'); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Nivel Académico:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($reservaData['academic_level'] ?? 'N/A'); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Información de la Clase o Pago -->
                    <div class="detail-card">
                        <?php if (isset($reservaData) && $reservaData): ?>
                        <h5>📅 Detalles de la Clase</h5>
                        <div class="detail-row">
                            <span class="detail-label">Fecha:</span>
                            <span class="detail-value"><?php echo date('l, d/m/Y', strtotime($reservaData['class_date'])); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Horario:</span>
                            <span class="detail-value">
                                <?php echo date('H:i', strtotime($reservaData['start_time'])) . ' - ' . date('H:i', strtotime($reservaData['end_time'])); ?>
                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Estado:</span>
                            <span class="detail-value">
                                <span class="status-badge status-pendiente">⏳ Pendiente de Pago</span>
                            </span>
                        </div>
                        <?php elseif (isset($reservaAsociada) && $reservaAsociada): ?>
                        <h5>📅 Detalles de la Clase Asociada</h5>
                        <div class="detail-row">
                            <span class="detail-label">Fecha:</span>
                            <span class="detail-value"><?php echo date('l, d/m/Y', strtotime($reservaAsociada['class_date'])); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Horario:</span>
                            <span class="detail-value">
                                <?php 
                                if ($reservaAsociada['start_time'] && $reservaAsociada['end_time']) {
                                    echo date('H:i', strtotime($reservaAsociada['start_time'])) . ' - ' . date('H:i', strtotime($reservaAsociada['end_time']));
                                } else {
                                    echo 'Horario por confirmar';
                                }
                                ?>
                            </span>
                        </div>
                        <?php else: ?>
                        <h5>💰 Detalles del Pago</h5>
                        <div class="detail-row">
                            <span class="detail-label">Descripción:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($description ?? 'Pago pendiente'); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Método de Pago:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($paymentMethod ?? 'PayPal'); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Total a Pagar -->
                    <div class="price-total">
                        <p class="mb-2">💰 Total a Pagar</p>
                        <h3>$<?php echo number_format($amount, 2); ?> USD</h3>
                        <small>
                            <?php if (isset($payment_id)): ?>
                                Pago pendiente de completar
                            <?php else: ?>
                                Tarifa por hora del profesor
                            <?php endif; ?>
                        </small>
                    </div>

                    <!-- Sección de Pago -->
                    <div class="payment-section">
                        <h4 class="mb-3">💳 Método de Pago</h4>
                        
                        <!-- Opción PayPal -->
                        <div class="payment-option" id="paypal-option">
                            <input type="radio" name="payment_method" value="paypal" id="paypal-radio">
                            <label for="paypal-radio" class="w-100">
                                <div class="d-flex align-items-center">
                                    <img src="https://www.paypalobjects.com/webstatic/mktg/Logo/pp-logo-100px.png" alt="PayPal" width="80" class="me-3">
                                    <div>
                                        <strong>Pagar con PayPal</strong>
                                        <br>
                                        <small class="text-muted">Pago inmediato y seguro</small>
                                    </div>
                                </div>
                            </label>
                            
                            <!-- Contenedor del botón PayPal -->
                            <div id="paypal-button-container" class="paypal-button-container"></div>
                        </div>

                        <!-- Opción de Prueba (Solo para desarrollo) -->
                        <div class="payment-option" id="test-option">
                            <input type="radio" name="payment_method" value="test" id="test-radio">
                            <label for="test-radio" class="w-100">
                                <div class="d-flex align-items-center">
                                    <div class="me-3" style="font-size: 2rem;">🧪</div>
                                    <div>
                                        <strong>Pago de Prueba (Solo Desarrollo)</strong>
                                        <br>
                                        <small class="text-muted">Simula un pago exitoso sin dinero real</small>
                                    </div>
                                </div>
                            </label>
                            
                            <!-- Botón de prueba -->
                            <div id="test-payment-section" class="paypal-button-container">
                                <button type="button" id="test-payment-btn" class="btn btn-success btn-lg w-100 mt-3">
                                    🧪 Simular Pago Exitoso
                                </button>
                                <small class="text-muted d-block text-center mt-2">
                                    Esto simula un pago real para propósitos de desarrollo
                                </small>
                            </div>
                        </div>

                        <!-- Opción Pagar Más Tarde (solo para reservas nuevas) -->
                        <?php if (!isset($payment_id)): ?>
                        <div class="payment-option" id="later-option">
                            <input type="radio" name="payment_method" value="later" id="later-radio">
                            <label for="later-radio" class="w-100">
                                <div class="d-flex align-items-center">
                                    <div class="me-3" style="font-size: 2rem;">⏰</div>
                                    <div>
                                        <strong>Pagar Más Tarde</strong>
                                        <br>
                                        <small class="text-muted">Reserva la clase y paga después</small>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Información sobre pago más tarde -->
                        <div id="later-payment-info" class="later-payment-section">
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3" style="font-size: 1.5rem;">ℹ️</div>
                                <div>
                                    <strong>¿Cómo funciona "Pagar Más Tarde"?</strong>
                                </div>
                            </div>
                            <ul class="mb-3">
                                <li>Tu clase quedará reservada temporalmente</li>
                                <li>Recibirás un recordatorio para completar el pago</li>
                                <li>Puedes pagar desde tu dashboard o la sección de pagos</li>
                                <li>La clase se confirmará una vez realizado el pago</li>
                            </ul>
                            <button type="button" id="confirm-later-payment" class="btn btn-warning btn-lg w-100">
                                ⏰ Confirmar - Pagar Más Tarde
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Botones de acción -->
                    <div class="text-center mt-4">
                        <a href="<?php 
                            if (isset($payment_id)) {
                                echo '/plataforma-clases-online/home/pagos';
                            } elseif (isset($reservaData) && $reservaData) {
                                echo '/plataforma-clases-online/home/reservar_clase?profesor_id=' . $reservaData['profesor_id'];
                            } else {
                                echo '/plataforma-clases-online/home/explorar_profesores';
                            }
                        ?>" class="btn btn-secondary btn-lg">
                            ← Cancelar
                        </a>
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
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paymentOptions = document.querySelectorAll('.payment-option');
            const paypalContainer = document.getElementById('paypal-button-container');
            const testPaymentSection = document.getElementById('test-payment-section');
            const laterPaymentInfo = document.getElementById('later-payment-info');
            const confirmLaterBtn = document.getElementById('confirm-later-payment');
            const testPaymentBtn = document.getElementById('test-payment-btn');

            // Manejar selección de método de pago
            paymentOptions.forEach(option => {
                option.addEventListener('click', function() {
                    paymentOptions.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                    this.querySelector('input[type="radio"]').checked = true;

                    const value = this.querySelector('input[type="radio"]').value;
                    
                    if (value === 'paypal') {
                        paypalContainer.classList.add('active');
                        if (testPaymentSection) testPaymentSection.classList.remove('active');
                        if (laterPaymentInfo) laterPaymentInfo.classList.remove('active');
                        initPayPalButton();
                    } else if (value === 'test') {
                        if (testPaymentSection) testPaymentSection.classList.add('active');
                        paypalContainer.classList.remove('active');
                        if (laterPaymentInfo) laterPaymentInfo.classList.remove('active');
                    } else if (value === 'later') {
                        paypalContainer.classList.remove('active');
                        if (testPaymentSection) testPaymentSection.classList.remove('active');
                        if (laterPaymentInfo) laterPaymentInfo.classList.add('active');
                    }
                });
            });

            // Función para inicializar el botón de PayPal
            function initPayPalButton() {
                // Limpiar contenedor anterior
                paypalContainer.innerHTML = '';
                
                paypal.Buttons({
                    createOrder: function(data, actions) {
                        return actions.order.create({
                            purchase_units: [{
                                amount: {
                                    value: '<?php echo number_format($amount, 2, ".", ""); ?>',
                                    currency_code: 'USD'
                                },
                                description: 'Pago de clase online'
                            }]
                        });
                    },
                    onApprove: function(data, actions) {
                        return actions.order.capture().then(function(details) {
                            // Procesar el pago exitoso
                            const formData = new FormData();
                            formData.append('action', 'complete_payment');
                            formData.append('paypal_order_id', data.orderID);
                            formData.append('transaction_id', details.id);
                            <?php if (isset($payment_id)): ?>
                                formData.append('payment_id', '<?php echo $payment_id; ?>');
                            <?php elseif (isset($reservaData) && $reservaData): ?>
                                formData.append('reservation_id', '<?php echo $reservaData['reservation_id']; ?>');
                                formData.append('amount', '<?php echo $amount; ?>');
                            <?php endif; ?>

                            fetch('/plataforma-clases-online/home/procesar_pago', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    <?php if (isset($payment_id)): ?>
                                        window.location.href = '/plataforma-clases-online/home/pagos?success=payment_completed';
                                    <?php else: ?>
                                        window.location.href = '/plataforma-clases-online/home/pago_exitoso?reservation_id=' + data.reservation_id;
                                    <?php endif; ?>
                                } else {
                                    alert('Error al procesar el pago: ' + data.message);
                                }
                            });
                        });
                    },
                    onError: function(err) {
                        console.error('Error de PayPal:', err);
                        alert('Error al procesar el pago con PayPal');
                    }
                }).render('#paypal-button-container');
            }

            // Manejar "Pago de Prueba"
            if (testPaymentBtn) {
                testPaymentBtn.addEventListener('click', function() {
                    // Confirmar con el usuario
                    if (confirm('¿Simular un pago exitoso? Esto completará el pago sin usar dinero real.')) {
                        this.innerHTML = '⏳ Procesando...';
                        this.disabled = true;
                        
                        // Simular pago exitoso
                        const formData = new FormData();
                        formData.append('action', 'complete_payment');
                        formData.append('transaction_id', 'TEST_' + Date.now()); // ID de transacción simulado
                        formData.append('paypal_order_id', 'TEST_ORDER_' + Date.now());
                        
                        <?php if (isset($payment_id)): ?>
                            formData.append('payment_id', '<?php echo $payment_id; ?>');
                        <?php elseif (isset($reservaData) && $reservaData): ?>
                            formData.append('reservation_id', '<?php echo $reservaData['reservation_id']; ?>');
                            formData.append('amount', '<?php echo $amount; ?>');
                        <?php endif; ?>

                        fetch('/plataforma-clases-online/home/procesar_pago', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('¡Pago de prueba exitoso! Redirigiendo...');
                                <?php if (isset($payment_id)): ?>
                                    window.location.href = '/plataforma-clases-online/home/pagos?success=payment_completed';
                                <?php else: ?>
                                    window.location.href = '/plataforma-clases-online/home/pago_exitoso?reservation_id=' + data.reservation_id;
                                <?php endif; ?>
                            } else {
                                alert('Error al procesar el pago de prueba: ' + data.message);
                                this.innerHTML = '🧪 Simular Pago Exitoso';
                                this.disabled = false;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Error de conexión');
                            this.innerHTML = '🧪 Simular Pago Exitoso';
                            this.disabled = false;
                        });
                    }
                });
            }

            // Manejar "Pagar Más Tarde" (solo para reservas nuevas)
            <?php if (!isset($payment_id)): ?>
            if (confirmLaterBtn) {
                confirmLaterBtn.addEventListener('click', function() {
                    const formData = new FormData();
                    formData.append('action', 'pay_later');
                    <?php if (isset($reservaData) && $reservaData): ?>
                        formData.append('reservation_id', '<?php echo $reservaData['reservation_id']; ?>');
                    <?php endif; ?>

                    fetch('/plataforma-clases-online/home/procesar_pago', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = '/plataforma-clases-online/home/reserva_confirmada?reservation_id=' + data.reservation_id;
                        } else {
                            alert('Error: ' + data.message);
                        }
                    });
                });
            }
            <?php endif; ?>
        });
    </script>
</body>
</html>