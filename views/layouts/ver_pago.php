<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Pago - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?= time(); ?>">
</head>
<body>
    <?php 
    // Definir la página actual para el header
    $currentPage = 'pagos';
    ?>
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">💰 Detalle del Pago</h1>
            <?php include __DIR__ . '/nav.php'; ?>
        </div>
    </header>

    <main class="container my-5">
        <!-- Contenedor principal con estilo elegante -->
        <div class="detalle-pago-container">
            <!-- Header de la vista -->
            <div class="detalle-header">
                <h2 class="detalle-title">💰 Información del Pago</h2>
                <div class="pago-id-badge">
                    Pago #<?= htmlspecialchars($pago['payment_id']); ?>
                </div>
            </div>

            <!-- Card principal con información del pago -->
            <div class="info-pago-card">
                <div class="card-body-detalle">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-icon">🆔</div>
                            <div class="info-content">
                                <div class="info-label">ID Pago</div>
                                <div class="info-value"><?= htmlspecialchars($pago['payment_id']); ?></div>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">👤</div>
                            <div class="info-content">
                                <div class="info-label">Usuario</div>
                                <div class="info-value"><?= htmlspecialchars($pago['first_name'] . ' ' . $pago['last_name']); ?></div>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">💰</div>
                            <div class="info-content">
                                <div class="info-label">Monto</div>
                                <div class="info-value monto">$<?= number_format($pago['amount'], 2); ?></div>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">💳</div>
                            <div class="info-content">
                                <div class="info-label">Método de Pago</div>
                                <div class="info-value"><?= htmlspecialchars($pago['payment_method']); ?></div>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">📅</div>
                            <div class="info-content">
                                <div class="info-label">Fecha</div>
                                <div class="info-value"><?= htmlspecialchars($pago['payment_date']); ?></div>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">📊</div>
                            <div class="info-content">
                                <div class="info-label">Estado</div>
                                <div class="info-value">
                                    <?php
                                        $estado = strtolower($pago['payment_status'] ?? 'pendiente');
                                        $statusClass = match($estado) {
                                            'pendiente' => 'status-pendiente',
                                            'completado' => 'status-pagado',
                                            'pagado' => 'status-pagado',
                                            'cancelado' => 'status-cancelado',
                                            default => 'status-pendiente'
                                        };
                                    ?>
                                    <span class="status-badge-detalle <?= $statusClass; ?>">
                                        <?= ucfirst($estado); ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($pago['transaction_id'])): ?>
                        <div class="info-item">
                            <div class="info-icon">🔗</div>
                            <div class="info-content">
                                <div class="info-label">ID Transacción</div>
                                <div class="info-value transaction-id"><?= htmlspecialchars($pago['transaction_id']); ?></div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($pago['description'])): ?>
                        <div class="info-item">
                            <div class="info-icon">📝</div>
                            <div class="info-content">
                                <div class="info-label">Descripción</div>
                                <div class="info-value description"><?= htmlspecialchars($pago['description']); ?></div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="acciones-detalle">
                <a href="/plataforma-clases-online/home/pagos" class="btn-volver">
                    ← Volver a Pagos
                </a>
                <button onclick="window.print()" class="btn-imprimir">
                    🖨️ Imprimir Detalle
                </button>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Plataforma de Clases Online</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
