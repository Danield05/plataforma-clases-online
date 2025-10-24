<?php
require_once 'controllers/AuthController.php';
class HomeController
{
    private function normalizeString($string) {
        // Convertir a minúsculas y quitar tildes
        $string = strtolower($string);
        $string = str_replace(
            ['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ'],
            ['a', 'e', 'i', 'o', 'u', 'u', 'n'],
            $string
        );
        return $string;
    }

    public function index()
    {
        // Verificar si el usuario está logueado
        if (!isset($_SESSION['user_id'])) {
            header('Location: /plataforma-clases-online/auth/login');
            exit;
        }


        // Verificar rol y mostrar dashboard correspondiente
        $role = $_SESSION['role'] ?? '';

        if ($role === 'administrador') {
            // Cargar datos para el dashboard del admin
            require_once 'models/ProfesorModel.php';
            require_once 'models/EstudianteModel.php';
            require_once 'models/ReservaModel.php';
            require_once 'models/PagoModel.php';

            $profesorModel = new ProfesorModel();
            $estudianteModel = new EstudianteModel();
            $reservaModel = new ReservaModel();
            $pagoModel = new PagoModel();

            // Obtener datos principales
            $profesores = $profesorModel->getProfesores();
            $estudiantes = $estudianteModel->getEstudiantes();
            $reservas = $reservaModel->getReservas();
            $pagos = $pagoModel->getPagos();

            // Calcular estadísticas adicionales
            $reservasActivas = count(array_filter($reservas, function($r) {
                return in_array($r['reservation_status_id'] ?? 0, [1, 2]); // 1=Pendiente, 2=Confirmada
            }));

            $ingresosMensuales = array_sum(array_filter(array_column($pagos, 'amount'), function($amount, $index) use ($pagos) {
                return isset($pagos[$index]['payment_date']) &&
                       date('Y-m', strtotime($pagos[$index]['payment_date'])) === date('Y-m') &&
                       in_array($pagos[$index]['payment_status_id'], [2, 3]); // Solo pagos completados o pagados
            }, ARRAY_FILTER_USE_BOTH));

            $data = [
                'profesores' => $profesores,
                'estudiantes' => $estudiantes,
                'reservas' => $reservas,
                'pagos' => $pagos,
                'estadisticas' => [
                    'totalProfesores' => count($profesores),
                    'totalEstudiantes' => count($estudiantes),
                    'reservasActivas' => $reservasActivas,
                    'ingresosMensuales' => $ingresosMensuales,
                    'profesoresRecientes' => array_slice($profesores, 0, 3)
                ]
            ];

            // Pasar datos a la vista
            require_once 'views/layouts/home.php';
        } elseif ($role === 'profesor') {
            $this->profesor_dashboard();
        } elseif ($role === 'estudiante') {
            $this->estudiante_dashboard();
        } else {
            // Rol desconocido, redirigir a login
            header('Location: /plataforma-clases-online/auth/login');
            exit;
        }
    }

    public function profesor_dashboard()
    {
        // Dashboard específico para profesores
        require_once 'models/ReservaModel.php';
        require_once 'models/DisponibilidadModel.php';
        require_once 'models/PagoModel.php';
        require_once 'models/ReviewModel.php';
        require_once 'models/EstudianteModel.php';

        $reservaModel = new ReservaModel();
        $disponibilidadModel = new DisponibilidadModel();
        $pagoModel = new PagoModel();
        $reviewModel = new ReviewModel();
        $estudianteModel = new EstudianteModel();

        $profesorId = $_SESSION['user_id'];

        // Obtener reservas del profesor
        $reservas = $reservaModel->getReservasByProfesor($profesorId);
        $disponibilidades = $disponibilidadModel->getDisponibilidadesByProfesor($profesorId);
        $pagos = $pagoModel->getPagosByProfesor($profesorId);

        // Calcular estadísticas
        $reservasActivas = count(array_filter($reservas, function($r) { return in_array($r['reservation_status_id'] ?? 0, [1, 2]); }));
        $estudiantesUnicos = array_unique(array_column($reservas, 'student_user_id'));
        $estudiantesTotales = count($estudiantesUnicos);

        // Calificación promedio
        $reviews = $reviewModel->getReviewsByProfesor($profesorId);
        $calificacionPromedio = !empty($reviews) ? array_sum(array_column($reviews, 'rating')) / count($reviews) : 0;

        // Ingresos del mes actual
        $mesActual = date('Y-m');
        $ingresosMes = array_sum(array_filter(array_column($pagos, 'amount'), function($amount, $index) use ($pagos, $mesActual) {
            return isset($pagos[$index]['payment_date']) && date('Y-m', strtotime($pagos[$index]['payment_date'])) === $mesActual;
        }, ARRAY_FILTER_USE_BOTH));

        // Obtener estudiantes con reservas
        $estudiantes = [];
        if (!empty($estudiantesUnicos)) {
            foreach ($estudiantesUnicos as $estId) {
                $estudiante = $estudianteModel->getEstudianteById($estId);
                if ($estudiante) {
                    $estudiantes[] = $estudiante;
                }
            }
        }

        $data = [
            'reservas' => $reservas,
            'disponibilidades' => $disponibilidades,
            'pagos' => $pagos,
            'estudiantes' => $estudiantes,
            'stats' => [
                'reservasActivas' => $reservasActivas,
                'estudiantesTotales' => $estudiantesTotales,
                'calificacionPromedio' => round($calificacionPromedio, 1),
                'ingresosMes' => $ingresosMes
            ]
        ];
        extract($data);
        require_once 'views/views_profesor/profesor_dashboard.php';
    }

    public function estudiante_dashboard()
    {
        // Dashboard específico para estudiantes
        require_once 'models/ReservaModel.php';
        require_once 'models/PagoModel.php';
        require_once 'models/ProfesorModel.php';
        require_once 'models/DisponibilidadModel.php';

        $reservaModel = new ReservaModel();
        $pagoModel = new PagoModel();
        $profesorModel = new ProfesorModel();
        $disponibilidadModel = new DisponibilidadModel();

        $userId = $_SESSION['user_id'];

        // Obtener reservas del estudiante con información detallada (sin duplicados)
        $reservas_raw = $reservaModel->getReservasByEstudianteWithDetails($userId);

        // Eliminar duplicados basados en reservation_id y limpiar datos
        $uniqueReservations = [];
        foreach ($reservas_raw as $reserva) {
            $id = $reserva['reservation_id'];
            if (!empty($id) && !isset($uniqueReservations[$id])) {
                // Asegurar que todos los campos necesarios estén presentes
                $reserva['profesor_last_name'] = $reserva['profesor_last_name'] ?? '';
                $reserva['academic_level'] = $reserva['academic_level'] ?? '';
                $reserva['hourly_rate'] = $reserva['hourly_rate'] ?? '';
                $reserva['personal_description'] = $reserva['personal_description'] ?? '';
                $reserva['notes'] = $reserva['notes'] ?? '';

                // Asegurar horarios por defecto si no están disponibles
                if (empty($reserva['start_time']) && empty($reserva['class_time'])) {
                    $reserva['start_time'] = '08:00:00';
                    $reserva['class_time'] = '08:00:00';
                }
                if (empty($reserva['end_time'])) {
                    $reserva['end_time'] = '10:00:00';
                }

                $uniqueReservations[$id] = $reserva;
            }
        }
        $reservas = array_values($uniqueReservations);

        // Obtener pagos del estudiante
        $pagos = $pagoModel->getPagosByEstudiante($userId);

        // Agregar información adicional de disponibilidad y profesor
        foreach ($reservas as &$reserva) {
            // Usar el campo class_time de la base de datos como horario principal
            if (isset($reserva['class_time']) && $reserva['class_time'] && $reserva['class_time'] != '00:00:00') {
                $reserva['start_time_formatted'] = date('H:i', strtotime($reserva['class_time']));
                $reserva['end_time_formatted'] = date('H:i', strtotime($reserva['class_time'] . ' +2 hours'));
            } elseif (isset($reserva['start_time']) && $reserva['start_time'] && $reserva['start_time'] != '0000-00-00 00:00:00') {
                $reserva['start_time_formatted'] = date('H:i', strtotime($reserva['start_time']));
                $reserva['end_time_formatted'] = date('H:i', strtotime($reserva['start_time'] . ' +2 hours'));
            } else {
                // Usar horario por defecto basado en la fecha para evitar conflictos
                $horaPorDefecto = ['08:00', '10:00', '14:00', '16:00'];
                $fechaParaHash = isset($reserva['class_date_formatted']) ? $reserva['class_date_formatted'] : $reserva['class_date'];
                $indice = $fechaParaHash ? hexdec(substr(md5($fechaParaHash), 0, 1)) % 4 : 0;
                $reserva['start_time_formatted'] = $horaPorDefecto[$indice];
                $reserva['end_time_formatted'] = date('H:i', strtotime($horaPorDefecto[$indice] . ' +2 hours'));
            }

            // Agregar información adicional del profesor
            if (isset($reserva['profesor_user_id'])) {
                $profesor = $profesorModel->getProfesorById($reserva['profesor_user_id']);
                if ($profesor) {
                    $reserva['profesor_email'] = $profesor['email'];
                    $reserva['profesor_description'] = $profesor['personal_description'];
                    $reserva['profesor_academic_level'] = $profesor['academic_level'];
                    $reserva['profesor_hourly_rate'] = $profesor['hourly_rate'];
                }
            }

            // Formatear fecha para mejor manejo en JavaScript
            if (isset($reserva['class_date']) && $reserva['class_date'] != '0000-00-00' && !empty($reserva['class_date'])) {
                $reserva['class_date_formatted'] = date('Y-m-d', strtotime($reserva['class_date']));
                $reserva['class_date_display'] = date('d/m/Y', strtotime($reserva['class_date']));
            } else {
                // Si no hay fecha válida, usar fecha actual
                $reserva['class_date_formatted'] = date('Y-m-d');
                $reserva['class_date_display'] = date('d/m/Y');
            }

            // Debug: Agregar información para verificar datos
            if (empty($reserva['class_date_formatted'])) {
                error_log("Reserva sin fecha formateada: " . print_r($reserva, true));
            }

            // Log para verificar horarios
            error_log("Reserva {$reserva['reservation_id']}: class_time={$reserva['class_time']}, class_date={$reserva['class_date']}, start_time_formatted={$reserva['start_time_formatted']}");

            // Formatear horarios para mostrar correctamente (asegurarse de que siempre haya valores)
            if (isset($reserva['class_time']) && $reserva['class_time'] && $reserva['class_time'] != '00:00:00') {
                $reserva['start_time_formatted'] = date('H:i', strtotime($reserva['class_time']));
                $reserva['end_time_formatted'] = date('H:i', strtotime($reserva['class_time'] . ' +2 hours'));
            } elseif (isset($reserva['start_time']) && $reserva['start_time'] && $reserva['start_time'] != '0000-00-00 00:00:00') {
                $reserva['start_time_formatted'] = date('H:i', strtotime($reserva['start_time']));
                $reserva['end_time_formatted'] = date('H:i', strtotime($reserva['start_time'] . ' +2 hours'));
            } else {
                // Usar horario por defecto basado en la fecha para evitar conflictos
                $horaPorDefecto = ['08:00', '10:00', '14:00', '16:00'];
                $fechaParaHash = isset($reserva['class_date_formatted']) ? $reserva['class_date_formatted'] : $reserva['class_date'];
                $indice = $fechaParaHash ? hexdec(substr(md5($fechaParaHash), 0, 1)) % 4 : 0;
                $reserva['start_time_formatted'] = $horaPorDefecto[$indice];
                $reserva['end_time_formatted'] = date('H:i', strtotime($horaPorDefecto[$indice] . ' +2 hours'));
            }

            // Asegurar que todos los campos necesarios estén disponibles
            $reserva['reservation_id'] = $reserva['reservation_id'] ?? '';
            $reserva['profesor_last_name'] = $reserva['profesor_last_name'] ?? '';

            // Log para verificar horarios
            error_log("Reserva {$reserva['reservation_id']}: class_time={$reserva['class_time']}, class_date={$reserva['class_date']}, start_time_formatted={$reserva['start_time_formatted']}");
        }

        // Calcular estadísticas
        $clasesReservadas = count($reservas);
        $clasesCompletadas = count(array_filter($reservas, function($r) { return $r['reservation_status'] === 'completada'; }));
        $profesoresActivos = count(array_unique(array_column($reservas, 'user_id')));

        // Calcular total invertido solo de pagos completados
        $totalInvertido = array_sum(array_filter(array_column($pagos, 'amount'), function($amount, $index) use ($pagos) {
            return in_array(strtolower($pagos[$index]['payment_status'] ?? ''), ['completado', 'pagado']);
        }, ARRAY_FILTER_USE_BOTH));

        // Obtener profesores disponibles
        $profesores = $profesorModel->getProfesores();

        $data = [
            'reservas' => $reservas,
            'pagos' => $pagos,
            'profesores' => $profesores,
            'stats' => [
                'clasesReservadas' => $clasesReservadas,
                'clasesCompletadas' => $clasesCompletadas,
                'profesoresActivos' => $profesoresActivos,
                'totalInvertido' => $totalInvertido
            ]
        ];

        extract($data);
        require_once 'views/views_estudiante/estudiante_dashboard.php';
    }

