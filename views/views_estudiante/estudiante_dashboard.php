<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Estudiante - Plataforma de Clases Online</title>
    <!-- Incluir Bootstrap 5.3 CSS para un estilo consistente -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Incluir estilo personalizado -->
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css">
</head>

<body>
    <header>
        <!-- Encabezado con título y barra de navegación -->
        <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?> (Estudiante)</h1>
        <?php
        // Incluir la barra de navegación
        $nav_path = __DIR__ . '/../nav.php';
        if (file_exists($nav_path)) {
            include $nav_path;
        } else {
            echo '<p class="text-danger">Error: No se pudo cargar la barra de navegación. Verifica la ubicación de nav.php.</p>';
        }
        ?>
    </header>
    <main class="container mt-4">
        <!-- Contenedor principal con margen superior -->
        <a href="/plataforma-clases-online/auth/logout" class="btn btn-danger mb-3">Cerrar Sesión</a>

        <div class="dashboard-section card mb-3">
            <!-- Sección de reservas con estilo de tarjeta -->
            <div class="card-body">
                <h2 class="card-title">Mis Reservas</h2>
                <p class="card-text">Lista de clases reservadas.</p>
                <!-- Aquí mostrar reservas del estudiante -->
            </div>
        </div>

        <div class="dashboard-section card mb-3">
            <!-- Sección de pagos con estilo de tarjeta -->
            <div class="card-body">
                <h2 class="card-title">Mis Pagos</h2>
                <p class="card-text">Historial de pagos realizados.</p>
                <!-- Aquí mostrar historial de pagos -->
            </div>
        </div>

        <div class="dashboard-section card mb-3">
            <!-- Sección de profesores disponibles con estilo de tarjeta -->
            <div class="card-body">
                <h2 class="card-title">Profesores Disponibles</h2>
                <p class="card-text">Buscar y reservar clases con profesores.</p>
                <!-- Aquí mostrar lista de profesores para reservar -->
            </div>
        </div>

        <div class="dashboard-section card mb-3">
            <!-- Sección de reviews con estilo de tarjeta -->
            <div class="card-body">
                <h2 class="card-title">Reviews</h2>
                <a href="/plataforma-clases-online/views/create_reviews.php" class="btn btn-primary">Crear Reviews</a>
            </div>
        </div>
    </main>
    <footer class="text-center py-3">
        <!-- Pie de página con estilo centrado -->
        <p>&copy; 2023 Plataforma de Clases Online</p>
    </footer>
    <!-- Incluir Bootstrap 5.3 JS para componentes interactivos -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Incluir script personalizado -->
    <script src="/plataforma-clases-online/public/js/script.js"></script>
</body>

</html>