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
                            value: window.reservaData.hourlyRate,
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
                    formData.append('reservation_id', window.reservaData.reservationId);
                    formData.append('paypal_transaction_id', details.id);
                    formData.append('payment_method', 'paypal');
                    formData.append('amount', window.reservaData.hourlyRate);
                    formData.append('payer_email', details.payer.email_address);

                    fetch('/plataforma-clases-online/home/procesar_pago', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = '/plataforma-clases-online/home/pago_exitoso?reservation_id=' + window.reservaData.reservationId;
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
            formData.append('reservation_id', window.reservaData.reservationId);

            fetch('/plataforma-clases-online/home/procesar_pago', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '/plataforma-clases-online/home/reserva_confirmada?reservation_id=' + window.reservaData.reservationId;
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