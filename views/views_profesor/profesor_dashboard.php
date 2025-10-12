<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Profesor - Plataforma de Clases Online</title>
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../nav.php'; ?>
    <div class="container">
        <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?> (Profesor)</h1>
        <a href="/plataforma-clases-online/auth/logout" class="logout-btn">Cerrar Sesión</a>

        <div class="dashboard-section">
            <h2>Mis Reservas</h2>
            <!-- Aquí mostrar reservas del profesor -->
            <p>Lista de reservas asignadas.</p>
        </div>

        <div class="dashboard-section">
            <h2>Mi Disponibilidad</h2>
            <!-- Aquí mostrar disponibilidad del profesor -->
            <p>Gestión de horarios disponibles.</p>
        </div>

        <div class="dashboard-section">
            <h2>Estudiantes</h2>
            <!-- Aquí mostrar estudiantes asignados -->
            <p>Lista de estudiantes inscritos en mis clases.</p>
        </div>

        <div class="dashboard-section">
            <h2>Mis Pagos</h2>
            <!-- Aquí mostrar pagos recibidos por el profesor -->
            <?php if (!empty($pagos)): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID Pago</th>
                            <th>ID Reserva</th>
                            <th>Monto</th>
                            <th>Método de Pago</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($pagos as $pago): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($pago['payment_id']); ?></td>
                                <td><?php echo htmlspecialchars($pago['reservation_id']); ?></td>
                                <td>$<?php echo number_format($pago['amount'], 2); ?></td>
                                <td><?php echo htmlspecialchars($pago['payment_method']); ?></td>
                                <td><?php echo htmlspecialchars($pago['payment_date']); ?></td>
                                <td><?php echo htmlspecialchars($pago['payment_status']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No hay pagos registrados.</p>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/plataforma-clases-online/public/js/script.js"></script>
</body>
</html>