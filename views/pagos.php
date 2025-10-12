<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagos - Plataforma de Clases Online</title>
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header>
        <h1>Pagos</h1>
        <?php include 'nav.php'; ?>
    </header>
    <main>
        <h2>Historial de Pagos</h2>
        <table>
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
                        <td><?php echo $pago['payment_id']; ?></td>
                        <td><?php echo $pago['reservation_id']; ?></td>
                        <td>$<?php echo number_format($pago['amount'], 2); ?></td>
                        <td><?php echo $pago['payment_method']; ?></td>
                        <td><?php echo $pago['payment_date']; ?></td>
                        <td><?php echo $pago['payment_status']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
    <footer>
        <p>&copy; 2025 Plataforma de Clases Online</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/plataforma-clases-online/public/js/script.js"></script>
</body>
</html>