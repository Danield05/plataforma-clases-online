<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas - Plataforma de Clases Online</title>
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header>
        <h1>Reservas</h1>
        <?php include 'nav.php'; ?>
    </header>
    <main>
        <h2>Lista de Reservas</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Reserva</th>
                    <th>Profesor</th>
                    <th>Estudiante</th>
                    <th>Fecha de Clase</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($reservas as $reserva): ?>
                    <tr>
                        <td><?php echo $reserva['reservation_id']; ?></td>
                        <td><?php echo $reserva['profesor_name']; ?></td>
                        <td><?php echo $reserva['estudiante_name']; ?></td>
                        <td><?php echo $reserva['class_date']; ?></td>
                        <td><?php echo $reserva['reservation_status']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
    <footer>
        <p>&copy; 2023 Plataforma de Clases Online</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/plataforma-clases-online/public/js/script.js"></script>
</body>
</html>