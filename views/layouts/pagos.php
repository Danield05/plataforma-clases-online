<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagos - Plataforma de Clases Online</title>
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
            <h1 class="header-title">💰 Gestión de Pagos</h1>
            <?php include __DIR__ . '/nav.php'; ?>
        </div>
    </header>

    <main class="container my-5">
        <!-- Contenedor principal con estilo del reporte -->
        <div class="pagos-container">
            <!-- Título y botones exportar -->
            <div class="pagos-header">
                <h2 class="pagos-title">
                    <?php
                    if ($userRole === 'administrador') {
                        echo '📊 Historial de Pagos';
                    } elseif ($userRole === 'estudiante') {
                        echo '💰 Mis Pagos';
                    } elseif ($userRole === 'profesor') {
                        echo '💵 Pagos de Mis Estudiantes';
                    } else {
                        echo ' Historial de Pagos';
                    }
                    ?>
                </h2>
                <?php if ($userRole === 'administrador'): ?>
                <div class="export-buttons">
                    <a href="/plataforma-clases-online/reportes/exportarPagos"
                       class="btn btn-export-csv">
                       📄 Exportar CSV
                    </a>
                    <a href="/plataforma-clases-online/reportes/exportarPagosPDF"
                       class="btn btn-export-pdf" target="_blank">
                       📋 Ver Reporte
                    </a>
                </div>
                <?php endif; ?>
            </div>

            <!-- Resumen de totales con nuevo diseño -->
            <div class="totales-pagos">
                <?php if ($userRole === 'administrador'): ?>
                <div class="total-card-pagos success">
                    <div class="card-icon">💵</div>
                    <h4>Total Recaudado</h4>
                    <div class="amount">$<?= number_format($totalRecaudado ?? 0, 2); ?></div>
                </div>
                <div class="total-card-pagos warning">
                    <div class="card-icon">⏳</div>
                    <h4>Pagos Pendientes</h4>
                    <div class="amount"><?= $totalPendientes ?? 0; ?></div>
                </div>
                <div class="total-card-pagos info">
                    <div class="card-icon">✅</div>
                    <h4>Pagos Completados</h4>
                    <div class="amount"><?= $totalPagados ?? 0; ?></div>
                </div>
                <div class="total-card-pagos danger">
                    <div class="card-icon">❌</div>
                    <h4>Pagos Cancelados</h4>
                    <div class="amount"><?= $totalCancelados ?? 0; ?></div>
                </div>
                <?php elseif ($userRole === 'estudiante'): ?>
                <div class="total-card-pagos success">
                    <div class="card-icon">💰</div>
                    <h4>Total Invertido</h4>
                    <div class="amount">$<?= number_format($totalPagadosUsuario ?? 0, 2); ?></div>
                </div>
                <div class="total-card-pagos warning">
                    <div class="card-icon">⏳</div>
                    <h4>Pagos Pendientes</h4>
                    <div class="amount"><?php echo $clasesStats['pendientes']; ?></div>
                </div>
                <div class="total-card-pagos info">
                    <div class="card-icon">✅</div>
                    <h4>Pagos Completados</h4>
                    <div class="amount"><?php echo $clasesStats['completadas']; ?></div>
                </div>
                <div class="total-card-pagos danger">
                    <div class="card-icon">❌</div>
                    <h4>Pagos Cancelados</h4>
                    <div class="amount"><?php echo $clasesStats['canceladas']; ?></div>
                </div>
                <?php elseif ($userRole === 'profesor'): ?>
                <div class="total-card-pagos success">
                    <div class="card-icon">💵</div>
                    <h4>Ingresos Totales</h4>
                    <div class="amount">$<?= number_format($totalPagadosUsuario ?? 0, 2); ?></div>
                </div>
                <div class="total-card-pagos warning">
                    <div class="card-icon">⏳</div>
                    <h4>Pagos Pendientes</h4>
                    <div class="amount"><?php echo $clasesStats['pendientes']; ?></div>
                </div>
                <div class="total-card-pagos info">
                    <div class="card-icon">✅</div>
                    <h4>Pagos Completados</h4>
                    <div class="amount"><?php echo $clasesStats['completadas']; ?></div>
                </div>
                <div class="total-card-pagos danger">
                    <div class="card-icon">❌</div>
                    <h4>Pagos Cancelados</h4>
                    <div class="amount"><?php echo $clasesStats['canceladas']; ?></div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Mensaje informativo para estudiantes con pagos pendientes -->
            <?php if ($userRole === 'estudiante' && $clasesStats['pendientes'] > 0): ?>
            <div class="alert alert-info mt-3 mb-4">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h6 class="alert-heading mb-1">💡 Información sobre Pagos Pendientes</h6>
                        <p class="mb-0 small">
                            Los pagos pendientes aparecen cuando seleccionas "Pagar más tarde" al reservar una clase. 
                            Puedes completar el pago haciendo clic en "💳 Pagar Ahora" en la tabla de abajo.
                        </p>
                    </div>
                    <div class="col-lg-4 text-end">
                        <small class="text-muted">
                            <i class="fas fa-lightbulb"></i> Completa tus pagos para confirmar tus clases
                        </small>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Mensaje informativo para administradores -->
            <?php if ($userRole === 'administrador'): ?>
            <div class="alert alert-secondary mt-3 mb-4">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h6 class="alert-heading mb-1">👨‍💼 Panel de Administración</h6>
                        <p class="mb-0 small">
                            Como administrador, puedes ver todos los pagos del sistema y sus detalles, pero no procesar pagos. 
                            Solo los estudiantes pueden realizar pagos a través de la plataforma.
                        </p>
                    </div>
                    <div class="col-lg-4 text-end">
                        <small class="text-muted">
                            <i class="fas fa-shield-alt"></i> Vista de solo lectura
                        </small>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Tabla de pagos con nuevo diseño -->
            <div class="table-container-pagos">
                <table class="tabla-pagos-nueva">
                    <thead>
                        <tr>
                            <th>🆔 ID Pago</th>
                            <th>👤 Usuario</th>
                            <th>💰 Monto</th>
                            <th>💳 Método</th>
                            <th>📅 Fecha Pago</th>
                            <th>📅 Fecha Clase</th>
                            <th>📊 Estado</th>
                            <th>⚙️ Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($pagos as $pago): ?>
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
                            <tr>
                                <td><?= htmlspecialchars($pago['payment_id']); ?></td>
                                <td><?= htmlspecialchars($pago['first_name'] . ' ' . $pago['last_name']); ?></td>
                                <td><strong>$<?= number_format($pago['amount'], 2); ?></strong></td>
                                <td><?= htmlspecialchars($pago['payment_method']); ?></td>
                                <td><?= htmlspecialchars($pago['payment_date']); ?></td>
                                <td>
                                    <?php
                                    // Mostrar fecha y hora de clase si están disponibles
                                    if (isset($pago['class_date']) && !empty($pago['class_date'])) {
                                        $fechaClase = date('d/m/Y', strtotime($pago['class_date']));
                                        if (isset($pago['class_time']) && !empty($pago['class_time'])) {
                                            $horaClase = date('H:i', strtotime($pago['class_time']));
                                            echo htmlspecialchars($fechaClase . ' ' . $horaClase);
                                        } else {
                                            echo htmlspecialchars($fechaClase);
                                        }
                                    } else {
                                        echo '<span class="text-muted">N/A</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <span class="status-badge-pagos <?= $statusClass; ?>">
                                        <?= ucfirst($estado); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="/plataforma-clases-online/home/verPago?id=<?= $pago['payment_id']; ?>"
                                       class="btn-detalle">
                                        👁️ Ver Detalle
                                    </a>
                                    <?php if ($estado === 'pendiente' && $userRole === 'estudiante'): ?>
                                        <a href="/plataforma-clases-online/home/pagar_pendiente?payment_id=<?= $pago['payment_id']; ?>"
                                           class="btn btn-warning btn-sm ms-2">
                                            💳 Pagar Ahora
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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

