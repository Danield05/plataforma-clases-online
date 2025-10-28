<?php
require_once 'controllers/AuthController.php';

class ReportesController
{
    /**
     * Función principal - redirige según el rol del usuario
     */
    public function index()
    {
        AuthController::checkAuth();

        $role = $_SESSION['role'] ?? '';

        switch ($role) {
            case 'administrador':
                $this->reporteGeneral();
                break;
            case 'profesor':
                $this->reporteProfesor();
                break;
            case 'estudiante':
                $this->reporteEstudiante();
                break;
            default:
                header('Location: /plataforma-clases-online/auth/login');
                exit;
        }
    }

    /**
     * Función para mostrar reporte general de pagos
     * Accesible para administradores
     */
    public function reportePagos()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador']);

        require_once 'models/PagoModel.php';
        require_once 'models/ReservaModel.php';

        $pagoModel = new PagoModel();
        $reservaModel = new ReservaModel();

        // Obtener filtros de fecha si se proporcionan
        $fechaInicio = $_GET['fecha_inicio'] ?? '';
        $fechaFin = $_GET['fecha_fin'] ?? '';
        $tipoReporte = $_GET['tipo_reporte'] ?? 'general';

        // Obtener pagos con posibles filtros
        $pagos = $pagoModel->getPagos();

        // Obtener totales
        $totales = $pagoModel->getTotales();

        // Obtener estadísticas adicionales
        $estadisticas = [
            'total_pagos' => count($pagos),
            'promedio_pago' => !empty($pagos) ? array_sum(array_column($pagos, 'amount')) / count($pagos) : 0,
            'metodos_pago' => $this->getMetodosPagoStats($pagos)
        ];

        $data = [
            'pagos' => $pagos,
            'totales' => $totales,
            'estadisticas' => $estadisticas,
            'filtros' => [
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'tipo_reporte' => $tipoReporte
            ]
        ];