    public function profesores()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador']);

        require_once 'models/ProfesorModel.php';
        $profesorModel = new ProfesorModel();
        $profesores = $profesorModel->getProfesores();
        require_once 'views/layouts/profesores.php';
    }

    // Información sobre métodos de pago de prueba
    public function info_pagos_prueba() {
        require_once 'views/info_pagos_prueba.php';
    }

    // Mostrar formulario para crear profesor
    public function profesores_create() {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador']);

        $showForm = true;
        require_once 'views/layouts/profesores.php';
    }

    // Almacenar profesor (POST)
    public function profesores_store() {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /plataforma-clases-online/home/profesores');
            exit;
        }

        require_once 'models/UserModel.php';
        require_once 'models/ProfesorModel.php';

        $userModel = new UserModel();
        $profesorModel = new ProfesorModel();

        $dataUser = [
            'role_id' => 2,
            'user_status_id' => 1,
            'first_name' => $_POST['first_name'] ?? '',
            'last_name' => $_POST['last_name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'personal_description' => $_POST['personal_description'] ?? null,
            'academic_level' => $_POST['academic_level'] ?? null,
            'hourly_rate' => $_POST['hourly_rate'] ?? null,
        ];

        $created = $userModel->createUser($dataUser);
        $msg = 'error';
        if ($created) {
            global $pdo;
            $userId = $pdo->lastInsertId();
            // createUser already creates profesor row but ensure data updated
            $profData = [
                'personal_description' => $dataUser['personal_description'],
                'academic_level' => $dataUser['academic_level'],
                'hourly_rate' => $dataUser['hourly_rate'],
            ];
            $ok = $profesorModel->updateProfesor($userId, $profData);
            $msg = $ok ? 'created' : 'created';
        }

        header('Location: /plataforma-clases-online/home/profesores?status=' . $msg);
        exit;
    }

    // Editar profesor (mostrar formulario)
    public function profesores_edit() {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador']);

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /plataforma-clases-online/home/profesores');
            exit;
        }

        require_once 'models/ProfesorModel.php';
        require_once 'models/UserModel.php';
        $profesorModel = new ProfesorModel();
        $userModel = new UserModel();

        $profesor = $profesorModel->getProfesorById($id);
        $user = $userModel->getUserById($id);
        $showForm = true;
        require_once 'views/layouts/profesores.php';
    }

    // Actualizar profesor (POST)
    public function profesores_update() {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /plataforma-clases-online/home/profesores');
            exit;
        }

        $id = $_POST['user_id'] ?? null;
        if (!$id) {
            header('Location: /plataforma-clases-online/home/profesores?status=error');
            exit;
        }

        require_once 'models/UserModel.php';
        require_once 'models/ProfesorModel.php';
        $userModel = new UserModel();
        $profesorModel = new ProfesorModel();

        $userData = [
            'first_name' => $_POST['first_name'] ?? '',
            'last_name' => $_POST['last_name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'photo_url' => null,
        ];
        $profData = [
            'personal_description' => $_POST['personal_description'] ?? null,
            'academic_level' => $_POST['academic_level'] ?? null,
            'hourly_rate' => $_POST['hourly_rate'] ?? null,
        ];

        $ok1 = $userModel->updateUser($id, $userData);
        $ok2 = $profesorModel->updateProfesor($id, $profData);
        $msg = ($ok1 || $ok2) ? 'updated' : 'error';

        header('Location: /plataforma-clases-online/home/profesores?status=' . $msg);
        exit;
    }

    // Eliminar profesor
    public function profesores_delete() {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador']);

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /plataforma-clases-online/home/profesores');
            exit;
        }

        require_once 'models/UserModel.php';
        $userModel = new UserModel();
        $ok = $userModel->deleteUser($id);
        $msg = $ok ? 'deleted' : 'error';
        header('Location: /plataforma-clases-online/home/profesores?status=' . $msg);
        exit;
    }

    public function estudiantes()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador', 'profesor']);

        require_once 'models/EstudianteModel.php';
        $estudianteModel = new EstudianteModel();

        // Si es profesor, mostrar solo sus estudiantes
        if ($_SESSION['role'] === 'profesor') {
            require_once 'models/ReservaModel.php';
            $reservaModel = new ReservaModel();

            $reservas = $reservaModel->getReservasByProfesor($_SESSION['user_id']);
            $estudiantesUnicos = array_unique(array_column($reservas, 'student_user_id'));

            $estudiantes = [];
            if (!empty($estudiantesUnicos)) {
                foreach ($estudiantesUnicos as $estId) {
                    $estudiante = $estudianteModel->getEstudianteById($estId);
                    if ($estudiante) {
                        $estudiantes[] = $estudiante;
                    }
                }
            }
        } else {
            // Administrador ve todos los estudiantes
            $estudiantes = $estudianteModel->getEstudiantes();
        }

        require_once 'views/layouts/estudiantes.php';
    }

    // Mostrar formulario para crear estudiante
    public function estudiantes_create() {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador']);

        $showForm = true;
        require_once 'views/layouts/estudiantes.php';
    }

    // Almacenar estudiante (POST)
    public function estudiantes_store() {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /plataforma-clases-online/home/estudiantes');
            exit;
        }

        require_once 'models/UserModel.php';
        require_once 'models/EstudianteModel.php';

        $userModel = new UserModel();
        $estudianteModel = new EstudianteModel();

        $dataUser = [
            'role_id' => 3,
            'user_status_id' => 1,
            'first_name' => $_POST['first_name'] ?? '',
            'last_name' => $_POST['last_name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'personal_description' => $_POST['personal_description'] ?? null,
        ];

        $created = $userModel->createUser($dataUser);
        $msg = 'error';
        if ($created) {
            $msg = 'created';
        }

        header('Location: /plataforma-clases-online/home/estudiantes?status=' . $msg);
        exit;
    }

    // Editar estudiante (mostrar formulario)
    public function estudiantes_edit() {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador']);

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /plataforma-clases-online/home/estudiantes');
            exit;
        }

        require_once 'models/EstudianteModel.php';
        require_once 'models/UserModel.php';
        $estudianteModel = new EstudianteModel();
        $userModel = new UserModel();

        $estudiante = $estudianteModel->getEstudianteById($id);
        $user = $userModel->getUserById($id);
        $showForm = true;
        require_once 'views/layouts/estudiantes.php';
    }

    // Actualizar estudiante (POST)
    public function estudiantes_update() {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /plataforma-clases-online/home/estudiantes');
            exit;
        }

        $id = $_POST['user_id'] ?? null;
        if (!$id) {
            header('Location: /plataforma-clases-online/home/estudiantes?status=error');
            exit;
        }

        require_once 'models/UserModel.php';
        require_once 'models/EstudianteModel.php';
        $userModel = new UserModel();
        $estudianteModel = new EstudianteModel();

        $userData = [
            'first_name' => $_POST['first_name'] ?? '',
            'last_name' => $_POST['last_name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'photo_url' => null,
        ];
        $estData = [
            'personal_description' => $_POST['personal_description'] ?? null,
        ];

        $ok1 = $userModel->updateUser($id, $userData);
        $ok2 = $estudianteModel->updateEstudiante($id, $estData);
        $msg = ($ok1 || $ok2) ? 'updated' : 'error';

        header('Location: /plataforma-clases-online/home/estudiantes?status=' . $msg);
        exit;
    }

    // Eliminar estudiante
    public function estudiantes_delete() {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador']);

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /plataforma-clases-online/home/estudiantes');
            exit;
        }

        require_once 'models/UserModel.php';
        $userModel = new UserModel();
        $ok = $userModel->deleteUser($id);
        $msg = $ok ? 'deleted' : 'error';
        header('Location: /plataforma-clases-online/home/estudiantes?status=' . $msg);
        exit;
    }
    // Obtener reservas del usuario en formato JSON para el calendario
    public function calendario()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['estudiante', 'profesor']);

        require_once 'models/ReservaModel.php';
        $reservaModel = new ReservaModel();

        $userId = $_SESSION['user_id'];
        $role = $_SESSION['role'];

        // Obtener reservas según el rol
        if ($role === 'estudiante') {
            $reservas_raw = $reservaModel->getReservasByEstudianteWithDetails($userId);
        } elseif ($role === 'profesor') {
            $reservas_raw = $reservaModel->getReservasByProfesor($userId);
        } else {
            $reservas_raw = [];
        }

        // Eliminar duplicados basados en reservation_id
        $uniqueReservations = [];
        foreach ($reservas_raw as $reserva) {
            $id = $reserva['reservation_id'];
            if (!empty($id) && !isset($uniqueReservations[$id])) {
                // Asegurar que todos los campos necesarios estén presentes
                $reserva['profesor_last_name'] = $reserva['profesor_last_name'] ?? '';
                $reserva['academic_level'] = $reserva['academic_level'] ?? '';
                $reserva['hourly_rate'] = $reserva['hourly_rate'] ?? '';
                $reserva['personal_description'] = $reserva['personal_description'] ?? '';
                $reserva['notes'] = $reserva['notes'] ?? '';

                // Asegurar horarios por defecto si no están disponibles
                if (empty($reserva['start_time']) && empty($reserva['class_time'])) {
                    $reserva['start_time'] = '08:00:00';
                    $reserva['class_time'] = '08:00:00';
                }
                if (empty($reserva['end_time'])) {
                    $reserva['end_time'] = '10:00:00';
                }

                // Formatear fecha para mejor manejo en JavaScript
                if (isset($reserva['class_date']) && $reserva['class_date'] != '0000-00-00' && !empty($reserva['class_date'])) {
                    $reserva['class_date_formatted'] = date('Y-m-d', strtotime($reserva['class_date']));
                    $reserva['class_date_display'] = date('d/m/Y', strtotime($reserva['class_date']));
                } else {
                    // Si no hay fecha válida, usar fecha actual
                    $reserva['class_date_formatted'] = date('Y-m-d');
                    $reserva['class_date_display'] = date('d/m/Y');
                }

                // Formatear horarios para mostrar correctamente
                if (isset($reserva['class_time']) && $reserva['class_time'] && $reserva['class_time'] != '00:00:00') {
                    $reserva['start_time_formatted'] = date('H:i', strtotime($reserva['class_time']));
                    $reserva['end_time_formatted'] = date('H:i', strtotime($reserva['class_time'] . ' +2 hours'));
                } elseif (isset($reserva['start_time']) && $reserva['start_time'] && $reserva['start_time'] != '0000-00-00 00:00:00') {
                    $reserva['start_time_formatted'] = date('H:i', strtotime($reserva['start_time']));
                    $reserva['end_time_formatted'] = date('H:i', strtotime($reserva['start_time'] . ' +2 hours'));
                } else {
                    // Usar horario por defecto basado en la fecha para evitar conflictos
                    $horaPorDefecto = ['08:00', '10:00', '14:00', '16:00'];
                    $fechaParaHash = isset($reserva['class_date_formatted']) ? $reserva['class_date_formatted'] : $reserva['class_date'];
                    $indice = $fechaParaHash ? hexdec(substr(md5($fechaParaHash), 0, 1)) % 4 : 0;
                    $reserva['start_time_formatted'] = $horaPorDefecto[$indice];
                    $reserva['end_time_formatted'] = date('H:i', strtotime($horaPorDefecto[$indice] . ' +2 hours'));
                }

                $uniqueReservations[$id] = $reserva;
            }
        }
        $reservas = array_values($uniqueReservations);

        // Preparar datos para JavaScript
        $reservasJS = [];
        foreach ($reservas as $reserva) {
            if ($role === 'estudiante') {
                $reservasJS[] = [
                    'reservation_id' => $reserva['reservation_id'] ?? '',
                    'fecha' => $reserva['class_date_formatted'] ?? '',
                    'fecha_display' => $reserva['class_date_display'] ?? '',
                    'profesor_name' => $reserva['profesor_name'] . ' ' . $reserva['profesor_last_name'],
                    'start_time' => $reserva['start_time_formatted'] ?? '08:00',
                    'end_time' => $reserva['end_time_formatted'] ?? '10:00',
                    'reservation_status' => strtolower($reserva['reservation_status'] ?? 'pendiente'),
                    'academic_level' => $reserva['academic_level'] ?? '',
                    'hourly_rate' => $reserva['hourly_rate'] ?? '',
                    'notes' => $reserva['notes'] ?? ''
                ];
            } elseif ($role === 'profesor') {
                $reservasJS[] = [
                    'reservation_id' => $reserva['reservation_id'] ?? '',
                    'fecha' => $reserva['class_date_formatted'] ?? '',
                    'fecha_display' => $reserva['class_date_display'] ?? '',
                    'estudiante_name' => $reserva['estudiante_name'] ?? '',
                    'start_time' => $reserva['start_time_formatted'] ?? '08:00',
                    'end_time' => $reserva['end_time_formatted'] ?? '10:00',
                    'reservation_status' => strtolower($reserva['reservation_status'] ?? 'pendiente'),
                    'academic_level' => $reserva['academic_level'] ?? '',
                    'hourly_rate' => $reserva['hourly_rate'] ?? '',
                    'notes' => $reserva['notes'] ?? ''
                ];
            }
        }

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'reservas' => $reservasJS
        ]);
        exit;
    }

    public function reservas()
    {
        // Verificar autenticación manualmente para debug
        if (!isset($_SESSION['user_id'])) {
            error_log("No hay sesión activa en reservas()");
            header('Location: /plataforma-clases-online/auth/login');
            exit;
        }

        AuthController::checkAuth();
        AuthController::checkRole(['administrador', 'profesor', 'estudiante']);

        // Debug: Log de información de sesión
        error_log("Sesión en reservas(): user_id=" . ($_SESSION['user_id'] ?? 'no definido') . ", role=" . ($_SESSION['role'] ?? 'no definido'));

        require_once 'models/ReservaModel.php';
        $reservaModel = new ReservaModel();

        // Filtrar reservas según el rol del usuario
        if ($_SESSION['role'] === 'estudiante') {
            $reservas = $reservaModel->getReservasByEstudianteWithDetails($_SESSION['user_id']);
        } elseif ($_SESSION['role'] === 'profesor') {
            $reservas = $reservaModel->getReservasByProfesor($_SESSION['user_id']);
        } else {
            // Administrador ve todas las reservas
            $reservas = $reservaModel->getReservas();
        }

        // Eliminar duplicados basados en reservation_id
        $uniqueReservations = [];
        foreach ($reservas as $reserva) {
            $id = $reserva['reservation_id'];
            if (!empty($id) && !isset($uniqueReservations[$id])) {
                $uniqueReservations[$id] = $reserva;
            }
        }
        $reservas = array_values($uniqueReservations);

        // Pasar información a la vista
        $data = compact('reservas');

        // Debug: Agregar información de sesión para troubleshooting
        $data['debug_session'] = [
            'user_id' => $_SESSION['user_id'] ?? 'no definido',
            'role' => $_SESSION['role'] ?? 'no definido',
            'session_exists' => isset($_SESSION['user_id'])
        ];

        extract($data);

        require_once 'views/layouts/reservas.php';
    }

    public function disponibilidad()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador', 'profesor']);

        require_once 'models/DisponibilidadModel.php';
        $disponibilidadModel = new DisponibilidadModel();

        // Si es profesor, mostrar solo sus disponibilidades
        if ($_SESSION['role'] === 'profesor') {
            $disponibilidades = $disponibilidadModel->getDisponibilidadesByProfesor($_SESSION['user_id']);
        } else {
            // Administrador ve todas las disponibilidades
            $disponibilidades = $disponibilidadModel->getDisponibilidades();
        }

        require_once 'views/layouts/disponibilidad.php';
    }

    // Mostrar formulario para crear disponibilidad
    public function disponibilidad_create() {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador','profesor']);

        require_once 'models/DiaSemanaModel.php';
        require_once 'models/EstadoReservaModel.php';
        require_once 'models/ProfesorModel.php';

        $diasModel = new DiaSemanaModel();
        $estadosModel = new EstadoReservaModel();
        $profesorModel = new ProfesorModel();

        $dias = $diasModel->getDiasSemana();
        $estados = $estadosModel->getEstadosReserva();
        $profesores = $profesorModel->getProfesores();

        // Si es profesor, filtrar solo su propio perfil
        if ($_SESSION['role'] === 'profesor') {
            $profesores = array_filter($profesores, function($p) {
                return $p['user_id'] == $_SESSION['user_id'];
            });
        }

        $showForm = true;
        require_once 'views/layouts/disponibilidad.php';
    }

    // Almacenar disponibilidad (POST)
    public function disponibilidad_store() {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador','profesor']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /plataforma-clases-online/home/disponibilidad');
            exit;
        }

        require_once 'models/DisponibilidadModel.php';
        $disModel = new DisponibilidadModel();

        // Si es profesor, forzar que use su propio user_id
        $userId = ($_SESSION['role'] === 'profesor') ? $_SESSION['user_id'] : ($_POST['user_id'] ?? $_SESSION['user_id']);

        $data = [
            'user_id' => $userId,
            'week_day_id' => $_POST['week_day_id'] ?? null,
            'reservation_status_id' => $_POST['reservation_status_id'] ?? null,
            'start_time' => $_POST['start_time'] ?? null,
            'end_time' => $_POST['end_time'] ?? null,
        ];

        $ok = $disModel->createDisponibilidad($data);
        $msg = $ok ? 'created' : 'error';
        header('Location: /plataforma-clases-online/home/disponibilidad?status=' . $msg);
        exit;
    }

    // Editar disponibilidad (mostrar formulario)
    public function disponibilidad_edit() {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador','profesor']);

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /plataforma-clases-online/home/disponibilidad');
            exit;
        }

        require_once 'models/DisponibilidadModel.php';
        require_once 'models/DiaSemanaModel.php';
        require_once 'models/EstadoReservaModel.php';
        require_once 'models/ProfesorModel.php';

        $disModel = new DisponibilidadModel();
        $diasModel = new DiaSemanaModel();
        $estadosModel = new EstadoReservaModel();
        $profesorModel = new ProfesorModel();

        $disponibilidad = $disModel->getDisponibilidadById($id);
        $dias = $diasModel->getDiasSemana();
        $estados = $estadosModel->getEstadosReserva();
        $profesores = $profesorModel->getProfesores();

        $showForm = true;
        require_once 'views/layouts/disponibilidad.php';
    }

    // Actualizar disponibilidad (POST)
    public function disponibilidad_update() {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador','profesor']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /plataforma-clases-online/home/disponibilidad');
            exit;
        }

        $id = $_POST['availability_id'] ?? null;
        if (!$id) {
            header('Location: /plataforma-clases-online/home/disponibilidad?status=error');
            exit;
        }

        require_once 'models/DisponibilidadModel.php';
        $disModel = new DisponibilidadModel();

        $data = [
            'week_day_id' => $_POST['week_day_id'] ?? null,
            'reservation_status_id' => $_POST['reservation_status_id'] ?? null,
            'start_time' => $_POST['start_time'] ?? null,
            'end_time' => $_POST['end_time'] ?? null,
        ];

        $ok = $disModel->updateDisponibilidad($id, $data);
        $msg = $ok ? 'updated' : 'error';
        header('Location: /plataforma-clases-online/home/disponibilidad?status=' . $msg);
        exit;
    }

    // Eliminar disponibilidad
    public function disponibilidad_delete() {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador','profesor']);

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /plataforma-clases-online/home/disponibilidad');
            exit;
        }

        require_once 'models/DisponibilidadModel.php';
        $disModel = new DisponibilidadModel();
        $ok = $disModel->deleteDisponibilidad($id);
        $msg = $ok ? 'deleted' : 'error';
        header('Location: /plataforma-clases-online/home/disponibilidad?status=' . $msg);
        exit;
    }

    public function pagos()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador', 'estudiante', 'profesor']);

        require_once 'models/PagoModel.php';
        require_once 'models/ReservaModel.php';
        $pagoModel = new PagoModel();
        $reservaModel = new ReservaModel();

        // Filtrar pagos según el rol del usuario
        if ($_SESSION['role'] === 'profesor') {
            $pagos = $pagoModel->getPagosByProfesor($_SESSION['user_id']);
            $reservas = $reservaModel->getReservasByProfesor($_SESSION['user_id']);
        } elseif ($_SESSION['role'] === 'estudiante') {
            $pagos = $pagoModel->getPagosByEstudiante($_SESSION['user_id']);
            $reservas = $reservaModel->getReservasByEstudiante($_SESSION['user_id']);
        } else {
            // Administrador ve todos los pagos
            $pagos = $pagoModel->getPagos();
            $reservas = $reservaModel->getReservas();
        }

        // Calcular totales específicos del usuario según su rol
        $totalUsuarioEspecifico = 0;
        
        // Calcular estadísticas de clases según el rol del usuario
        // Para estudiantes y profesores, contamos por estados de PAGOS, no de reservas
        $clasesPendientes = 0;
        $clasesCompletadas = 0;
        $clasesCanceladas = 0;

        if ($_SESSION['role'] === 'estudiante' || $_SESSION['role'] === 'profesor') {
            foreach ($pagos as $pago) {
                $status = strtolower($pago['payment_status'] ?? 'pendiente');
                if ($status === 'pendiente') {
                    $clasesPendientes++;
                } elseif ($status === 'completado' || $status === 'pagado') {
                    $clasesCompletadas++;
                    // Sumar al total específico del usuario solo los pagos completados
                    $totalUsuarioEspecifico += floatval($pago['amount']);
                } elseif ($status === 'cancelado') {
                    $clasesCanceladas++;
                }
            }
        }

        // Calcular totales específicos para administradores
        if ($_SESSION['role'] === 'administrador') {
            // Obtener estadísticas desde el método existente
            $estadisticasAdmin = $pagoModel->getTotales();
            
            $totalPendientes = $estadisticasAdmin['totalPendientes'] ?? 0;
            $totalPagados = $estadisticasAdmin['totalPagados'] ?? 0;
            $totalCancelados = $estadisticasAdmin['totalCancelados'] ?? 0;
            $totalRecaudado = $estadisticasAdmin['totalRecaudado'] ?? 0;
        } else {
            // Para usuarios específicos, usar los conteos calculados arriba
            $totalPendientes = $clasesPendientes;
            $totalPagados = $clasesCompletadas;
            $totalCancelados = $clasesCanceladas;
            $totalRecaudado = $totalUsuarioEspecifico;
        }

        // Pasar el rol del usuario y estadísticas de clases a la vista
        $userRole = $_SESSION['role'];
        $totalPagadosUsuario = $totalUsuarioEspecifico; // Total específico del usuario actual
        $clasesStats = [
            'pendientes' => $clasesPendientes,
            'completadas' => $clasesCompletadas,
            'canceladas' => $clasesCanceladas
        ];
        require_once 'views/layouts/pagos.php';
    }
    public function verPago()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador', 'estudiante']);

        if (!isset($_GET['id'])) {
            header('Location: /plataforma-clases-online/home/pagos');
            exit;
        }

        $id = $_GET['id'];
        require_once 'models/PagoModel.php';
        $pagoModel = new PagoModel();
        $pago = $pagoModel->getPagoById($id);

        if (!$pago) {
            echo "<p>Pago no encontrado.</p>";
            return;
        }

        require_once 'views/layouts/ver_pago.php'; // Nueva vista para detalle
    }

    public function reviews()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador']);

        require_once 'models/ReviewModel.php';
        $reviewModel = new ReviewModel();
        $reviews = $reviewModel->getReviews();
        require_once 'views/layouts/reviews.php';
    }

    public function perfil_edit()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['profesor', 'estudiante']);

        $role = $_SESSION['role'];
        require_once 'models/UserModel.php';
        $userModel = new UserModel();
        $user = $userModel->getUserById($_SESSION['user_id']);

        if ($role === 'profesor') {
            require_once 'models/ProfesorModel.php';
            $profesorModel = new ProfesorModel();
            $profesor = $profesorModel->getProfesorById($_SESSION['user_id']);
            $data = ['user' => $user, 'profesor' => $profesor];
            require_once 'views/views_profesor/perfil_edit.php';
        } elseif ($role === 'estudiante') {
            require_once 'models/EstudianteModel.php';
            $estudianteModel = new EstudianteModel();
            $estudiante = $estudianteModel->getEstudianteById($_SESSION['user_id']);
            $data = ['user' => $user, 'estudiante' => $estudiante];
            require_once 'views/views_estudiante/perfil_edit.php';
        }
    }

    public function perfil_update()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['profesor', 'estudiante']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /plataforma-clases-online/home/perfil_edit');
            exit;
        }

        $role = $_SESSION['role'];
        require_once 'models/UserModel.php';
        $userModel = new UserModel();

        $userData = [
            'first_name' => $_POST['first_name'] ?? '',
            'last_name' => $_POST['last_name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'photo_url' => $_POST['photo_url'] ?? null,
        ];

        if (!empty($_POST['password'])) {
            $userData['password'] = $_POST['password'];
        }

        $ok1 = $userModel->updateUser($_SESSION['user_id'], $userData);

        $ok2 = true;
        if ($role === 'profesor') {
            require_once 'models/ProfesorModel.php';
            $profesorModel = new ProfesorModel();
            $profData = [
                'personal_description' => $_POST['personal_description'] ?? null,
                'academic_level' => $_POST['academic_level'] ?? null,
                'hourly_rate' => $_POST['hourly_rate'] ?? null,
            ];
            $ok2 = $profesorModel->updateProfesor($_SESSION['user_id'], $profData);
        } elseif ($role === 'estudiante') {
            require_once 'models/EstudianteModel.php';
            $estudianteModel = new EstudianteModel();
            $estData = [
                'personal_description' => $_POST['personal_description'] ?? null,
            ];
            $ok2 = $estudianteModel->updateEstudiante($_SESSION['user_id'], $estData);
        }

        $msg = ($ok1 && $ok2) ? 'updated' : 'error';
        header('Location: /plataforma-clases-online/home/perfil_edit?status=' . $msg);
        exit;
    }

    public function explorar_profesores()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['estudiante']);

        require_once 'models/ProfesorModel.php';
        require_once 'models/ReviewModel.php';
        require_once 'models/DisponibilidadModel.php';
        require_once 'models/ReservaModel.php';

        $profesorModel = new ProfesorModel();
        $reviewModel = new ReviewModel();
        $disponibilidadModel = new DisponibilidadModel();
        $reservaModel = new ReservaModel();

        $profesores = $profesorModel->getProfesores();

        // Filtrar por búsqueda si se proporciona
        $search = $_GET['search'] ?? '';
        if (!empty($search)) {
            $profesores = array_filter($profesores, function($p) use ($search) {
                $fullName = $p['first_name'] . ' ' . $p['last_name'];
                $normalizedName = $this->normalizeString($fullName);
                $normalizedSearch = $this->normalizeString($search);
                return stripos($normalizedName, $normalizedSearch) !== false;
            });
        }

        // Obtener reseñas y disponibilidad para cada profesor
        foreach ($profesores as &$profesor) {
            $reviews = $reviewModel->getReviewsByProfesor($profesor['user_id']);
            $profesor['rating'] = !empty($reviews) ? array_sum(array_column($reviews, 'rating')) / count($reviews) : 0;
            $profesor['review_count'] = count($reviews);

            // Obtener disponibilidad del profesor
            $disponibilidades = $disponibilidadModel->getDisponibilidadesByProfesor($profesor['user_id']);
            $profesor['disponibilidades'] = $disponibilidades;

            // Obtener slots disponibles para los próximos 7 días
            $availableSlots = $reservaModel->getAvailableSlots($profesor['user_id'], date('Y-m-d'), date('Y-m-d', strtotime('+7 days')));
            $profesor['available_slots'] = array_filter($availableSlots, function($slot) {
                return $slot['available'] == 1;
            });
        }

        $data = [
            'profesores' => $profesores
        ];

        extract($data);
        require_once 'views/views_estudiante/explorar_profesores.php';
    }
    
    public function pagar_pendiente()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['estudiante']);

        global $pdo;
        $paymentId = $_GET['payment_id'] ?? null;
        
        if (!$paymentId) {
            header('Location: /plataforma-clases-online/home/pagos?error=payment_not_found');
            exit;
        }

        require_once 'models/PagoModel.php';
        require_once 'models/ReservaModel.php';
        
        $pagoModel = new PagoModel();
        $reservaModel = new ReservaModel();
        
        // Obtener el pago con información completa
        $pago = $pagoModel->getPagoById($paymentId);
        
        if (!$pago) {
            header('Location: /plataforma-clases-online/home/pagos?error=payment_not_found');
            exit;
        }
        
        // Verificar que el pago pertenece al usuario actual
        if ($pago['user_id'] != $_SESSION['user_id']) {
            header('Location: /plataforma-clases-online/home/pagos?error=access_denied');
            exit;
        }
        
        // Verificar que el pago está pendiente
        if (strtolower($pago['payment_status']) !== 'pendiente') {
            header('Location: /plataforma-clases-online/home/pagos?error=payment_already_processed');
            exit;
        }

        // Si hay reserva asociada, usar esos datos
        $reservaData = null;
        $reservaAsociada = null;
        
        if ($pago['reservation_id']) {
            // Hay una reserva asociada, obtener sus datos completos
            $reservaAsociada = $reservaModel->getReservaById($pago['reservation_id']);
            
            if ($reservaAsociada) {
                $reservaData = [
                    'reservation_id' => $reservaAsociada['reservation_id'],
                    'profesor_id' => $reservaAsociada['user_id'],
                    'profesor_nombre' => $reservaAsociada['profesor_name'] . ' ' . $reservaAsociada['profesor_last_name'],
                    'profesor_email' => $reservaAsociada['profesor_email'],
                    'academic_level' => $reservaAsociada['academic_level'],
                    'hourly_rate' => $reservaAsociada['hourly_rate'] ?? $pago['amount'],
                    'class_date' => $reservaAsociada['class_date'],
                    'start_time' => $reservaAsociada['start_time'],
                    'end_time' => $reservaAsociada['end_time'],
                    'subject_name' => $reservaAsociada['subject_name'],
                    'day_name' => $reservaAsociada['day_name']
                ];
            }
        } else {
            // No hay reserva directamente asociada, buscar por fecha y usuario
            $stmt = $pdo->prepare("
                SELECT 
                    r.*,
                    u_profesor.first_name as profesor_name, 
                    u_profesor.last_name as profesor_last_name,
                    u_profesor.email as profesor_email,
                    prof.hourly_rate,
                    prof.academic_level,
                    d.start_time,
                    d.end_time,
                    mat.subject_name,
                    ds.day as day_name
                FROM reservas r 
                JOIN usuarios u_profesor ON r.user_id = u_profesor.user_id 
                LEFT JOIN profesor prof ON r.user_id = prof.user_id
                LEFT JOIN disponibilidad_profesores d ON r.availability_id = d.availability_id
                LEFT JOIN materias mat ON d.subject_id = mat.subject_id
                LEFT JOIN dias_semana ds ON d.week_day_id = ds.week_day_id
                WHERE r.student_user_id = ? 
                AND DATE(r.class_date) >= DATE(?)
                AND r.reservation_status_id = 1
                ORDER BY r.class_date ASC
                LIMIT 1
            ");
            $stmt->execute([$_SESSION['user_id'], $pago['payment_date']]);
            $reservaAsociada = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        // Pasar datos a la vista de confirmación de pago
        $amount = $pago['amount'];
        $description = $pago['description'] ?? 'Pago de clase pendiente';
        $paymentMethod = $pago['payment_method'] ?? 'PayPal';
        
        // Variables adicionales para la vista
        $payment_id = $pago['payment_id']; // Para identificar que es un pago pendiente        // Usar la misma vista de confirmación pero con datos del pago existente
        require_once 'views/views_estudiante/confirmar_reserva.php';
    }

    public function explorar_materias()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['estudiante']);

        require_once 'models/MateriaModel.php';
        $materiaModel = new MateriaModel();

        // Filtrar por búsqueda si se proporciona
        $search = $_GET['search'] ?? '';
        if (!empty($search)) {
            $materias = $materiaModel->searchMaterias($search);
        } else {
            $materias = $materiaModel->getMaterias();
        }

        $data = [
            'materias' => $materias
        ];

        extract($data);
        require_once 'views/views_estudiante/explorar_materias.php';
    }

    public function explorar_precio_hora()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['estudiante']);

        require_once 'models/ProfesorModel.php';
        $profesorModel = new ProfesorModel();

        $profesores = $profesorModel->getProfesores();

        // Agrupar profesores por rangos de precio por hora
        $precioRangos = [
            'Menos de $10' => [],
            '$10 - $20' => [],
            '$20 - $30' => [],
            '$30 - $40' => [],
            'Más de $40' => []
        ];

        foreach ($profesores as $profesor) {
            $rate = (float)($profesor['hourly_rate'] ?? 0);
            if ($rate < 10) {
                $precioRangos['Menos de $10'][] = $profesor;
            } elseif ($rate >= 10 && $rate < 20) {
                $precioRangos['$10 - $20'][] = $profesor;
            } elseif ($rate >= 20 && $rate < 30) {
                $precioRangos['$20 - $30'][] = $profesor;
            } elseif ($rate >= 30 && $rate < 40) {
                $precioRangos['$30 - $40'][] = $profesor;
            } else {
                $precioRangos['Más de $40'][] = $profesor;
            }
        }

        $data = [
            'precioRangos' => $precioRangos
        ];

        extract($data);
        require_once 'views/views_estudiante/explorar_precio_hora.php';
    }

    public function profesores_por_materia()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['estudiante']);

        $materiaId = $_GET['materia_id'] ?? null;
        if (!$materiaId) {
            header('Location: /plataforma-clases-online/home/explorar_materias');
            exit;
        }

        require_once 'models/MateriaModel.php';
        require_once 'models/ProfesorModel.php';

        $materiaModel = new MateriaModel();
        $profesorModel = new ProfesorModel();

        $materia = $materiaModel->getMateriaById($materiaId);
        if (!$materia) {
            header('Location: /plataforma-clases-online/home/explorar_materias');
            exit;
        }

        $profesores = $materiaModel->getProfesoresByMateria($materiaId);

        $data = [
            'materia' => $materia,
            'profesores' => $profesores
        ];

        extract($data);
        require_once 'views/views_estudiante/profesores_por_materia.php';
    }

    public function profesores_por_precio()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['estudiante']);

        $precioRango = $_GET['precio_rango'] ?? null;
        if (!$precioRango) {
            header('Location: /plataforma-clases-online/home/explorar_profesores');
            exit;
        }

        require_once 'models/ProfesorModel.php';
        require_once 'models/ReviewModel.php';
        require_once 'models/DisponibilidadModel.php';
        require_once 'models/ReservaModel.php';

        $profesorModel = new ProfesorModel();
        $reviewModel = new ReviewModel();
        $disponibilidadModel = new DisponibilidadModel();
        $reservaModel = new ReservaModel();

        $profesores = $profesorModel->getProfesores();

        // Filtrar profesores por rango de precio
        $profesores = array_filter($profesores, function($p) use ($precioRango) {
            $rate = (float)($p['hourly_rate'] ?? 0);
            switch ($precioRango) {
                case 'menos-10':
                    return $rate < 10;
                case '10-20':
                    return $rate >= 10 && $rate < 20;
                case '20-30':
                    return $rate >= 20 && $rate < 30;
                case '30-40':
                    return $rate >= 30 && $rate < 40;
                case 'mas-40':
                    return $rate >= 40;
                default:
                    return false;
            }
        });

        // Obtener reseñas y disponibilidad para cada profesor
        foreach ($profesores as &$profesor) {
            $reviews = $reviewModel->getReviewsByProfesor($profesor['user_id']);
            $profesor['rating'] = !empty($reviews) ? array_sum(array_column($reviews, 'rating')) / count($reviews) : 0;
            $profesor['review_count'] = count($reviews);

            // Obtener disponibilidad del profesor
            $disponibilidades = $disponibilidadModel->getDisponibilidadesByProfesor($profesor['user_id']);
            $profesor['disponibilidades'] = $disponibilidades;

            // Obtener slots disponibles para los próximos 7 días
            $availableSlots = $reservaModel->getAvailableSlots($profesor['user_id'], date('Y-m-d'), date('Y-m-d', strtotime('+7 days')));
            $profesor['available_slots'] = array_filter($availableSlots, function($slot) {
                return $slot['available'] == 1;
            });
        }

        // Mapear el rango a un nombre legible
        $rangoNombres = [
            'menos-10' => 'Menos de $10',
            '10-20' => '$10 - $20',
            '20-30' => '$20 - $30',
            '30-40' => '$30 - $40',
            'mas-40' => 'Más de $40'
        ];
        $rangoNombre = $rangoNombres[$precioRango] ?? 'Desconocido';

        $data = [
            'precioRango' => $precioRango,
            'rangoNombre' => $rangoNombre,
            'profesores' => $profesores
        ];

        extract($data);
        require_once 'views/views_estudiante/profesores_por_precio.php';
    }

    public function about()
    {
        require_once 'views/layouts/about.php';
    }

    public function crear_clase()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['profesor']);

        require_once 'models/EstudianteModel.php';
        $estudianteModel = new EstudianteModel();
        $estudiantes = $estudianteModel->getEstudiantes();

        $data = ['estudiantes' => $estudiantes];
        extract($data);
        require_once 'views/layouts/crear_clase.php';
    }

    public function crear_clase_store()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['profesor']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /plataforma-clases-online/home/crear_clase');
            exit;
        }

        require_once 'models/ReservaModel.php';
        $reservaModel = new ReservaModel();

        // No generamos reservation_id, será AUTO_INCREMENT
        $data = [
            'user_id' => $_SESSION['user_id'],
            'student_user_id' => $_POST['student_user_id'] ?? null,
            'availability_id' => null, // Se puede asignar después
            'reservation_status_id' => 1, // Pendiente
            'class_date' => $_POST['class_date'] ?? null,
            'class_time' => $_POST['class_time'] ?? null,
            'notes' => $_POST['notes'] ?? null
        ];

        $reservationId = $reservaModel->createReserva($data);
        $msg = $reservationId ? 'created' : 'error';
        header('Location: /plataforma-clases-online/home/profesor_dashboard?status=' . $msg);
        exit;
    }

    public function reportes()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['profesor']);

        // Redirigir al controlador de reportes con los parámetros necesarios
        $fechaInicio = $_GET['fecha_inicio'] ?? '';
        $fechaFin = $_GET['fecha_fin'] ?? '';
        $tipoReporte = $_GET['tipo_reporte'] ?? 'general';

        // Construir URL para el controlador de reportes
        $url = '/plataforma-clases-online/reportes/profesor';
        if ($fechaInicio) $url .= "?fecha_inicio=$fechaInicio";
        if ($fechaFin) $url .= ($fechaInicio ? '&' : '?') . "fecha_fin=$fechaFin";
        if ($tipoReporte) $url .= (($fechaInicio || $fechaFin) ? '&' : '?') . "tipo_reporte=$tipoReporte";

        header("Location: $url");
        exit;
    }

    public function reservar_clase()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['estudiante']);

        require_once 'models/ProfesorModel.php';
        require_once 'models/DisponibilidadModel.php';
        require_once 'models/ReservaModel.php';

        $profesorModel = new ProfesorModel();
        $disponibilidadModel = new DisponibilidadModel();
        $reservaModel = new ReservaModel();

        $profesorId = $_GET['profesor_id'] ?? null;

        if (!$profesorId) {
            header('Location: /plataforma-clases-online/home/explorar_profesores?error=missing_profesor');
            exit;
        }

        // Obtener información del profesor
        $profesor = $profesorModel->getProfesorById($profesorId);
        if (!$profesor) {
            header('Location: /plataforma-clases-online/home/explorar_profesores?error=profesor_not_found');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Procesar la reserva
            $availabilityId = $_POST['availability_id'] ?? null;
            $classDate = $_POST['class_date'] ?? null;
            $classTime = $_POST['class_time'] ?? null;
            $notes = $_POST['notes'] ?? null;

            if (!$availabilityId || !$classDate) {
                header('Location: /plataforma-clases-online/home/reservar_clase?profesor_id=' . $profesorId . '&error=missing_data');
                exit;
            }

            // Verificar disponibilidad
            if (!$reservaModel->checkAvailability($profesorId, $classDate, $availabilityId)) {
                header('Location: /plataforma-clases-online/home/reservar_clase?profesor_id=' . $profesorId . '&error=not_available');
                exit;
            }

            // Crear reserva en estado "Pendiente de Pago"
            // No generamos reservation_id, será AUTO_INCREMENT
            $data = [
                'user_id' => $profesorId,
                'student_user_id' => $_SESSION['user_id'],
                'availability_id' => $availabilityId,
                'reservation_status_id' => 1, // Pendiente
                'class_date' => $classDate,
                'class_time' => $classTime ?? null,
                'notes' => $notes ?? null
            ];

            $reservationId = $reservaModel->createReserva($data);

            if ($reservationId) {
                // NO crear pago pendiente automáticamente aquí
                // El pago se creará solo cuando el estudiante elija "Pagar más tarde" o pague inmediatamente

                // Redirigir a la página de confirmación y pago
                header('Location: /plataforma-clases-online/home/confirmar_reserva?reservation_id=' . $reservationId);
            } else {
                header('Location: /plataforma-clases-online/home/reservar_clase?profesor_id=' . $profesorId . '&error=creation_failed');
            }
            exit;
        } else {
            // Mostrar formulario de reserva
            // Obtener disponibilidad del profesor
            $disponibilidades = $disponibilidadModel->getDisponibilidadesByProfesor($profesorId);

            // Obtener slots disponibles para los próximos 90 días (aproximadamente 3 meses)
            $availableSlots = [];
            for ($i = 0; $i < 90; $i++) {
                $date = date('Y-m-d', strtotime("+$i days"));
                $dayOfWeek = date('N', strtotime($date)); // 1=Lunes, 7=Domingo

                $slotsForDay = [];
                foreach ($disponibilidades as $disp) {
                    if ($disp['week_day_id'] == $dayOfWeek && ($disp['reservation_status_id'] ?? 1) == 1) {
                        // Verificar si este slot está disponible
                        if ($reservaModel->checkAvailability($profesorId, $date, $disp['availability_id'])) {
                            $slotsForDay[] = [
                                'availability_id' => $disp['availability_id'],
                                'start_time' => $disp['start_time'],
                                'end_time' => $disp['end_time'],
                                'day_name' => $this->getDayName($dayOfWeek)
                            ];
                        }
                    }
                }

                if (!empty($slotsForDay)) {
                    $availableSlots[$date] = [
                        'date' => $date,
                        'date_display' => date('d/m/Y', strtotime($date)),
                        'day_name' => $this->getDayName($dayOfWeek),
                        'slots' => $slotsForDay
                    ];
                }
            }

            $data = [
                'profesor' => $profesor,
                'available_slots' => $availableSlots
            ];

            extract($data);
            require_once 'views/views_estudiante/reservar_clase.php';
        }
    }

    public function confirmar_reserva()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['estudiante']);

        $reservationId = $_GET['reservation_id'] ?? null;
        if (!$reservationId) {
            header('Location: /plataforma-clases-online/home/estudiante_dashboard?error=missing_reservation');
            exit;
        }

        require_once 'models/ReservaModel.php';
        require_once 'models/ProfesorModel.php';
        require_once 'models/DisponibilidadModel.php';
        require_once 'models/PagoModel.php';

        $reservaModel = new ReservaModel();
        $profesorModel = new ProfesorModel();
        $disponibilidadModel = new DisponibilidadModel();
        $pagoModel = new PagoModel();

        // Obtener datos de la reserva con toda la información necesaria
        $reserva = $reservaModel->getReservaById($reservationId);
        if (!$reserva || $reserva['student_user_id'] != $_SESSION['user_id']) {
            header('Location: /plataforma-clases-online/home/estudiante_dashboard?error=reservation_not_found');
            exit;
        }

        // Verificar si ya existe un pago pendiente para esta reserva
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT payment_id FROM pagos
            WHERE user_id = ? AND payment_status_id = 1
            AND description LIKE CONCAT('%Reserva: ', ?, '%')
        ");
        $stmt->execute([$_SESSION['user_id'], $reservationId]);
        $pagoExistente = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si existe un pago pendiente, redirigir a pagar_pendiente
        if ($pagoExistente) {
            header('Location: /plataforma-clases-online/home/pagar_pendiente?payment_id=' . $pagoExistente['payment_id']);
            exit;
        }

        // Los datos ya vienen completos de la consulta mejorada
        $reservaData = [
            'reservation_id' => $reserva['reservation_id'],
            'profesor_id' => $reserva['user_id'],
            'profesor_nombre' => $reserva['profesor_name'] . ' ' . $reserva['profesor_last_name'],
            'profesor_email' => $reserva['profesor_email'],
            'academic_level' => $reserva['academic_level'],
            'hourly_rate' => $reserva['hourly_rate'] ?? $reserva['availability_price'] ?? 25.00,
            'class_date' => $reserva['class_date'],
            'start_time' => $reserva['start_time'],
            'end_time' => $reserva['end_time'],
            'subject_name' => $reserva['subject_name'],
            'day_name' => $reserva['day_name']
        ];

        // Variable para compatibilidad con ambos flujos (nueva reserva y pago pendiente)
        $amount = $reservaData['hourly_rate'];

        require_once 'views/views_estudiante/confirmar_reserva.php';
    }

    public function procesar_pago()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['estudiante']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            exit;
        }

        $action = $_POST['action'] ?? null;
        
        require_once 'models/ReservaModel.php';
        require_once 'models/PagoModel.php';
        require_once 'models/ProfesorModel.php';

        $reservaModel = new ReservaModel();
        $pagoModel = new PagoModel();
        $profesorModel = new ProfesorModel();

        if ($action === 'complete_payment') {
            // Pago completado con PayPal - puede ser para reserva nueva o pago pendiente
            $transactionId = $_POST['transaction_id'] ?? null;
            $paypalOrderId = $_POST['paypal_order_id'] ?? null;
            $reservationId = $_POST['reservation_id'] ?? null;
            $paymentId = $_POST['payment_id'] ?? null; // Para pagos pendientes
            $amount = $_POST['amount'] ?? null;

            if (!$transactionId) {
                echo json_encode(['success' => false, 'message' => 'Transaction ID requerido']);
                exit;
            }

            if ($paymentId) {
                // Actualizar pago pendiente existente
                $success = $pagoModel->updatePagoStatus($paymentId, 2); // 2 = Completado
                if ($success) {
                    // También actualizar transaction_id si es necesario
                    global $pdo;
                    $stmt = $pdo->prepare("UPDATE pagos SET transaction_id = ? WHERE payment_id = ?");
                    $stmt->execute([$transactionId, $paymentId]);

                    // Buscar y actualizar la reserva asociada a "Confirmada" (estado 2)
                    $stmt2 = $pdo->prepare("
                        SELECT reservation_id FROM reservas
                        WHERE student_user_id = ? AND reservation_status_id = 1
                        AND class_date >= DATE(?)
                        ORDER BY class_date ASC LIMIT 1
                    ");
                    $stmt2->execute([$_SESSION['user_id'], date('Y-m-d')]);
                    $reservaAsociada = $stmt2->fetch(PDO::FETCH_ASSOC);

                    if ($reservaAsociada) {
                        $reservaModel->updateReservaStatus($reservaAsociada['reservation_id'], 2); // 2 = Confirmada
                    }

                    echo json_encode(['success' => true, 'message' => 'Pago completado exitosamente', 'payment_id' => $paymentId]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al actualizar el pago']);
                }
            } elseif ($reservationId) {
                // Crear nuevo pago para reserva
                if (!$amount) {
                    echo json_encode(['success' => false, 'message' => 'Monto requerido']);
                    exit;
                }

                // Verificar que la reserva pertenece al estudiante y obtener datos completos
                $reserva = $reservaModel->getReservaById($reservationId);
                if (!$reserva || $reserva['student_user_id'] != $_SESSION['user_id']) {
                    echo json_encode(['success' => false, 'message' => 'Reserva no encontrada']);
                    exit;
                }

                // Usar la tarifa correcta del profesor o disponibilidad
                $tarifaReal = $reserva['hourly_rate'] ?? $reserva['availability_price'] ?? $amount;

                // Crear registro de pago con descripción mejorada
                $pagoData = [
                    'user_id' => $_SESSION['user_id'],
                    'payment_status_id' => 2, // Completado
                    'amount' => $tarifaReal,
                    'payment_method' => 'PayPal',
                    'description' => "Clase de {$reserva['subject_name']} con {$reserva['profesor_name']} {$reserva['profesor_last_name']} - Reserva: {$reservationId}",
                    'transaction_id' => $transactionId
                ];

                $pagoSuccess = $pagoModel->createPago($pagoData);

                if ($pagoSuccess) {
                    // Actualizar estado de reserva a "Confirmada"
                    $reservaModel->updateReservaStatus($reservationId, 2); // 2 = Confirmada
                    echo json_encode(['success' => true, 'message' => 'Pago procesado exitosamente', 'reservation_id' => $reservationId]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al registrar el pago']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Datos de pago incompletos']);
            }

        } elseif ($action === 'pay_later') {
            // Pagar más tarde - crear pago pendiente solo si no existe uno
            $reservationId = $_POST['reservation_id'] ?? null;

            if (!$reservationId) {
                echo json_encode(['success' => false, 'message' => 'ID de reserva requerido']);
                exit;
            }

            // Verificar que la reserva pertenece al estudiante y obtener datos completos
            $reserva = $reservaModel->getReservaById($reservationId);
            if (!$reserva || $reserva['student_user_id'] != $_SESSION['user_id']) {
                echo json_encode(['success' => false, 'message' => 'Reserva no encontrada']);
                exit;
            }

            // Verificar si ya existe un pago pendiente para esta reserva
            global $pdo;
            $stmt = $pdo->prepare("
                SELECT payment_id FROM pagos
                WHERE user_id = ? AND payment_status_id = 1
                AND description LIKE CONCAT('%Reserva: ', ?, '%')
            ");
            $stmt->execute([$_SESSION['user_id'], $reservationId]);
            $pagoExistente = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($pagoExistente) {
                // Ya existe un pago pendiente, no crear otro
                echo json_encode(['success' => true, 'message' => 'Ya existe un pago pendiente para esta reserva', 'reservation_id' => $reservationId]);
            } else {
                // Usar la tarifa correcta del profesor o disponibilidad
                $amount = $reserva['hourly_rate'] ?? $reserva['availability_price'] ?? 25.00;

                // Crear registro de pago pendiente con descripción mejorada
                $pagoData = [
                    'user_id' => $_SESSION['user_id'],
                    'payment_status_id' => 1, // Pendiente
                    'amount' => $amount,
                    'payment_method' => 'PayPal',
                    'description' => "Pago pendiente - Clase de {$reserva['subject_name']} con {$reserva['profesor_name']} {$reserva['profesor_last_name']} - Reserva: {$reservationId}",
                    'transaction_id' => null
                ];

                $pagoSuccess = $pagoModel->createPago($pagoData);

                if ($pagoSuccess) {
                    // La reserva mantiene estado "Pendiente" hasta que se pague
                    echo json_encode(['success' => true, 'message' => 'Reserva confirmada para pagar más tarde', 'reservation_id' => $reservationId]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al crear pago pendiente']);
                }
            }

        } else {
            echo json_encode(['success' => false, 'message' => 'Acción no válida']);
        }

        exit;
    }

    public function pago_exitoso()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['estudiante']);

        $reservationId = $_GET['reservation_id'] ?? null;
        if (!$reservationId) {
            header('Location: /plataforma-clases-online/home/estudiante_dashboard');
            exit;
        }

        require_once 'models/ReservaModel.php';
        $reservaModel = new ReservaModel();
        $reserva = $reservaModel->getReservaById($reservationId);

        require_once 'views/views_estudiante/pago_exitoso.php';
    }

    public function reserva_confirmada()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['estudiante']);

        $reservationId = $_GET['reservation_id'] ?? null;
        if (!$reservationId) {
            header('Location: /plataforma-clases-online/home/estudiante_dashboard');
            exit;
        }

        require_once 'models/ReservaModel.php';
        $reservaModel = new ReservaModel();
        $reserva = $reservaModel->getReservaById($reservationId);

        require_once 'views/views_estudiante/reserva_confirmada.php';
    }

    private function getDayName($dayNumber) {
        $days = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            7 => 'Domingo'
        ];
        return $days[$dayNumber] ?? 'Desconocido';
    }

    public function cancelar_reserva()
    {
        AuthController::checkAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /plataforma-clases-online/home/estudiante_dashboard');
            exit;
        }

        $reservationId = $_POST['reservation_id'] ?? null;

        if (!$reservationId) {
            header('Location: /plataforma-clases-online/home/estudiante_dashboard?error=missing_id');
            exit;
        }

        require_once 'models/ReservaModel.php';
        $reservaModel = new ReservaModel();

        $success = $reservaModel->cancelReserva($reservationId, $_SESSION['user_id']);

        if ($success) {
            header('Location: /plataforma-clases-online/home/reservas?success=cancelled');
        } else {
            header('Location: /plataforma-clases-online/home/reservas?error=cancel_failed&debug=check_permissions');
        }
        exit;
    }

    public function completar_reserva()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['profesor']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /plataforma-clases-online/home/profesor_dashboard');
            exit;
        }

        $reservationId = $_POST['reservation_id'] ?? null;

        if (!$reservationId) {
            header('Location: /plataforma-clases-online/home/profesor_dashboard?error=missing_id');
            exit;
        }

        require_once 'models/ReservaModel.php';
        $reservaModel = new ReservaModel();

        // Verificar que la reserva pertenece al profesor
        $reserva = $reservaModel->getReservaById($reservationId);
        if (!$reserva || $reserva['user_id'] != $_SESSION['user_id']) {
            header('Location: /plataforma-clases-online/home/profesor_dashboard?error=not_authorized');
            exit;
        }

        // Solo permitir completar reservas confirmadas
        if ($reserva['reservation_status_id'] != 2) {
            header('Location: /plataforma-clases-online/home/profesor_dashboard?error=invalid_status');
            exit;
        }

        // Marcar como completada (estado 4)
        $success = $reservaModel->updateReservaStatus($reservationId, 4);

        if ($success) {
            header('Location: /plataforma-clases-online/home/reservas?success=completed');
        } else {
            header('Location: /plataforma-clases-online/home/reservas?error=complete_failed');
        }
        exit;
    }

    public function reagendar_reserva()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['profesor']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /plataforma-clases-online/home/profesor_dashboard');
            exit;
        }

        $reservationId = $_POST['reservation_id'] ?? null;
        $newDate = $_POST['new_date'] ?? null;
        $newAvailabilityId = $_POST['new_availability_id'] ?? null;

        if (!$reservationId || !$newDate) {
            header('Location: /plataforma-clases-online/home/reservas?error=missing_data');
            exit;
        }

        require_once 'models/ReservaModel.php';
        $reservaModel = new ReservaModel();

        // Verificar que la reserva pertenece al profesor
        $reserva = $reservaModel->getReservaById($reservationId);
        if (!$reserva || $reserva['user_id'] != $_SESSION['user_id']) {
            header('Location: /plataforma-clases-online/home/reservas?error=not_authorized');
            exit;
        }

        // Solo permitir reagendar reservas pendientes o confirmadas
        if (!in_array($reserva['reservation_status_id'], [1, 2])) {
            header('Location: /plataforma-clases-online/home/reservas?error=invalid_status_reschedule');
            exit;
        }

        // Intentar reagendar la reserva
        $success = $reservaModel->reagendarReserva($reservationId, $newDate, $newAvailabilityId);

        if ($success) {
            header('Location: /plataforma-clases-online/home/reservas?success=rescheduled');
        } else {
            header('Location: /plataforma-clases-online/home/reservas?error=reschedule_failed&debug=slot_not_available');
        }
        exit;
    }

    public function get_available_slots_profesor()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['profesor']);

        $fecha = $_GET['fecha'] ?? null;

        if (!$fecha) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Fecha requerida']);
            exit;
        }

        require_once 'models/DisponibilidadModel.php';
        require_once 'models/ReservaModel.php';

        $disponibilidadModel = new DisponibilidadModel();
        $reservaModel = new ReservaModel();

        // Obtener disponibilidad del profesor para esa fecha
        $diaSemana = date('N', strtotime($fecha)); // 1=Lunes, 7=Domingo

        $disponibilidades = $disponibilidadModel->getDisponibilidadesByProfesor($_SESSION['user_id']);

        $slotsDisponibles = [];
        $diasTrabajo = [];
        $horariosTrabajo = [];

        foreach ($disponibilidades as $disp) {
            // Solo mostrar slots que estén marcados como "Disponible" (ID 1)
            if ($disp['week_day_id'] == $diaSemana && $disp['availability_status_id'] == 1) {
                // Verificar si este slot específico está disponible (no reservado)
                if ($reservaModel->checkAvailability($_SESSION['user_id'], $fecha, $disp['availability_id'])) {
                    $slotsDisponibles[] = [
                        'availability_id' => $disp['availability_id'],
                        'start_time' => $disp['start_time'],
                        'end_time' => $disp['end_time'],
                        'day' => $disp['day']
                    ];
                }
            }

            // Recopilar días de trabajo únicos y sus horarios
            if ($disp['availability_status_id'] == 1) {
                $diaNombre = $disp['day'];
                if (!isset($diasTrabajo[$disp['week_day_id']])) {
                    $diasTrabajo[$disp['week_day_id']] = $diaNombre;
                    $horariosTrabajo[$disp['week_day_id']] = [];
                }
                $horariosTrabajo[$disp['week_day_id']][] = $disp['start_time'] . ' - ' . $disp['end_time'];
            }
        }

        // Ordenar días de trabajo
        ksort($diasTrabajo);
        $diasTrabajoList = array_values($diasTrabajo);

        // Crear lista de horarios por día
        $horariosPorDia = [];
        foreach ($diasTrabajo as $weekDayId => $diaNombre) {
            $horariosPorDia[] = $diaNombre . ': ' . implode(', ', $horariosTrabajo[$weekDayId]);
        }

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'slots' => $slotsDisponibles,
            'dias_trabajo' => $diasTrabajoList,
            'horarios_trabajo' => $horariosPorDia
        ]);
        exit;
    }

    public function fix_estado_reserva()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador']);

        require_once 'models/ReservaModel.php';
        $reservaModel = new ReservaModel();

        $success = $reservaModel->fixEstadosReserva();

        if ($success) {
            header('Location: /plataforma-clases-online/home/reservas?success=estados_fixed');
        } else {
            header('Location: /plataforma-clases-online/home/reservas?error=fix_failed');
        }
        exit;
    }

    public function mensajes()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['profesor']);

        require_once 'models/EstudianteModel.php';
        $estudianteModel = new EstudianteModel();
        $estudiantes = $estudianteModel->getEstudiantes();

        // Placeholder para conversaciones y mensajes
        $conversaciones = [];
        $historial_mensajes = [];

        $data = [
            'estudiantes' => $estudiantes,
            'conversaciones' => $conversaciones,
            'historial_mensajes' => $historial_mensajes
        ];

        extract($data);
        require_once 'views/layouts/mensajes.php';
    }

    public function get_available_slots()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['estudiante']);

        $profesorId = $_GET['profesor_id'] ?? null;
        $fecha = $_GET['fecha'] ?? null;

        if (!$profesorId || !$fecha) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            exit;
        }

        require_once 'models/ReservaModel.php';
        require_once 'models/DisponibilidadModel.php';

        $reservaModel = new ReservaModel();
        $disponibilidadModel = new DisponibilidadModel();

        // Obtener disponibilidad del profesor para esa fecha
        $diaSemana = date('N', strtotime($fecha)); // 1=Lunes, 7=Domingo

        $disponibilidades = $disponibilidadModel->getDisponibilidadesByProfesor($profesorId);

        $slotsDisponibles = [];
        foreach ($disponibilidades as $disp) {
            // Solo mostrar slots que estén marcados como "Disponible" (ID 1)
            if ($disp['week_day_id'] == $diaSemana && $disp['reservation_status_id'] == 1) {
                // Verificar si este slot específico está disponible (no reservado)
                if ($reservaModel->checkAvailability($profesorId, $fecha, $disp['availability_id'])) {
                    $slotsDisponibles[] = [
                        'availability_id' => $disp['availability_id'],
                        'start_time' => $disp['start_time'],
                        'end_time' => $disp['end_time'],
                        'day' => $disp['day']
                    ];
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'slots' => $slotsDisponibles
        ]);
        exit;
    }

    public function ver_estudiante()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['profesor']);

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /plataforma-clases-online/home/profesor_dashboard');
            exit;
        }

        require_once 'models/UserModel.php';
        require_once 'models/EstudianteModel.php';
        require_once 'models/ReservaModel.php';
        require_once 'models/PagoModel.php';
        require_once 'models/ReviewModel.php';

        $userModel = new UserModel();
        $estudianteModel = new EstudianteModel();
        $reservaModel = new ReservaModel();
        $pagoModel = new PagoModel();
        $reviewModel = new ReviewModel();

        $estudiante = $userModel->getUserById($id);
        if (!$estudiante) {
            header('Location: /plataforma-clases-online/home/profesor_dashboard');
            exit;
        }

        // Obtener datos adicionales
        $reservas = $reservaModel->getReservasByEstudianteWithDetails($id);
        $pagos = $pagoModel->getPagosByEstudiante($id);
        $reviews = $reviewModel->getReviewsByProfesor($_SESSION['user_id']);

        // Filtrar reviews de este estudiante
        $estudianteReviews = array_filter($reviews, function($r) use ($id) {
            return isset($r['reviewer_user_id']) && $r['reviewer_user_id'] == $id;
        });

        // Debug: Agregar logs para verificar datos
        error_log("Estudiante ID: $id");
        error_log("Total reviews del profesor: " . count($reviews));
        error_log("Reviews filtradas para estudiante: " . count($estudianteReviews));
        foreach ($estudianteReviews as $review) {
            error_log("Review ID: " . ($review['review_id'] ?? 'N/A') . ", Reservation ID: " . ($review['reservation_id'] ?? 'N/A') . ", Rating: " . ($review['rating'] ?? 'N/A'));
        }

        // Agregar calificación si existe para esta reserva
        foreach ($reservas as &$reserva) {
            $reserva['rating'] = null;
            foreach ($estudianteReviews as $review) {
                if (isset($review['reservation_id']) && $review['reservation_id'] == $reserva['reservation_id']) {
                    $reserva['rating'] = $review['rating'];
                    error_log("Asignando rating {$review['rating']} a reserva {$reserva['reservation_id']}");
                    break;
                }
            }
        }

        // Calcular estadísticas
        $clasesTotales = count($reservas);
        $clasesCompletadas = count(array_filter($reservas, function($r) { return $r['reservation_status'] === 'completada'; }));
        $clasesPendientes = $clasesTotales - $clasesCompletadas;
        $totalInvertido = array_sum(array_column($pagos, 'amount'));

        $data = [
            'estudiante' => $estudiante,
            'historial_clases' => $reservas,
            'calificaciones' => $estudianteReviews,
            'estadisticas' => [
                'clases_totales' => $clasesTotales,
                'clases_completadas' => $clasesCompletadas,
                'clases_pendientes' => $clasesPendientes,
                'total_invertido' => $totalInvertido
            ]
        ];

        extract($data);
        require_once 'views/layouts/ver_estudiante.php';
    }
}
