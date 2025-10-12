<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Review - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css">
</head>
<body>
    <header>
        <h1>Crear Review</h1>
        <?php
        session_start(); // Iniciar la sesión para acceder a variables de usuario
        // Incluir la barra de navegación desde el archivo nav.php
        $nav_path = __DIR__ . '/nav.php';
        if (file_exists($nav_path)) {
            include $nav_path;
        } else {
            echo '<div class="alert alert-danger">Error: No se pudo cargar la barra de navegación. Verifica que nav.php esté en el directorio views/.</div>';
        }
        ?>
    </header>
    <main class="container mt-4">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">Crear una nueva review</h2>
                <!-- Mostrar el nombre del usuario logueado -->
                <p class="card-text">Usuario: <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Estudiante'); ?></p>
                <?php
                // Verificar si existe el archivo de configuración de la base de datos
                $db_path = __DIR__ . '/../config/database.php';
                if (file_exists($db_path)) {
                    try {
                        require_once $db_path; // Incluir la configuración de la base de datos
                    } catch (Exception $e) {
                        echo '<div class="alert alert-danger">Error al cargar la configuración de la base de datos: ' . htmlspecialchars($e->getMessage()) . '</div>';
                        exit; // Terminar la ejecución si hay un error
                    }
                } else {
                    echo '<div class="alert alert-danger">Error: No se encontró database.php en ' . htmlspecialchars($db_path) . '. Por favor, verifica que el archivo esté en el directorio config/.</div>';
                    exit; // Terminar la ejecución si falta el archivo
                }

                // Verificar que la conexión a la base de datos ($pdo) esté inicializada
                global $pdo;
                if (!$pdo) {
                    echo '<div class="alert alert-danger">Error: La conexión a la base de datos no está inicializada. Verifica config/database.php.</div>';
                    exit; // Terminar la ejecución si $pdo no está definido
                }

                // Incluir los controladores y modelos necesarios
                require_once '../controllers/AuthController.php';
                require_once '../models/ReservaModel.php';
                require_once '../models/ReviewModel.php';

                // Verificar que el usuario esté autenticado y tenga el rol de estudiante
                AuthController::checkAuth();
                AuthController::checkRole(['estudiante']);

                // Instanciar los modelos para reservas y reseñas
                $reservaModel = new ReservaModel();
                $reviewModel = new ReviewModel();
                $student_user_id = $_SESSION['user_id']; // Obtener el ID del estudiante de la sesión

                // Obtener las reservas del estudiante logueado
                try {
                    $reservas = $reservaModel->getReservasByEstudiante($student_user_id);
                } catch (Exception $e) {
                    echo '<div class="alert alert-danger">Error al cargar las reservas: ' . htmlspecialchars($e->getMessage()) . '</div>';
                    $reservas = []; // Establecer reservas vacías si hay un error
                }

                // Procesar el formulario de creación de reseña si se envía
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $reservation_id = $_POST['reservation_id'] ?? ''; // ID de la reserva seleccionada
                    $rating = $_POST['rating'] ?? ''; // Calificación proporcionada
                    $comment = $_POST['comment'] ?? ''; // Comentario opcional

                    // Validar que se hayan proporcionado la reserva y la calificación
                    if (empty($reservation_id) || empty($rating)) {
                        echo '<div class="alert alert-danger">Por favor, selecciona una reserva y una calificación.</div>';
                    } else {
                        try {
                            // Obtener detalles de la reserva para verificar y obtener el ID del profesor
                            $reserva = $reservaModel->getReservaById($reservation_id);
                            if ($reserva && $reserva['student_user_id'] == $student_user_id) {
                                // Preparar los datos para la reseña
                                $data = [
                                    'reservation_id' => $reservation_id,
                                    'user_id' => $reserva['user_id'], // ID del profesor
                                    'student_user_id' => $student_user_id,
                                    'rating' => $rating,
                                    'comment' => $comment,
                                    'review_date' => date('Y-m-d H:i:s') // Fecha actual
                                ];

                                // Intentar guardar la reseña en la base de datos
                                if ($reviewModel->createReview($data)) {
                                    echo '<div class="alert alert-success">Review creada exitosamente.</div>';
                                } else {
                                    echo '<div class="alert alert-danger">Error al crear la review. Inténtalo de nuevo.</div>';
                                }
                            } else {
                                echo '<div class="alert alert-danger">Reserva inválida o no pertenece al estudiante.</div>';
                            }
                        } catch (Exception $e) {
                            echo '<div class="alert alert-danger">Error al procesar la review: ' . htmlspecialchars($e->getMessage()) . '</div>';
                        }
                    }
                }
                ?>

                <!-- Formulario para crear una nueva reseña -->
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="reservation_id" class="form-label">Seleccionar Reserva</label>
                        <select name="reservation_id" id="reservation_id" class="form-select" required>
                            <option value="">Selecciona una reserva</option>
                            <?php foreach ($reservas as $reserva): ?>
                                <option value="<?php echo htmlspecialchars($reserva['reservation_id']); ?>">
                                    Clase con <?php echo htmlspecialchars($reserva['profesor_name']); ?> - 
                                    <?php echo htmlspecialchars($reserva['class_date']); ?> 
                                    (Estado: <?php echo htmlspecialchars($reserva['reservation_status']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="rating" class="form-label">Calificación (1-5)</label>
                        <select name="rating" id="rating" class="form-select" required>
                            <option value="">Selecciona una calificación</option>
                            <option value="1">1 - Muy Mala</option>
                            <option value="2">2 - Mala</option>
                            <option value="3">3 - Regular</option>
                            <option value="4">4 - Buena</option>
                            <option value="5">5 - Excelente</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="form-label">Comentario (Opcional)</label>
                        <textarea name="comment" id="comment" class="form-control" rows="4"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Crear Review</button>
                    <a href="/plataforma-clases-online/" class="btn btn-secondary">Volver al Dashboard</a>
                </form>
            </div>
        </div>
    </main>
    <footer class="text-center py-3">
        <p>&copy; 2023 Plataforma de Clases Online</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/plataforma-clases-online/public/js/script.js"></script>
</body>
</html>