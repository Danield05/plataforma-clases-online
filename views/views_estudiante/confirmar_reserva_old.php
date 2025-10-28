<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>💳 <?php echo isset($reserva) ? 'Confirmar Reserva' : 'Completar Pago'; ?> - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
    
    <!-- PayPal SDK -->
    <script src="https://www.paypal.com/sdk/js?client-id=test&currency=USD"></script>
    
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
            color: #371783;
            font-weight: 500;
        }

        .price-total {
            background: linear-gradient(135deg, #371783 0%, #8B5A96 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 2rem;
        }

        .price-total h3 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
        }

        .payment-section {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
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
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pendiente {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
    </style>
</head>
<body>
    <?php
    $currentPage = 'reservar';
    ?>
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">💳 <?php echo isset($reserva) ? 'Confirmar Reserva' : 'Completar Pago Pendiente'; ?></h1>
            <?php include __DIR__ . '/../layouts/nav.php'; ?>
        </div>
    </header>

    <main class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="reserva-summary">
                    <h2 class="text-center mb-4">
                        <?php if (isset($reserva)): ?>
                            📋 Resumen de tu Reserva
                        <?php else: ?>
                            💳 Pago Pendiente
                        <?php endif; ?>
                    </h2>
                    
                    <!-- Información del Profesor -->
                    <div class="detail-card">
                        <h5>👨‍🏫 Información del Profesor</h5>
                        <div class="detail-row">
                            <span class="detail-label">Nombre:</span>
                            <span class="detail-value">
                                <?php 
                                if (isset($reservaData)) {
                                    echo htmlspecialchars($reservaData['profesor_nombre']);
                                } elseif (isset($reservaAsociada)) {
                                    echo htmlspecialchars($reservaAsociada['profesor_name'] . ' ' . $reservaAsociada['profesor_last_name']);
                                } else {
                                    echo 'Información no disponible';
                                }
                                ?>
                            </span>
                        </div>
                        <?php if (isset($reservaData)): ?>
                        <div class="detail-row">
                            <span class="detail-label">Email:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($reservaData['profesor_email']); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Nivel Académico:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($reservaData['academic_level'] ?? 'N/A'); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Información de la Clase o Pago -->
                    <div class="detail-card">
                        <?php if (isset($reservaData)): ?>
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
                            <span class="detail-label">Duración:</span>
                            <span class="detail-value">
                                <?php 
                                $start = new DateTime($reservaData['start_time']);
                                $end = new DateTime($reservaData['end_time']);
                                $duration = $start->diff($end);
                                echo $duration->h . ' hora(s) ' . $duration->i . ' minuto(s)';
                                ?>
                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Estado:</span>
                            <span class="detail-value">
                                <span class="status-badge status-pendiente">⏳ Pendiente de Pago</span>
                            </span>
                        </div>
                        <?php elseif (isset($reservaAsociada)): ?>
                        <h5>📅 Detalles de la Clase Asociada</h5>
                        <div class="detail-row">
                            <span class="detail-label">Fecha:</span>
                            <span class="detail-value"><?php echo date('l, d/m/Y', strtotime($reservaAsociada['class_date'])); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Horario:</span>
                            <span class="detail-value">
                                <?php echo date('H:i', strtotime($reservaAsociada['start_time'])) . ' - ' . date('H:i', strtotime($reservaAsociada['end_time'])); ?>
                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Estado de Reserva:</span>
                            <span class="detail-value">
                                <span class="status-badge status-pendiente">⏳ Pendiente de Pago</span>
                            </span>
                        </div>
                        <?php else: ?>
                        <h5>💰 Detalles del Pago</h5>
                        <div class="detail-row">
                            <span class="detail-label">Descripción:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($description); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Fecha de Creación:</span>
                            <span class="detail-value"><?php echo date('d/m/Y', strtotime($pago['payment_date'])); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Estado Actual:</span>
                            <span class="detail-value">
                                <span class="status-badge status-pending">⏳ Pendiente</span>
                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Método de Pago:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($paymentMethod); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                            <span class="detail-value">
                                <?php if (isset($reserva)): ?>
                                    <span class="status-badge status-pendiente">⏳ Pendiente de Pago</span>
                                <?php else: ?>
                                    <span class="detail-value"><?php echo htmlspecialchars($paymentMethod); ?></span>
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>

                    <!-- Total a Pagar -->
                    <div class="price-total">
                        <p class="mb-2">💰 Total a Pagar</p>
                        <h3>$<?php echo number_format($amount, 2); ?> USD</h3>
                        <small>
                            <?php if (isset($reserva)): ?>
                                Tarifa por hora del profesor
                            <?php else: ?>
                                Pago pendiente de completar
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
                        </div>

                        <!-- Botón PayPal -->
                        <div id="paypal-button-container" class="paypal-button-container"></div>

                        <!-- Opción Pagar Más Tarde -->
                        <div class="payment-option" id="later-option">
                            <input type="radio" name="payment_method" value="later" id="later-radio">
                            <label for="later-radio" class="w-100">
                                <div class="d-flex align-items-center">
                                    <span class="me-3" style="font-size: 2rem;">⏰</span>
                                    <div>
                                        <strong>Pagar Más Tarde</strong>
                                        <br>
                                        <small class="text-muted">Reserva la clase y paga después</small>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Información pago más tarde -->
                        <div id="later-payment-info" class="later-payment-section">
                            <div class="d-flex align-items-start">
                                <span class="text-warning me-2" style="font-size: 1.5rem;">⚠️</span>
                                <div>
                                    <h6 class="text-warning mb-2">Pago Más Tarde</h6>
                                    <p class="mb-2">
                                        Tu reserva se guardará con estado "Pendiente de Pago". 
                                        Podrás completar el pago desde tu panel de estudiante.
                                    </p>
                                    <p class="mb-0">
                                        <strong>Nota:</strong> La clase no estará confirmada hasta completar el pago.
                                    </p>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <button type="button" id="confirm-later-btn" class="btn btn-warning btn-lg">
                                    📝 Confirmar Reserva (Pagar Después)
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="text-center mt-4">
                        <a href="/plataforma-clases-online/home/reservar_clase?profesor_id=<?php echo (isset($reservaData) && is_array($reservaData) && isset($reservaData['profesor_id'])) ? $reservaData['profesor_id'] : ''; ?>" 
                           class="btn btn-outline-secondary btn-lg me-3">
                            ← Volver a Seleccionar Horario
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paypalOption = document.getElementById('paypal-option');
            const laterOption = document.getElementById('later-option');
            const paypalContainer = document.getElementById('paypal-button-container');
            const laterInfo = document.getElementById('later-payment-info');
            const paypalRadio = document.getElementById('paypal-radio');
            const laterRadio = document.getElementById('later-radio');
            const confirmLaterBtn = document.getElementById('confirm-later-btn');

            // Manejo de selección de método de pago
            paypalOption.addEventListener('click', function() {
                paypalRadio.checked = true;
                selectPaymentMethod('paypal');
            });

            laterOption.addEventListener('click', function() {
                laterRadio.checked = true;
                selectPaymentMethod('later');
            });

            function selectPaymentMethod(method) {
                // Remover selecciones anteriores
                document.querySelectorAll('.payment-option').forEach(option => {
                    option.classList.remove('selected');
                });

                if (method === 'paypal') {
                    paypalOption.classList.add('selected');
                    paypalContainer.classList.add('active');
                    laterInfo.classList.remove('active');
                    renderPayPalButton();
                } else if (method === 'later') {
                    laterOption.classList.add('selected');
                    laterInfo.classList.add('active');
                    paypalContainer.classList.remove('active');
                }
            }

            function renderPayPalButton() {
                // Limpiar container anterior
                paypalContainer.innerHTML = '';

                paypal.Buttons({
                    createOrder: function(data, actions) {
                        return actions.order.create({
                            purchase_units: [{
                                amount: {
                                    value: '<?php echo (isset($reservaData) && is_array($reservaData) && isset($reservaData['hourly_rate'])) ? number_format($reservaData['hourly_rate'], 2, '.', '') : '50.00'; ?>',
                                    currency_code: 'USD'
                                },
                                description: 'Clase online'
                            }]
                        });
                    },
                    onApprove: function(data, actions) {
                        return actions.order.capture().then(function(details) {
                            // Enviar datos del pago completado al servidor
                            const formData = new FormData();
                            formData.append('action', 'process_payment');
                            formData.append('reservation_id', '<?php echo (isset($reservaData) && is_array($reservaData) && isset($reservaData['reservation_id'])) ? $reservaData['reservation_id'] : ''; ?>');
                            formData.append('paypal_transaction_id', details.id);
                            formData.append('payment_method', 'paypal');
                            formData.append('amount', '<?php echo (isset($reservaData) && is_array($reservaData) && isset($reservaData['hourly_rate'])) ? $reservaData['hourly_rate'] : '50.00'; ?>');
                            formData.append('payer_email', details.payer.email_address);

                            fetch('/plataforma-clases-online/home/procesar_pago', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    window.location.href = '/plataforma-clases-online/home/pago_exitoso?reservation_id=<?php echo (isset($reservaData) && is_array($reservaData) && isset($reservaData['reservation_id'])) ? $reservaData['reservation_id'] : ''; ?>';
                                } else {
                                    alert('Error al procesar el pago: ' + data.message);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Error al procesar el pago. Contacta al soporte.');
                            });
                        });
                    },
                    onError: function(err) {
                        console.error('PayPal error:', err);
                        alert('Error en el pago con PayPal. Intenta de nuevo.');
                    }
                }).render('#paypal-button-container');
            }

            // Botón de confirmar pago más tarde
            confirmLaterBtn.addEventListener('click', function() {
                if (confirm('¿Confirmas que quieres reservar esta clase y pagar más tarde?')) {
                    const formData = new FormData();
                    formData.append('action', 'confirm_later');
                    formData.append('reservation_id', '<?php echo (isset($reservaData) && is_array($reservaData) && isset($reservaData['reservation_id'])) ? $reservaData['reservation_id'] : ''; ?>');

                    fetch('/plataforma-clases-online/home/procesar_pago', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = '/plataforma-clases-online/home/reserva_confirmada?reservation_id=<?php echo (isset($reservaData) && is_array($reservaData) && isset($reservaData['reservation_id'])) ? $reservaData['reservation_id'] : ''; ?>';
                        } else {
                            alert('Error al confirmar la reserva: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al confirmar la reserva. Intenta de nuevo.');
                    });
                }
            });
        });
    </script>
</body>
</html>