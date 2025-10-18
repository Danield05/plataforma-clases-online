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

            $profesorModel = new ProfesorModel();
            $estudianteModel = new EstudianteModel();
            $reservaModel = new ReservaModel();

            $data = [
                'profesores' => $profesorModel->getProfesores(),
                'estudiantes' => $estudianteModel->getEstudiantes(),
                'reservas' => $reservaModel->getReservas()
            ];

            // Pasar datos a la vista
            require_once 'views/home.php';
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

        $reservaModel = new ReservaModel();
        $disponibilidadModel = new DisponibilidadModel();
        $pagoModel = new PagoModel();

        // Obtener reservas del profesor (asumiendo que hay una forma de filtrar por profesor)
        $reservas = $reservaModel->getReservas(); // TODO: filtrar por profesor logueado
        $disponibilidades = $disponibilidadModel->getDisponibilidades(); // TODO: filtrar por profesor
        $pagos = $pagoModel->getPagos(); // TODO: filtrar por profesor logueado

        require_once 'views/views_profesor/profesor_dashboard.php';
    }

    public function estudiante_dashboard()
    {
        // Dashboard específico para estudiantes
        require_once 'models/ReservaModel.php';
        require_once 'models/PagoModel.php';

        $reservaModel = new ReservaModel();
        $pagoModel = new PagoModel();

        // Obtener reservas y pagos del estudiante
        $reservas = $reservaModel->getReservas(); // TODO: filtrar por estudiante logueado
        $pagos = $pagoModel->getPagos(); // TODO: filtrar por estudiante

        require_once 'views/views_estudiante/estudiante_dashboard.php';
    }

    public function profesores()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador']);

        require_once 'models/ProfesorModel.php';
        $profesorModel = new ProfesorModel();
        $profesores = $profesorModel->getProfesores();
        require_once 'views/profesores.php';
    }

    public function estudiantes()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador']);

        require_once 'models/EstudianteModel.php';
        $estudianteModel = new EstudianteModel();
        $estudiantes = $estudianteModel->getEstudiantes();
        require_once 'views/estudiantes.php';
    }

    public function reservas()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador', 'profesor', 'estudiante']);

        require_once 'models/ReservaModel.php';
        $reservaModel = new ReservaModel();
        $reservas = $reservaModel->getReservas();
        require_once 'views/reservas.php';
    }

    public function disponibilidad()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador', 'profesor']);

        require_once 'models/DisponibilidadModel.php';
        $disponibilidadModel = new DisponibilidadModel();
        $disponibilidades = $disponibilidadModel->getDisponibilidades();
        require_once 'views/disponibilidad.php';
    }

    public function pagos()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador', 'estudiante']);

        require_once 'models/PagoModel.php';
        $pagoModel = new PagoModel();
        $pagos = $pagoModel->getPagos();
        $totales = $pagoModel->getTotales();

        extract($totales);
        require_once 'views/pagos.php';
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

        require_once 'views/ver_pago.php'; // Nueva vista para detalle
    }

    public function reviews()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador']);

        require_once 'models/ReviewModel.php';
        $reviewModel = new ReviewModel();
        $reviews = $reviewModel->getReviews();
        require_once 'views/reviews.php';
    }

    public function about()
    {
        require_once 'views/about.php';
    }
}