        extract($data);
        require_once 'views/reportes/reporte_pagos.php';
    }

    /**
     * Función para mostrar reporte específico de profesor
     * Accesible para profesores y administradores
     */
    public function reporteProfesor()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador', 'profesor']);

        $profesorId = $_GET['profesor_id'] ?? $_SESSION['user_id'];

        // Si es profesor, solo puede ver su propio reporte
        if ($_SESSION['role'] === 'profesor' && $profesorId != $_SESSION['user_id']) {
            header('Location: /plataforma-clases-online/reportes/profesor?error=no_autorizado');
            exit;
        }

        require_once 'models/ReservaModel.php';
        require_once 'models/PagoModel.php';
        require_once 'models/ReviewModel.php';
        require_once 'models/EstudianteModel.php';
        require_once 'models/ProfesorModel.php';

        $reservaModel = new ReservaModel();
        $pagoModel = new PagoModel();
        $reviewModel = new ReviewModel();
        $estudianteModel = new EstudianteModel();
        $profesorModel = new ProfesorModel();

        // Obtener datos del profesor
        $profesor = $profesorModel->getProfesorById($profesorId);
        $userData = [
            'first_name' => $profesor['first_name'] ?? 'Usuario',
            'last_name' => $profesor['last_name'] ?? 'Desconocido',
            'user_id' => $profesorId
        ];

        // Obtener filtros de fecha
        $fechaInicio = $_GET['fecha_inicio'] ?? '';
        $fechaFin = $_GET['fecha_fin'] ?? '';

        // Obtener datos con filtros
        $reservas = $reservaModel->getReservasByProfesor($profesorId, $fechaInicio, $fechaFin);
        $pagos = $pagoModel->getPagosByProfesor($profesorId);
        $reviews = $reviewModel->getReviewsByProfesor($profesorId, $fechaInicio, $fechaFin);

        // Debug: Log para verificar datos obtenidos
        error_log("=== REPORTE PROFESOR - DATOS OBTENIDOS ===");
        error_log("Profesor ID: " . $profesorId);
        error_log("Nombre Profesor: " . ($profesor['first_name'] ?? 'no definido') . ' ' . ($profesor['last_name'] ?? 'no definido'));
        error_log("Reservas encontradas: " . count($reservas));
        error_log("Pagos encontrados: " . count($pagos));
        error_log("Reviews encontrados: " . count($reviews));
        error_log("Estudiantes únicos: " . count(array_unique(array_column($reservas, 'student_user_id'))));
        error_log("=== FIN DATOS ===");

        // Calcular estadísticas
        $totalClases = count($reservas);
        $clasesCompletadas = count(array_filter($reservas, function($r) {
            return strtolower($r['reservation_status'] ?? '') === 'completada';
        }));
        $clasesPendientes = count(array_filter($reservas, function($r) {
            return in_array(strtolower($r['reservation_status'] ?? ''), ['pendiente', 'confirmada']);
        }));
        $clasesCanceladas = count(array_filter($reservas, function($r) {
            return strtolower($r['reservation_status'] ?? '') === 'cancelada';
        }));

        $ingresosTotales = array_sum(array_column($pagos, 'amount'));
        $calificacionPromedio = !empty($reviews) ? array_sum(array_column($reviews, 'rating')) / count($reviews) : 0;

        // Top estudiantes
        $estudiantesUnicos = array_unique(array_column($reservas, 'student_user_id'));
        $topEstudiantes = [];
        foreach (array_slice($estudiantesUnicos, 0, 5) as $estId) {
            $estudiante = $estudianteModel->getEstudianteById($estId);
            if ($estudiante) {
                $clasesEstudiante = count(array_filter($reservas, function($r) use ($estId) {
                    return $r['student_user_id'] == $estId;
                }));
                $topEstudiantes[] = [
                    'nombre' => $estudiante['first_name'] . ' ' . $estudiante['last_name'],
                    'clases' => $clasesEstudiante,
                    'ultima_clase' => '2024-01-01' // Placeholder - debería calcularse
                ];
            }
        }

        $estadisticas = [
            'total_clases' => $totalClases,
            'clases_completadas' => $clasesCompletadas,
            'clases_pendientes' => $clasesPendientes,
            'clases_canceladas' => $clasesCanceladas,
            'ingresos_totales' => $ingresosTotales,
            'calificacion_promedio' => $calificacionPromedio,
            'total_estudiantes' => count($estudiantesUnicos),
            'promedio_clases_por_estudiante' => count($estudiantesUnicos) > 0 ? $totalClases / count($estudiantesUnicos) : 0
        ];

        // Verificar que los datos sean válidos antes de pasar a la vista
        if (empty($reservas) && empty($pagos) && empty($reviews)) {
            error_log("ADVERTENCIA: No se encontraron datos para el reporte del profesor");
            // Crear datos por defecto para evitar errores en la vista
            $reservas = [];
            $pagos = [];
            $reviews = [];
            $estudiantesUnicos = [];
            $topEstudiantes = [];
            $estadisticas = [
                'total_clases' => 0,
                'clases_completadas' => 0,
                'clases_pendientes' => 0,
                'clases_canceladas' => 0,
                'ingresos_totales' => 0,
                'calificacion_promedio' => 0,
                'total_estudiantes' => 0,
                'promedio_clases_por_estudiante' => 0
            ];
        }

        // Log final antes de pasar a la vista
        error_log("=== REPORTE PROFESOR - DATOS FINALES ===");
        error_log("Datos a pasar a la vista:");
        error_log("- userData: " . json_encode($userData));
        error_log("- estadisticas: " . json_encode($estadisticas));
        error_log("- clases count: " . count($reservas));
        error_log("- topEstudiantes count: " . count($topEstudiantes));
        error_log("- calificaciones count: " . count($reviews));
        error_log("=== FIN LOG ===");

        // Verificar que todas las variables requeridas estén definidas
        $required_vars = ['userData', 'clases', 'estadisticas', 'topEstudiantes', 'calificacionesRecientes', 'filtros'];
        foreach ($required_vars as $var) {
            if (!isset($$var)) {
                error_log("ERROR: Variable requerida '$var' no está definida");
            }
        }

        $data = [
            'userData' => $userData,
            'clases' => $reservas,
            'estadisticas' => $estadisticas,
            'topEstudiantes' => $topEstudiantes,
            'calificacionesRecientes' => array_slice($reviews, 0, 5),
            'filtros' => [
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin
            ]
        ];

        // Hacer disponibles las variables para la vista
        extract($data);

        // Debug: Verificar que las variables estén disponibles
        error_log("=== REPORTE PROFESOR - DEBUG ===");
        error_log("Variables para vista - userData: " . ($userData['first_name'] ?? 'no definido'));
        error_log("Variables para vista - estadisticas total_clases: " . ($estadisticas['total_clases'] ?? 'no definido'));
        error_log("Variables para vista - clases count: " . count($reservas));
        error_log("Variables para vista - topEstudiantes count: " . count($topEstudiantes));
        error_log("Variables para vista - calificaciones count: " . count($reviews));
        error_log("Variables para vista - ingresos totales: " . ($estadisticas['ingresos_totales'] ?? 'no definido'));
        error_log("=== FIN DEBUG ===");

        require_once 'views/reportes/reporte_profesor.php';
    }


    /**
     * Función para mostrar reporte específico de estudiante
     * Accesible para estudiantes, profesores y administradores
     */
    public function reporteEstudiante()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador', 'profesor', 'estudiante']);

        $estudianteId = $_GET['estudiante_id'] ?? $_SESSION['user_id'];

        // Si es estudiante, solo puede ver su propio reporte
        if ($_SESSION['role'] === 'estudiante' && $estudianteId != $_SESSION['user_id']) {
            header('Location: /plataforma-clases-online/reportes/estudiante?error=no_autorizado');
            exit;
        }

        require_once 'models/ReservaModel.php';
        require_once 'models/PagoModel.php';
        require_once 'models/ProfesorModel.php';
        require_once 'models/ReviewModel.php';
        require_once 'models/EstudianteModel.php';

        $reservaModel = new ReservaModel();
        $pagoModel = new PagoModel();
        $profesorModel = new ProfesorModel();
        $reviewModel = new ReviewModel();
        $estudianteModel = new EstudianteModel();

        // Obtener datos del estudiante
        $estudiante = $estudianteModel->getEstudianteById($estudianteId);
        $userData = [
            'first_name' => $estudiante['first_name'] ?? 'Usuario',
            'last_name' => $estudiante['last_name'] ?? 'Desconocido',
            'user_id' => $estudianteId
        ];

        // Obtener filtros de fecha
        $fechaInicio = $_GET['fecha_inicio'] ?? '';
        $fechaFin = $_GET['fecha_fin'] ?? '';

        // Obtener datos con filtros - Nota: Los modelos actuales no tienen métodos específicos por fecha para estudiantes
        $reservas = $reservaModel->getReservasByEstudianteWithDetails($estudianteId);
        $pagos = $pagoModel->getPagosByEstudiante($estudianteId);

        // Filtrar por fecha si se proporcionan (ya que no hay métodos específicos)
        if (!empty($fechaInicio) && !empty($fechaFin)) {
            $reservas = array_filter($reservas, function($reserva) use ($fechaInicio, $fechaFin) {
                $fechaClase = $reserva['class_date'] ?? '';
                return $fechaClase >= $fechaInicio && $fechaClase <= $fechaFin;
            });
        }

        // Calcular estadísticas
        $totalClases = count($reservas);
        $clasesCompletadas = count(array_filter($reservas, function($r) {
            return strtolower($r['reservation_status'] ?? '') === 'completada';
        }));
        $clasesPendientes = count(array_filter($reservas, function($r) {
            return in_array(strtolower($r['reservation_status'] ?? ''), ['pendiente', 'confirmada']);
        }));
        $totalInvertido = array_sum(array_column($pagos, 'amount'));

        // Profesores únicos
        $profesoresUnicos = array_unique(array_column($reservas, 'user_id'));
        $profesoresActivos = count($profesoresUnicos);

        // Calificaciones dadas por el estudiante - Nota: Este método no existe, usar getReviews()
        $allReviews = $reviewModel->getReviews();
        $reviews = array_filter($allReviews, function($review) use ($estudianteId) {
            return $review['reviewer_user_id'] == $estudianteId;
        });

        $estadisticas = [
            'total_clases' => $totalClases,
            'clases_completadas' => $clasesCompletadas,
            'clases_pendientes' => $clasesPendientes,
            'total_invertido' => $totalInvertido,
            'profesores_activos' => $profesoresActivos,
            'promedio_inversion' => $totalClases > 0 ? $totalInvertido / $totalClases : 0,
            'calificaciones_dadas' => count($reviews)
        ];

        $data = [
            'userData' => $userData,
            'clases' => $reservas,
            'pagos' => $pagos,
            'estadisticas' => $estadisticas,
            'profesores' => $profesoresUnicos,
            'reviews' => $reviews,
            'filtros' => [
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin
            ]
        ];

        extract($data);
        require_once 'views/reportes/reporte_estudiante.php';
    }

    /**
     * Función para mostrar reporte administrativo general
     * Accesible solo para administradores
     */
    public function reporteGeneral()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador']);

        require_once 'models/ReservaModel.php';
        require_once 'models/PagoModel.php';
        require_once 'models/ProfesorModel.php';
        require_once 'models/EstudianteModel.php';
        require_once 'models/ReviewModel.php';

        $reservaModel = new ReservaModel();
        $pagoModel = new PagoModel();
        $profesorModel = new ProfesorModel();
        $estudianteModel = new EstudianteModel();
        $reviewModel = new ReviewModel();

        // Obtener filtros de fecha
        $fechaInicio = $_GET['fecha_inicio'] ?? '';
        $fechaFin = $_GET['fecha_fin'] ?? '';

        // Obtener datos - Nota: Los modelos no tienen métodos específicos por fecha para datos generales
        $reservas = $reservaModel->getReservas();
        $pagos = $pagoModel->getPagos();

        // Filtrar por fecha si se proporcionan
        if (!empty($fechaInicio) && !empty($fechaFin)) {
            $reservas = array_filter($reservas, function($reserva) use ($fechaInicio, $fechaFin) {
                $fechaClase = $reserva['class_date'] ?? '';
                return $fechaClase >= $fechaInicio && $fechaClase <= $fechaFin;
            });
        }

        $profesores = $profesorModel->getProfesores();
        $estudiantes = $estudianteModel->getEstudiantes();
        $reviews = $reviewModel->getReviews();

        // Calcular estadísticas generales - solo pagos completados
        $pagosCompletados = array_filter($pagos, function($p) {
            return in_array($p['payment_status_id'], [2, 3]); // Solo completados/pagados
        });
        $totalIngresos = array_sum(array_column($pagosCompletados, 'amount'));
        $reservasActivas = count(array_filter($reservas, function($r) {
            return in_array(strtolower($r['reservation_status'] ?? ''), ['pendiente', 'confirmada']);
        }));
        $promedioCalificacion = !empty($reviews) ? array_sum(array_column($reviews, 'rating')) / count($reviews) : 0;

        // Estadísticas por profesor
        $profesorStats = [];
        foreach ($profesores as $profesor) {
            $profesorReservas = array_filter($reservas, function($r) use ($profesor) {
                return $r['user_id'] == $profesor['user_id'];
            });
            $profesorPagos = array_filter($pagos, function($p) use ($profesor) {
                return $p['profesor_user_id'] == $profesor['user_id'] && in_array($p['payment_status_id'], [2, 3]); // Solo completados/pagados
            });

            $profesorStats[] = [
                'nombre' => $profesor['first_name'] . ' ' . $profesor['last_name'],
                'clases' => count($profesorReservas),
                'ingresos' => array_sum(array_column($profesorPagos, 'amount')),
                'estudiantes' => count(array_unique(array_column($profesorReservas, 'student_user_id')))
            ];
        }

        // Estadísticas por estudiante
        $estudianteStats = [];
        foreach ($estudiantes as $estudiante) {
            $estudianteReservas = array_filter($reservas, function($r) use ($estudiante) {
                return $r['student_user_id'] == $estudiante['user_id'];
            });
            $estudiantePagos = array_filter($pagos, function($p) use ($estudiante) {
                return $p['user_id'] == $estudiante['user_id'] && in_array($p['payment_status_id'], [2, 3]); // Solo completados/pagados
            });

            $estudianteStats[] = [
                'nombre' => $estudiante['first_name'] . ' ' . $estudiante['last_name'],
                'clases' => count($estudianteReservas),
                'invertido' => array_sum(array_column($estudiantePagos, 'amount')),
                'profesores' => count(array_unique(array_column($estudianteReservas, 'user_id')))
            ];
        }

        $data = [
            'estadisticas' => [
                'total_profesores' => count($profesores),
                'total_estudiantes' => count($estudiantes),
                'total_reservas' => count($reservas),
                'reservas_activas' => $reservasActivas,
                'total_ingresos' => $totalIngresos,
                'promedio_calificacion' => $promedioCalificacion,
                'total_reviews' => count($reviews)
            ],
            'profesorStats' => $profesorStats,
            'estudianteStats' => $estudianteStats,
            'filtros' => [
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin
            ]
        ];

        extract($data);
        require_once 'views/reportes/reporte_general.php';
    }

    /**
     * Función para manejar exportaciones de reportes
     */
    public function exportar()
    {
        AuthController::checkAuth();

        $tipo = $_GET['tipo'] ?? 'pdf';
        $tipoReporte = $_GET['tipo_reporte'] ?? 'general';
        $fechaInicio = $_GET['fecha_inicio'] ?? '';
        $fechaFin = $_GET['fecha_fin'] ?? '';

        switch ($tipo) {
            case 'pdf':
                $this->exportarPDF($tipoReporte, $fechaInicio, $fechaFin);
                break;
            case 'excel':
                $this->exportarExcel($tipoReporte, $fechaInicio, $fechaFin);
                break;
            case 'csv':
                $this->exportarCSV($tipoReporte, $fechaInicio, $fechaFin);
                break;
            case 'email':
                $this->enviarPorEmail($tipoReporte, $fechaInicio, $fechaFin);
                break;
            default:
                header('Location: /plataforma-clases-online/reportes?error=tipo_invalido');
                exit;
        }
    }

    /**
     * Función para mostrar reporte de reservas
     */
    public function reporteReservas()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador', 'profesor']);

        require_once 'models/ReservaModel.php';
        $reservaModel = new ReservaModel();

        $fechaInicio = $_GET['fecha_inicio'] ?? '';
        $fechaFin = $_GET['fecha_fin'] ?? '';

        $reservas = $reservaModel->getReservas();

        // Filtrar por fecha si se proporcionan
        if (!empty($fechaInicio) && !empty($fechaFin)) {
            $reservas = array_filter($reservas, function($reserva) use ($fechaInicio, $fechaFin) {
                $fechaClase = $reserva['class_date'] ?? '';
                return $fechaClase >= $fechaInicio && $fechaClase <= $fechaFin;
            });
        }

        // Calcular estadísticas de reservas
        $totalReservas = count($reservas);
        $reservasCompletadas = count(array_filter($reservas, function($r) {
            return strtolower($r['reservation_status'] ?? '') === 'completada';
        }));
        $reservasPendientes = count(array_filter($reservas, function($r) {
            return in_array(strtolower($r['reservation_status'] ?? ''), ['pendiente', 'confirmada']);
        }));
        $reservasCanceladas = count(array_filter($reservas, function($r) {
            return strtolower($r['reservation_status'] ?? '') === 'cancelada';
        }));

        $data = [
            'reservas' => $reservas,
            'estadisticas' => [
                'total' => $totalReservas,
                'completadas' => $reservasCompletadas,
                'pendientes' => $reservasPendientes,
                'canceladas' => $reservasCanceladas,
                'tasa_completacion' => $totalReservas > 0 ? ($reservasCompletadas / $totalReservas) * 100 : 0
            ],
            'filtros' => [
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin
            ]
        ];

        extract($data);
        require_once 'views/reportes/reporte_reservas.php';
    }

    /**
     * Función para mostrar reporte de ingresos por período
     */
    public function reporteIngresos()
    {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador']);

        require_once 'models/PagoModel.php';
        $pagoModel = new PagoModel();

        $periodo = $_GET['periodo'] ?? 'mensual';
        $fechaInicio = $_GET['fecha_inicio'] ?? '';
        $fechaFin = $_GET['fecha_fin'] ?? '';

        // Calcular ingresos por período manualmente ya que no existe el método
        $ingresosData = $this->calcularIngresosPorPeriodo($periodo, $fechaInicio, $fechaFin);

        $data = [
            'ingresos' => $ingresosData,
            'periodo' => $periodo,
            'filtros' => [
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin
            ]
        ];

        extract($data);
        require_once 'views/reportes/reporte_ingresos.php';
    }

    // FUNCIONES AUXILIARES

    /**
     * Obtener estadísticas de métodos de pago
     */
    private function getMetodosPagoStats($pagos)
    {
        $stats = [];
        foreach ($pagos as $pago) {
            $metodo = $pago['payment_method'] ?? 'No especificado';
            $stats[$metodo] = ($stats[$metodo] ?? 0) + 1;
        }
        return $stats;
    }

    /**
     * Calcular ingresos por período
     */
    private function calcularIngresosPorPeriodo($periodo, $fechaInicio, $fechaFin)
    {
        require_once 'models/PagoModel.php';
        $pagoModel = new PagoModel();

        $pagos = $pagoModel->getPagos();

        // Filtrar por fecha si se proporcionan
        if (!empty($fechaInicio) && !empty($fechaFin)) {
            $pagos = array_filter($pagos, function($pago) use ($fechaInicio, $fechaFin) {
                $fechaPago = $pago['payment_date'] ?? '';
                return $fechaPago >= $fechaInicio && $fechaPago <= $fechaFin;
            });
        }

        // Agrupar por período
        $ingresosData = [];
        foreach ($pagos as $pago) {
            $fechaPago = $pago['payment_date'] ?? '';
            if (empty($fechaPago)) continue;

            switch ($periodo) {
                case 'diario':
                    $key = date('Y-m-d', strtotime($fechaPago));
                    break;
                case 'semanal':
                    $key = date('Y-W', strtotime($fechaPago));
                    break;
                case 'mensual':
                    $key = date('Y-m', strtotime($fechaPago));
                    break;
                case 'anual':
                    $key = date('Y', strtotime($fechaPago));
                    break;
                default:
                    $key = date('Y-m', strtotime($fechaPago));
            }

            if (!isset($ingresosData[$key])) {
                $ingresosData[$key] = [
                    'periodo' => $key,
                    'ingresos' => 0,
                    'cantidad' => 0
                ];
            }

            $ingresosData[$key]['ingresos'] += $pago['amount'];
            $ingresosData[$key]['cantidad']++;
        }

        return array_values($ingresosData);
    }

    /**
     * Exportar reporte como PDF
     */
    private function exportarPDF($tipoReporte, $fechaInicio, $fechaFin)
    {
        // Para desarrollo, generar HTML que puede ser guardado como PDF desde el navegador
        header('Content-Type: text/html; charset=utf-8');

        // Obtener datos según el tipo de reporte
        $data = $this->getDatosReportePDF($tipoReporte, $fechaInicio, $fechaFin);

        // Generar HTML para PDF
        $html = $this->generarHTMLReportePDF($tipoReporte, $data, $fechaInicio, $fechaFin);

        echo $html;
        exit;
    }

    /**
     * Obtener datos para el reporte PDF
     */
    private function getDatosReportePDF($tipoReporte, $fechaInicio, $fechaFin)
    {
        require_once 'models/ReservaModel.php';
        require_once 'models/PagoModel.php';
        require_once 'models/ReviewModel.php';
        require_once 'models/EstudianteModel.php';

        $reservaModel = new ReservaModel();
        $pagoModel = new PagoModel();
        $reviewModel = new ReviewModel();
        $estudianteModel = new EstudianteModel();

        $profesorId = $_SESSION['user_id'];

        // Obtener datos base
        $reservas = $reservaModel->getReservasByProfesor($profesorId, $fechaInicio, $fechaFin);
        $pagos = $pagoModel->getPagosByProfesor($profesorId);
        $reviews = $reviewModel->getReviewsByProfesor($profesorId, $fechaInicio, $fechaFin);

        // Filtrar pagos por fecha si se proporcionan
        if (!empty($fechaInicio) && !empty($fechaFin)) {
            $pagos = array_filter($pagos, function($pago) use ($fechaInicio, $fechaFin) {
                $fechaPago = $pago['payment_date'] ?? '';
                return $fechaPago >= $fechaInicio && $fechaPago <= $fechaFin;
            });
        }

        // Obtener estudiantes únicos
        $estudiantesUnicos = array_unique(array_column($reservas, 'student_user_id'));
        $estudiantes = [];
        foreach ($estudiantesUnicos as $estId) {
            $estudiante = $estudianteModel->getEstudianteById($estId);
            if ($estudiante) {
                $estudiantes[] = $estudiante;
            }
        }

        return [
            'reservas' => $reservas,
            'pagos' => array_values($pagos),
            'reviews' => $reviews,
            'estudiantes' => $estudiantes
        ];
    }

    /**
     * Generar HTML completo para reporte PDF
     */
    private function generarHTMLReportePDF($tipoReporte, $data, $fechaInicio, $fechaFin)
    {
        $profesorId = $_SESSION['user_id'];

        // Obtener información del profesor
        require_once 'models/ProfesorModel.php';
        $profesorModel = new ProfesorModel();
        $profesor = $profesorModel->getProfesorById($profesorId);

        $nombreProfesor = $profesor['first_name'] . ' ' . $profesor['last_name'];

        // Calcular estadísticas
        $totalClases = count($data['reservas']);
        $totalEstudiantes = count($data['estudiantes']);
        $ingresosTotales = array_sum(array_column($data['pagos'], 'amount'));
        $calificacionPromedio = !empty($data['reviews']) ? array_sum(array_column($data['reviews'], 'rating')) / count($data['reviews']) : 0;

        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Reporte Completo - ' . $nombreProfesor . '</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; color: #333; }
                .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #371783; padding-bottom: 20px; }
                .header h1 { color: #371783; margin: 0; font-size: 24px; }
                .header h2 { color: #666; margin: 10px 0 0 0; font-size: 18px; font-weight: normal; }
                .fecha-reporte { text-align: right; color: #666; margin-bottom: 20px; font-size: 12px; }

                .estadisticas { display: table; width: 100%; margin-bottom: 30px; }
                .estadistica { display: table-cell; width: 25%; padding: 15px; text-align: center; background: #f8f9fa; border: 1px solid #dee2e6; }
                .estadistica .valor { font-size: 20px; font-weight: bold; margin-bottom: 5px; }
                .estadistica .etiqueta { font-size: 12px; color: #666; }
                .clases .valor { color: #36b9cc; }
                .estudiantes .valor { color: #1cc88a; }
                .ingresos .valor { color: #e74a3b; }
                .calificacion .valor { color: #f6c23e; }

                .section { margin-bottom: 30px; }
                .section h3 { color: #371783; border-bottom: 2px solid #8B5A96; padding-bottom: 5px; font-size: 16px; }

                table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px; }
                th { background-color: #371783; color: white; font-weight: bold; }
                tr:nth-child(even) { background-color: #f8f9fc; }

                .text-center { text-align: center; }
                .text-right { text-align: right; }
                .mb-2 { margin-bottom: 10px; }
                .mt-3 { margin-top: 15px; }

                @media print {
                    body { margin: 0; }
                    .no-print { display: none; }
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>📊 REPORTE COMPLETO DEL PROFESOR</h1>
                <h2>' . htmlspecialchars($nombreProfesor) . '</h2>
            </div>

            <div class="fecha-reporte">
                <strong>Generado el:</strong> ' . date('d/m/Y H:i:s') . '
            </div>

            <!-- Estadísticas Principales -->
            <div class="estadisticas">
                <div class="estadistica clases">
                    <div class="valor">' . $totalClases . '</div>
                    <div class="etiqueta">Total Clases</div>
                </div>
                <div class="estadistica estudiantes">
                    <div class="valor">' . $totalEstudiantes . '</div>
                    <div class="etiqueta">Estudiantes Activos</div>
                </div>
                <div class="estadistica ingresos">
                    <div class="valor">$ ' . number_format($ingresosTotales, 2) . '</div>
                    <div class="etiqueta">Ingresos Totales</div>
                </div>
                <div class="estadistica calificacion">
                    <div class="valor">' . number_format($calificacionPromedio, 1) . '</div>
                    <div class="etiqueta">Calificación Promedio</div>
                </div>
            </div>

            <!-- Información de Estudiantes -->
            <div class="section">
                <h3>🎓 ESTUDIANTES ACTIVOS</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Clases Tomadas</th>
                            <th>Total Invertido</th>
                        </tr>
                    </thead>
                    <tbody>';

        // Datos de estudiantes
        foreach ($data['estudiantes'] as $estudiante) {
            $clasesEstudiante = count(array_filter($data['reservas'], function($reserva) use ($estudiante) {
                return $reserva['student_user_id'] == $estudiante['user_id'];
            }));
            $pagosEstudiante = array_filter($data['pagos'], function($pago) use ($estudiante) {
                return $pago['user_id'] == $estudiante['user_id'];
            });
            $totalInvertido = array_sum(array_column($pagosEstudiante, 'amount'));

            $html .= '
                        <tr>
                            <td>' . htmlspecialchars($estudiante['first_name'] . ' ' . $estudiante['last_name']) . '</td>
                            <td>' . htmlspecialchars($estudiante['email']) . '</td>
                            <td class="text-center">' . $clasesEstudiante . '</td>
                            <td class="text-right">$ ' . number_format($totalInvertido, 2) . '</td>
                        </tr>';
        }

        $html .= '
                    </tbody>
                </table>
            </div>

            <!-- Información Financiera -->
            <div class="section">
                <h3>💰 INFORMACIÓN FINANCIERA</h3>
                <table>
                    <thead>
                        <tr>
                            <th>ID Pago</th>
                            <th>Estudiante</th>
                            <th>Monto</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($data['pagos'] as $pago) {
            $html .= '
                        <tr>
                            <td>' . htmlspecialchars($pago['payment_id']) . '</td>
                            <td>' . htmlspecialchars($pago['first_name'] . ' ' . $pago['last_name']) . '</td>
                            <td class="text-right">$ ' . number_format($pago['amount'], 2) . '</td>
                            <td>' . htmlspecialchars($pago['payment_date']) . '</td>
                            <td>' . htmlspecialchars($pago['payment_status']) . '</td>
                        </tr>';
        }

        $html .= '
                    </tbody>
                </table>
            </div>

            <!-- Información de Clases -->
            <div class="section">
                <h3>📚 INFORMACIÓN DE CLASES</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Estudiante</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($data['reservas'] as $reserva) {
            $html .= '
                        <tr>
                            <td>' . htmlspecialchars($reserva['estudiante_name'] . ' ' . $reserva['estudiante_last_name']) . '</td>
                            <td>' . htmlspecialchars($reserva['class_date']) . '</td>
                            <td>' . htmlspecialchars($reserva['start_time']) . '</td>
                            <td>' . htmlspecialchars($reserva['reservation_status']) . '</td>
                        </tr>';
        }

        $html .= '
                    </tbody>
                </table>
            </div>

            <!-- Calificaciones -->
            <div class="section">
                <h3>⭐ CALIFICACIONES RECIENTES</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Estudiante</th>
                            <th>Calificación</th>
                            <th>Fecha</th>
                            <th>Comentario</th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($data['reviews'] as $review) {
            $html .= '
                        <tr>
                            <td>' . htmlspecialchars($review['estudiante_name'] . ' ' . $review['estudiante_last_name']) . '</td>
                            <td class="text-center">' . htmlspecialchars($review['rating']) . '/5</td>
                            <td>' . htmlspecialchars($review['created_at'] ?? date('Y-m-d')) . '</td>
                            <td>' . htmlspecialchars(substr($review['comment'] ?? '', 0, 50)) . '...</td>
                        </tr>';
        }

        $html .= '
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 50px; text-align: center; font-size: 10px; color: #666; border-top: 1px solid #ddd; padding-top: 20px;">
                <p>Reporte generado automáticamente por la Plataforma de Clases Online</p>
                <p>Fecha de generación: ' . date('d/m/Y H:i:s') . '</p>
            </div>
        </body>
        </html>';

        return $html;
    }

    /**
     * Exportar reporte como Excel
     */
    private function exportarExcel($tipoReporte, $fechaInicio, $fechaFin)
    {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="reporte_' . $tipoReporte . '_' . date('Y-m-d') . '.xls"');

        // Obtener datos según el tipo de reporte
        $data = $this->getDatosParaExportacion($tipoReporte, $fechaInicio, $fechaFin);

        // Generar contenido Excel (usando formato CSV compatible con Excel)
        $this->generarContenidoExcel($data);
        exit;
    }

    /**
     * Exportar reporte como CSV
     */
    private function exportarCSV($tipoReporte, $fechaInicio, $fechaFin)
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="reporte_' . $tipoReporte . '_' . date('Y-m-d') . '.csv"');

        // Obtener datos según el tipo de reporte
        $data = $this->getDatosParaExportacion($tipoReporte, $fechaInicio, $fechaFin);

        // Generar contenido CSV
        $this->generarContenidoCSV($data);
        exit;
    }

    /**
     * Obtener datos para exportación según el tipo de reporte
     */
    private function getDatosParaExportacion($tipoReporte, $fechaInicio, $fechaFin)
    {
        require_once 'models/ReservaModel.php';
        require_once 'models/PagoModel.php';
        require_once 'models/ReviewModel.php';
        require_once 'models/EstudianteModel.php';

        $reservaModel = new ReservaModel();
        $pagoModel = new PagoModel();
        $reviewModel = new ReviewModel();
        $estudianteModel = new EstudianteModel();

        $profesorId = $_SESSION['user_id'];

        // Obtener datos base
        $reservas = $reservaModel->getReservasByProfesor($profesorId, $fechaInicio, $fechaFin);
        $pagos = $pagoModel->getPagosByProfesor($profesorId);
        $reviews = $reviewModel->getReviewsByProfesor($profesorId, $fechaInicio, $fechaFin);

        // Filtrar pagos por fecha si se proporcionan
        if (!empty($fechaInicio) && !empty($fechaFin)) {
            $pagos = array_filter($pagos, function($pago) use ($fechaInicio, $fechaFin) {
                $fechaPago = $pago['payment_date'] ?? '';
                return $fechaPago >= $fechaInicio && $fechaPago <= $fechaFin;
            });
        }

        // Obtener estudiantes únicos
        $estudiantesUnicos = array_unique(array_column($reservas, 'student_user_id'));
        $estudiantes = [];
        foreach ($estudiantesUnicos as $estId) {
            $estudiante = $estudianteModel->getEstudianteById($estId);
            if ($estudiante) {
                $estudiantes[] = $estudiante;
            }
        }

        return [
            'reservas' => $reservas,
            'pagos' => array_values($pagos),
            'reviews' => $reviews,
            'estudiantes' => $estudiantes
        ];
    }

    /**
     * Generar contenido Excel
     */
    private function generarContenidoExcel($data)
    {
        // Encabezados para Excel
        echo "Estudiante\tMateria\tTema/Clase\tFecha\tHora\tPrecio\tEstado\tCalificación\n";

        // Combinar datos de reservas, pagos y reviews
        $filas = [];

        foreach ($data['reservas'] as $reserva) {
            $fila = [
                $reserva['estudiante_name'] . ' ' . $reserva['estudiante_last_name'],
                $reserva['subject_name'] ?? 'Materia',
                $reserva['notes'] ?? 'Clase general',
                $reserva['class_date'],
                $reserva['start_time'],
                $reserva['hourly_rate'] ?? 0,
                $reserva['reservation_status'],
                ''  // Calificación vacía para reservas
            ];
            $filas[] = $fila;
        }

        foreach ($data['pagos'] as $pago) {
            $fila = [
                $pago['first_name'] . ' ' . $pago['last_name'],
                'Pago',
                'Pago realizado',
                $pago['payment_date'],
                '',
                $pago['amount'],
                'Pago',
                ''
            ];
            $filas[] = $fila;
        }

        foreach ($data['reviews'] as $review) {
            $fila = [
                $review['estudiante_name'] . ' ' . $review['estudiante_last_name'],
                'Review',
                'Calificación del estudiante',
                $review['created_at'] ?? date('Y-m-d'),
                '',
                '',
                'Review',
                $review['rating']
            ];
            $filas[] = $fila;
        }

        // Ordenar por fecha
        usort($filas, function($a, $b) {
            return strtotime($a[3]) - strtotime($b[3]);
        });

        // Imprimir filas
        foreach ($filas as $fila) {
            echo implode("\t", $fila) . "\n";
        }
    }

    /**
     * Generar contenido CSV
     */
    private function generarContenidoCSV($data)
    {
        // Encabezados para CSV
        echo "Estudiante,Materia,Tema/Clase,Fecha,Hora,Precio,Estado,Calificación\n";

        // Combinar datos de reservas, pagos y reviews
        $filas = [];

        foreach ($data['reservas'] as $reserva) {
            $fila = [
                $reserva['estudiante_name'] . ' ' . $reserva['estudiante_last_name'],
                $reserva['subject_name'] ?? 'Materia',
                $reserva['notes'] ?? 'Clase general',
                $reserva['class_date'],
                $reserva['start_time'],
                $reserva['hourly_rate'] ?? 0,
                $reserva['reservation_status'],
                ''  // Calificación vacía para reservas
            ];
            $filas[] = $fila;
        }

        foreach ($data['pagos'] as $pago) {
            $fila = [
                $pago['first_name'] . ' ' . $pago['last_name'],
                'Pago',
                'Pago realizado',
                $pago['payment_date'],
                '',
                $pago['amount'],
                'Pago',
                ''
            ];
            $filas[] = $fila;
        }

        foreach ($data['reviews'] as $review) {
            $fila = [
                $review['estudiante_name'] . ' ' . $review['estudiante_last_name'],
                'Review',
                'Calificación del estudiante',
                $review['created_at'] ?? date('Y-m-d'),
                '',
                '',
                'Review',
                $review['rating']
            ];
            $filas[] = $fila;
        }

        // Ordenar por fecha
        usort($filas, function($a, $b) {
            return strtotime($a[3]) - strtotime($b[3]);
        });

        // Imprimir filas
        foreach ($filas as $fila) {
            // Escapar comas y comillas si es necesario
            $filaEscapada = array_map(function($valor) {
                return '"' . str_replace('"', '""', $valor) . '"';
            }, $fila);
            echo implode(',', $filaEscapada) . "\n";
        }
    }

    /**
     * Enviar reporte por email
     */
    private function enviarPorEmail($tipoReporte, $fechaInicio, $fechaFin)
    {
        // Aquí iría la lógica para enviar reporte por email
        $response = [
            'success' => true,
            'message' => 'Reporte enviado por email correctamente'
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}
?>