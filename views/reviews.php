<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews - Plataforma de Clases Online</title>
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header>
        <h1>Reviews</h1>
        <?php include 'nav.php'; ?>
    </header>
    <main>
        <h2>Comentarios y Calificaciones</h2>
        <table>
            <thead>
                <tr>
                    <th>Profesor</th>
                    <th>Estudiante</th>
                    <th>Calificación</th>
                    <th>Comentario</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($reviews as $review): ?>
                    <tr>
                        <td><?php echo $review['profesor_name']; ?></td>
                        <td><?php echo $review['estudiante_name']; ?></td>
                        <td><?php echo $review['rating']; ?>/5</td>
                        <td><?php echo $review['comment'] ?? 'Sin comentario'; ?></td>
                        <td><?php echo $review['review_date']; ?></td>
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