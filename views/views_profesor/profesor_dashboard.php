<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Profesor - Plataforma de Clases Online</title>
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../nav.php'; ?>
    <div class="container">
        <h1>Bienvenido, <?php echo $_SESSION['user_name']; ?> (Profesor)</h1>
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
    </div>
</body>
</html>