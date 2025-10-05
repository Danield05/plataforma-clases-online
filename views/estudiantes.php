<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudiantes - Plataforma de Clases Online</title>
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header>
        <h1>Estudiantes</h1>
        <?php include 'nav.php'; ?>
    </header>
    <main>
        <h2>Lista de Estudiantes</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Descripción Personal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($estudiantes as $estudiante): ?>
                    <tr>
                        <td><?php echo $estudiante['user_id']; ?></td>
                        <td><?php echo $estudiante['first_name'] . ' ' . $estudiante['last_name']; ?></td>
                        <td><?php echo $estudiante['email']; ?></td>
                        <td><?php echo $estudiante['personal_description'] ?? 'N/A'; ?></td>
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