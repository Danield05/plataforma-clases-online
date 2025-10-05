<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Estudiante - Plataforma de Clases Online</title>
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css">
</head>
<body>
    <?php include 'nav.php'; ?>
    <div class="container">
        <h1>Bienvenido, <?php echo $_SESSION['user_name']; ?> (Estudiante)</h1>

        <div class="dashboard-section">
            <h2>Mis Reservas</h2>
            <!-- Aquí mostrar reservas del estudiante -->
            <p>Lista de clases reservadas.</p>
        </div>

        <div class="dashboard-section">
            <h2>Mis Pagos</h2>
            <!-- Aquí mostrar historial de pagos -->
            <p>Historial de pagos realizados.</p>
        </div>

        <div class="dashboard-section">
            <h2>Profesores Disponibles</h2>
            <!-- Aquí mostrar lista de profesores para reservar -->
            <p>Buscar y reservar clases con profesores.</p>
        </div>
    </div>
</body>
</html>