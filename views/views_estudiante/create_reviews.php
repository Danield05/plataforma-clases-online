<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Review - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/reviews.css">
</head>
<body>
    <header>
        <h1>Crear Review</h1>
        <?php
        if (session_status() === PHP_SESSION_NONE) {
            session_start(); // Iniciar la sesión sólo si no está iniciada
        }
        // Marcar la página actual para activar el link correspondiente en el nav
        $currentPage = $currentPage ?? 'reviews';
        // Incluir el nav existente desde views/layouts/nav.php
        $layout_nav = __DIR__ . '/../layouts/nav.php';
        if (file_exists($layout_nav)) {
            require_once $layout_nav;
        } else {
            echo '<div class="alert alert-danger">Error: No se pudo cargar la barra de navegación. Verifica views/layouts/nav.php.</div>';
        }
        ?>
    </header>
    <main class="container mt-4">
        <div class="card shadow-sm border-0" style="border-radius: 15px; background: linear-gradient(to bottom right, #ffffff, #f8f9fa);">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div class="display-6 mb-2">📝</div>
                    <h2 class="card-title fw-bold">Crear una nueva review</h2>
                    <p class="text-muted">Comparte tu experiencia y ayuda a otros estudiantes</p>
                </div>
                
                <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-3">
                    <div class="avatar-circle me-3">
                        <span class="avatar-text">
                            <?php echo substr($_SESSION['user_name'] ?? 'E', 0, 1); ?>
                        </span>
                    </div>
                    <div>
                        <h6 class="mb-1">Usuario</h6>
                        <p class="mb-0 text-primary fw-bold"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Estudiante'); ?></p>
                    </div>
                </div>

                <style>
                    .avatar-circle {
                        width: 40px;
                        height: 40px;
                        background-color: var(--bs-primary);
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }

                    .avatar-text {
                        color: white;
                        font-size: 1.2em;
                        font-weight: bold;
                    }

                    .icon-emoji {
                        display: inline-block;
                        width: 1.5em;
                        text-align: center;
                        vertical-align: middle;
                        margin-right: 0.3em;
                    }

                    .form-floating {
                        margin-bottom: 1rem;
                    }

                    .form-floating > label {
                        padding-left: 1rem;
                    }

                    .form-control:focus {
                        border-color: var(--bs-primary);
                        box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.25);
                    }

                    .select-container {
                        position: relative;
                        background-color: white;
                        border-radius: 0.375rem;
                        border: 1px solid #ced4da;
                        padding: 0.5rem;
                    }

                    .select-container:focus-within {
                        border-color: var(--bs-primary);
                        box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.25);
                    }

                    .select-label {
                        font-size: 0.875em;
                        color: #6c757d;
                        margin-bottom: 0.5rem;
                    }

                    textarea.form-control {
                        min-height: 120px;
                    }
                </style>
                <?php
                // Verificar si existe el archivo de configuración de la base de datos
                $db_path = __DIR__ . '/../../config/database.php';
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

                // Incluir los controladores y modelos necesarios usando rutas basadas en este archivo
                require_once __DIR__ . '/../../controllers/AuthController.php';
                require_once __DIR__ . '/../../models/ReservaModel.php';
                require_once __DIR__ . '/../../models/ReviewModel.php';

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

                // Obtener las reviews existentes del estudiante para listarlas
                try {
                    $reviews = $reviewModel->getReviewsByEstudiante($student_user_id);
                } catch (Exception $e) {
                    $reviews = [];
                }

                ?>

                <!-- Formulario para crear una nueva reseña -->
                <form method="POST" action="/plataforma-clases-online/home/store_review" class="needs-validation" novalidate>
                    <div class="mb-4">
                        <div class="select-container">
                            <label class="select-label" for="reservation_id">
                                <span class="icon-emoji text-primary">📅</span>Seleccionar Clase
                            </label>
                            <select name="reservation_id" id="reservation_id" class="form-select border-0 p-2" required>
                                <option value="">Elige una clase para reseñar</option>
                                <?php foreach ($reservas as $reserva): ?>
                                    <option value="<?php echo htmlspecialchars($reserva['reservation_id']); ?>">
                                        <span class="icon-emoji">👨‍🏫</span><?php echo htmlspecialchars($reserva['profesor_name']); ?> - 
                                        <span class="icon-emoji">📅</span><?php echo htmlspecialchars($reserva['class_date']); ?> 
                                        (<?php echo htmlspecialchars($reserva['reservation_status']); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="rating-container p-3 bg-light rounded-3">
                            <label class="form-label mb-3">
                                <span class="icon-emoji text-warning">⭐</span>¿Cómo calificarías la clase?
                            </label>
                            <div class="rating">
                                <input type="radio" name="rating" value="5" id="rating5" required>
                                <label for="rating5" title="Excelente"></label>
                                
                                <input type="radio" name="rating" value="4" id="rating4">
                                <label for="rating4" title="Buena"></label>
                                
                                <input type="radio" name="rating" value="3" id="rating3">
                                <label for="rating3" title="Regular"></label>
                                
                                <input type="radio" name="rating" value="2" id="rating2">
                                <label for="rating2" title="Mala"></label>
                                
                                <input type="radio" name="rating" value="1" id="rating1">
                                <label for="rating1" title="Muy Mala"></label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-floating">
                            <textarea name="comment" id="comment" class="form-control" 
                                      placeholder="Comparte tu experiencia" style="height: 120px"></textarea>
                            <label for="comment">
                                <span class="icon-emoji text-primary">💭</span>Comparte tu experiencia (Opcional)
                            </label>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-center">
                        <button type="submit" class="btn btn-primary btn-lg px-4">
                            <i class="me-2">✨</i>Crear Review
                        </button>
                        <a href="/plataforma-clases-online/" class="btn btn-outline-secondary btn-lg px-4">
                            <i class="me-2">←</i>Volver
                        </a>
                    </div>
                </form>
                
                <!-- Mostrar las reviews que el estudiante ya creó (si están disponibles en $reviews) -->
                <div class="my-5">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h3 class="mb-0">
                            <span class="text-primary">📝</span> Mis Reviews
                        </h3>
                        <span class="badge bg-primary rounded-pill"><?php echo count($reviews); ?> reviews</span>
                    </div>

                    <?php if (empty($reviews)): ?>
                        <div class="text-center py-5 bg-light rounded">
                            <div class="display-1 mb-3">📝</div>
                            <h5 class="text-muted">Aún no has creado ninguna review</h5>
                            <p class="text-muted">Tus reviews ayudan a otros estudiantes a elegir mejor</p>
                        </div>
                    <?php else: ?>
                        <div class="row g-3">
                            <?php foreach ($reviews as $r): ?>
                                <div class="col-md-6">
                                    <div class="card h-100 shadow-sm border-0 review-card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div>
                                                    <h5 class="card-title">
                                                        <span class="icon-emoji text-primary">👨‍🏫</span>
                                                        <?php echo htmlspecialchars(($r['profesor_name'] ?? '') . ' ' . ($r['profesor_last_name'] ?? '')); ?>
                                                    </h5>
                                                    <div class="stars-display mb-2">
                                                        <?php echo str_repeat('⭐', intval($r['rating'] ?? 0)); ?>
                                                    </div>
                                                </div>
                                                <small class="text-muted">
                                                    <?php 
                                                        $date = new DateTime($r['created_at'] ?? 'now');
                                                        echo $date->format('d/m/Y');
                                                    ?>
                                                </small>
                                            </div>
                                            
                                            <div class="review-comment">
                                                <?php if (!empty($r['comment'])): ?>
                                                    <p class="card-text">
                                                        <span class="icon-emoji text-muted">💭</span>
                                                        <?php echo htmlspecialchars($r['comment']); ?>
                                                    </p>
                                                <?php else: ?>
                                                    <p class="card-text text-muted fst-italic">
                                                        <span class="icon-emoji text-muted">💭</span>
                                                        Sin comentario
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <style>
                    .review-card {
                        transition: transform 0.2s ease, box-shadow 0.2s ease;
                        background: linear-gradient(to bottom right, #ffffff, #f8f9fa);
                        border-radius: 15px;
                    }

                    .review-card:hover {
                        transform: translateY(-5px);
                        box-shadow: 0 6px 15px rgba(0,0,0,0.1) !important;
                    }

                    .review-comment {
                        background-color: rgba(0,0,0,0.02);
                        border-radius: 10px;
                        padding: 1rem;
                        margin-top: 0.5rem;
                    }

                    .stars-display {
                        display: inline-block;
                        color: #ffd700;
                        font-size: 1.2em;
                        text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
                    }
                    
                    .stars-display ⭐ {
                        display: inline-block;
                        margin-right: 2px;
                    }
                </style>
            </div>
        </div>
    </main>
    <footer class="text-center py-3">
        <p>&copy; 2023 Plataforma de Clases Online</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/plataforma-clases-online/public/js/script.js"></script>
    <script>
        // Función para mostrar un tooltip con el significado de cada calificación
        document.querySelectorAll('.rating label').forEach(label => {
            label.addEventListener('mouseover', function() {
                const rating = this.getAttribute('for').replace('rating', '');
                const meanings = {
                    '5': 'Excelente',
                    '4': 'Buena',
                    '3': 'Regular',
                    '2': 'Mala',
                    '1': 'Muy Mala'
                };
                this.title = `${rating} - ${meanings[rating]}`;
            });
        });
    </script>
</body>
</html>
