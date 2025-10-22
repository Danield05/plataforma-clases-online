<?php
require_once 'controllers/AuthController.php';
class HomeController
{
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

        $reservaModel = new ReservaModel();
        $pagoModel = new PagoModel();
        $profesorModel = new ProfesorModel();

        $userId = $_SESSION['user_id'];

        // Obtener reservas del estudiante
        $reservas = $reservaModel->getReservasByEstudiante($userId);

        // Obtener pagos del estudiante
        $pagos = $pagoModel->getPagosByEstudiante($userId);

        // Calcular estadísticas
        $clasesReservadas = count($reservas);
        $clasesCompletadas = count(array_filter($reservas, function($r) { return $r['reservation_status'] === 'completada'; }));
        $profesoresActivos = count(array_unique(array_column($reservas, 'user_id')));
        $totalInvertido = array_sum(array_column($pagos, 'amount'));

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

    public function reservas()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador', 'profesor', 'estudiante']);

        require_once 'models/ReservaModel.php';
        $reservaModel = new ReservaModel();

        // Filtrar reservas según el rol del usuario
        if ($_SESSION['role'] === 'estudiante') {
            $reservas = $reservaModel->getReservasByEstudiante($_SESSION['user_id']);
        } elseif ($_SESSION['role'] === 'profesor') {
            $reservas = $reservaModel->getReservasByProfesor($_SESSION['user_id']);
        } else {
            // Administrador ve todas las reservas
            $reservas = $reservaModel->getReservas();
        }

        // Pasar información a la vista
        $data = compact('reservas');
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
        $pagoModel = new PagoModel();

        // Filtrar pagos según el rol del usuario
        if ($_SESSION['role'] === 'profesor') {
            $pagos = $pagoModel->getPagosByProfesor($_SESSION['user_id']);
        } elseif ($_SESSION['role'] === 'estudiante') {
            $pagos = $pagoModel->getPagosByEstudiante($_SESSION['user_id']);
        } else {
            // Administrador ve todos los pagos
            $pagos = $pagoModel->getPagos();
        }

        $totales = $pagoModel->getTotales();

        extract($totales);
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

        $reservationId = time() . rand(1000, 9999); // timestamp + número aleatorio
        $data = [
            'reservation_id' => $reservationId,
            'user_id' => $_SESSION['user_id'],
            'student_user_id' => $_POST['student_user_id'] ?? null,
            'availability_id' => null, // Se puede asignar después
            'reservation_status_id' => 1, // Pendiente
            'class_date' => $_POST['class_date'] ?? null
        ];

        $ok = $reservaModel->createReserva($data);
        $msg = $ok ? 'created' : 'error';
        header('Location: /plataforma-clases-online/home/profesor_dashboard?status=' . $msg);
        exit;
    }

    public function reportes()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['profesor']);

        require_once 'models/ReservaModel.php';
        require_once 'models/PagoModel.php';
        require_once 'models/ReviewModel.php';
        require_once 'models/EstudianteModel.php';

        $reservaModel = new ReservaModel();
        $pagoModel = new PagoModel();
        $reviewModel = new ReviewModel();
        $estudianteModel = new EstudianteModel();

        $profesorId = $_SESSION['user_id'];

        // Datos para reportes
        $reservas = $reservaModel->getReservasByProfesor($profesorId);
        $pagos = $pagoModel->getPagosByProfesor($profesorId);
        $reviews = $reviewModel->getReviewsByProfesor($profesorId);

        // Calcular estadísticas
        $totalClases = count($reservas);
        $totalEstudiantes = count(array_unique(array_column($reservas, 'student_user_id')));
        $ingresosTotales = array_sum(array_column($pagos, 'amount'));
        $calificacionPromedio = !empty($reviews) ? array_sum(array_column($reviews, 'rating')) / count($reviews) : 0;

        // Top estudiantes
        $estudiantesCount = array_count_values(array_column($reservas, 'student_user_id'));
        arsort($estudiantesCount);
        $topEstudiantes = [];
        foreach(array_slice($estudiantesCount, 0, 5, true) as $estId => $count) {
            $estudiante = $estudianteModel->getEstudianteById($estId);
            if ($estudiante) {
                $topEstudiantes[] = [
                    'nombre' => $estudiante['first_name'] . ' ' . $estudiante['last_name'],
                    'clases' => $count,
                    'ultima_clase' => '2024-01-01' // Placeholder
                ];
            }
        }

        // Preparar calificaciones recientes con formato correcto
        $calificacionesRecientes = [];
        foreach(array_slice($reviews, 0, 5) as $review) {
            $calificacionesRecientes[] = [
                'estudiante' => $review['estudiante_name'] . ' ' . $review['estudiante_last_name'],
                'rating' => $review['rating'],
                'fecha' => $review['created_at'] ?? date('Y-m-d'),
                'comentario' => $review['comment'] ?? ''
            ];
        }

        $data = [
            'reportes' => [
                'total_clases' => $totalClases,
                'total_estudiantes' => $totalEstudiantes,
                'ingresos_totales' => $ingresosTotales,
                'calificacion_promedio' => $calificacionPromedio,
                'top_estudiantes' => $topEstudiantes,
                'calificaciones_recientes' => $calificacionesRecientes
            ]
        ];

        extract($data);
        require_once 'views/layouts/reportes.php';
    }

    public function reservar_clase()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['estudiante']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /plataforma-clases-online/home/explorar_profesores');
            exit;
        }

        require_once 'models/ReservaModel.php';
        $reservaModel = new ReservaModel();

        $availabilityId = $_POST['availability_id'] ?? null;
        $classDate = $_POST['class_date'] ?? null;
        $profesorId = $_POST['profesor_id'] ?? null;

        if (!$availabilityId || !$classDate || !$profesorId) {
            header('Location: /plataforma-clases-online/home/explorar_profesores?error=missing_data');
            exit;
        }

        // Verificar disponibilidad
        if (!$reservaModel->checkAvailability($profesorId, $classDate, $availabilityId)) {
            header('Location: /plataforma-clases-online/home/explorar_profesores?error=not_available');
            exit;
        }

        // Crear reserva - Generar ID único numérico
        $reservationId = time() . rand(1000, 9999); // timestamp + número aleatorio
        $data = [
            'reservation_id' => $reservationId,
            'user_id' => $profesorId,
            'student_user_id' => $_SESSION['user_id'],
            'availability_id' => $availabilityId,
            'reservation_status_id' => 1, // Pendiente
            'class_date' => $classDate
        ];

        $success = $reservaModel->createReserva($data);

        if ($success) {
            header('Location: /plataforma-clases-online/home/estudiante_dashboard?success=reservation_created');
        } else {
            header('Location: /plataforma-clases-online/home/explorar_profesores?error=creation_failed');
        }
        exit;
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
        $reservas = $reservaModel->getReservasByEstudiante($id);
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

        // Agregar información de disponibilidad a las reservas para mostrar horas
        foreach ($reservas as &$reserva) {
            if (isset($reserva['availability_id']) && $reserva['availability_id']) {
                require_once 'models/DisponibilidadModel.php';
                $disponibilidadModel = new DisponibilidadModel();
                $disponibilidad = $disponibilidadModel->getDisponibilidadById($reserva['availability_id']);
                if ($disponibilidad) {
                    $reserva['start_time'] = $disponibilidad['start_time'];
                    $reserva['end_time'] = $disponibilidad['end_time'];
                }
            }

            // Agregar calificación si existe para esta reserva
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
