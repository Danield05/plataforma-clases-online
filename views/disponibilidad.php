<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disponibilidad - Plataforma de Clases Online</title>
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header>
        <h1>Disponibilidad de Profesores</h1>
        <?php include 'nav.php'; ?>
    </header>
    <main>
        <h2>Horarios Disponibles</h2>
        <table>
            <thead>
                <tr>
                    <th>Profesor</th>
                    <th>Día</th>
                    <th>Hora Inicio</th>
                    <th>Hora Fin</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($disponibilidades as $disp): ?>
                    <tr>
                        <td><?php echo $disp['first_name'] . ' ' . $disp['last_name']; ?></td>
                        <td><?php echo $disp['day']; ?></td>
                        <td><?php echo $disp['start_time']; ?></td>
                        <td><?php echo $disp['end_time']; ?></td>
                        <td><?php echo $disp['status']; ?></td>
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