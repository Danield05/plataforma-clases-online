<?php
class ReservaModel {
    private $db;

    public function __construct() {
        global $pdo;
        $this->db = $pdo;
    }

    public function getReservas() {
        $stmt = $this->db->query("SELECT r.*, u.first_name as profesor_name, u.last_name as profesor_last_name, s.first_name as estudiante_name, s.last_name as estudiante_last_name, er.status as reservation_status, d.start_time, d.end_time FROM reservas r JOIN usuarios u ON r.user_id = u.user_id JOIN usuarios s ON r.student_user_id = s.user_id JOIN estados_reserva er ON r.reservation_status_id = er.reservation_status_id LEFT JOIN disponibilidad_profesores d ON r.availability_id = d.availability_id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReservaById($id) {
        $stmt = $this->db->prepare("
            SELECT 
                r.*,
                u_profesor.first_name as profesor_name, 
                u_profesor.last_name as profesor_last_name,
                u_profesor.email as profesor_email,
                u_estudiante.first_name as estudiante_name, 
                u_estudiante.last_name as estudiante_last_name,
                er.status as reservation_status,
                prof.hourly_rate,
                prof.academic_level,
                prof.personal_description,
                d.start_time,
                d.end_time,
                d.price_per_hour as availability_price,
                ds.day as day_name,
                mat.subject_name
            FROM reservas r 
            JOIN usuarios u_profesor ON r.user_id = u_profesor.user_id 
            JOIN usuarios u_estudiante ON r.student_user_id = u_estudiante.user_id 
            JOIN estados_reserva er ON r.reservation_status_id = er.reservation_status_id
            LEFT JOIN profesor prof ON r.user_id = prof.user_id
            LEFT JOIN disponibilidad_profesores d ON r.availability_id = d.availability_id
            LEFT JOIN dias_semana ds ON d.week_day_id = ds.week_day_id
            LEFT JOIN materias mat ON d.subject_id = mat.subject_id
            WHERE r.reservation_id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function reservaExists($id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM reservas WHERE reservation_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }

    public function getReservasByEstudiante($studentUserId) {
        $stmt = $this->db->prepare("SELECT DISTINCT r.*, u.first_name as profesor_name, er.status as reservation_status, d.start_time, d.end_time FROM reservas r JOIN usuarios u ON r.user_id = u.user_id JOIN estados_reserva er ON r.reservation_status_id = er.reservation_status_id LEFT JOIN disponibilidad_profesores d ON r.availability_id = d.availability_id WHERE r.student_user_id = ? ORDER BY r.class_date ASC");
        $stmt->execute([$studentUserId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReservasByEstudianteWithDetails($studentUserId) {
        $stmt = $this->db->prepare("
            SELECT DISTINCT r.reservation_id,
                   (SELECT MAX(u.first_name) FROM usuarios u WHERE u.user_id = r.user_id) as profesor_name,
                   (SELECT MAX(u.last_name) FROM usuarios u WHERE u.user_id = r.user_id) as profesor_last_name,
                   (SELECT MAX(u.user_id) FROM usuarios u WHERE u.user_id = r.user_id) as profesor_user_id,
                   (SELECT MAX(er.status) FROM estados_reserva er WHERE er.reservation_status_id = r.reservation_status_id) as reservation_status,
                   (SELECT MAX(d.start_time) FROM disponibilidad_profesores d WHERE d.availability_id = r.availability_id) as start_time,
                   (SELECT MAX(d.end_time) FROM disponibilidad_profesores d WHERE d.availability_id = r.availability_id) as end_time,
                   (SELECT MAX(prof.personal_description) FROM profesor prof WHERE prof.user_id = r.user_id) as personal_description,
                   (SELECT MAX(prof.academic_level) FROM profesor prof WHERE prof.user_id = r.user_id) as academic_level,
                   (SELECT MAX(prof.hourly_rate) FROM profesor prof WHERE prof.user_id = r.user_id) as hourly_rate,
                   (SELECT MAX(r2.class_date) FROM reservas r2 WHERE r2.reservation_id = r.reservation_id) as class_date,
                   (SELECT MAX(r2.class_time) FROM reservas r2 WHERE r2.reservation_id = r.reservation_id) as class_time,
                   (SELECT MAX(r2.notes) FROM reservas r2 WHERE r2.reservation_id = r.reservation_id) as notes,
                   (SELECT MAX(r2.availability_id) FROM reservas r2 WHERE r2.reservation_id = r.reservation_id) as availability_id,
                   (SELECT MAX(r2.reservation_status_id) FROM reservas r2 WHERE r2.reservation_id = r.reservation_id) as reservation_status_id,
                   (SELECT MAX(r2.student_user_id) FROM reservas r2 WHERE r2.reservation_id = r.reservation_id) as student_user_id,
                   (SELECT MAX(r2.user_id) FROM reservas r2 WHERE r2.reservation_id = r.reservation_id) as user_id
            FROM reservas r
            WHERE r.student_user_id = ?
            ORDER BY class_date ASC, class_time ASC
        ");
        $stmt->execute([$studentUserId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReservasByProfesor($profesorUserId, $fechaInicio = null, $fechaFin = null) {
        $query = "SELECT r.*, s.first_name as estudiante_name, s.last_name as estudiante_last_name, er.status as reservation_status, d.start_time, d.end_time, prof.academic_level, prof.hourly_rate, m.subject_name as subject_name FROM reservas r JOIN usuarios s ON r.student_user_id = s.user_id JOIN estados_reserva er ON r.reservation_status_id = er.reservation_status_id LEFT JOIN disponibilidad_profesores d ON r.availability_id = d.availability_id LEFT JOIN profesor prof ON r.user_id = prof.user_id LEFT JOIN materias m ON d.subject_id = m.subject_id WHERE r.user_id = ?";

        $params = [$profesorUserId];

        if ($fechaInicio) {
            $query .= " AND r.class_date >= ?";
            $params[] = $fechaInicio;
        }

        if ($fechaFin) {
            $query .= " AND r.class_date <= ?";
            $params[] = $fechaFin;
        }

        $query .= " ORDER BY r.class_date DESC, d.start_time DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Agregar el campo notes a cada reserva
        foreach ($reservas as &$reserva) {
            $reserva['notes'] = $reserva['notes'] ?? '';
        }

        return $reservas;
    }

    public function createReserva($data) {
        $stmt = $this->db->prepare("INSERT INTO reservas (reservation_id, user_id, student_user_id, availability_id, reservation_status_id, class_date) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['reservation_id'],
            $data['user_id'],
            $data['student_user_id'],
            $data['availability_id'],
            $data['reservation_status_id'],
            $data['class_date']
        ]);
    }

    public function checkAvailability($profesorId, $date, $availabilityId = null) {
        // Verificar si el slot está disponible (no reservado)
        $query = "SELECT COUNT(*) as count FROM reservas r
                  JOIN disponibilidad_profesores d ON r.availability_id = d.availability_id
                  WHERE d.user_id = ? AND r.class_date = ? AND r.reservation_status_id IN (1, 2)"; // 1=pendiente, 2=confirmada

        if ($availabilityId) {
            $query .= " AND r.availability_id = ?";
        }

        $stmt = $this->db->prepare($query);
        if ($availabilityId) {
            $stmt->execute([$profesorId, $date, $availabilityId]);
        } else {
            $stmt->execute([$profesorId, $date]);
        }

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] == 0;
    }

    public function getAvailableSlots($profesorId, $startDate = null, $endDate = null) {
        if (!$startDate) $startDate = date('Y-m-d');
        if (!$endDate) $endDate = date('Y-m-d', strtotime('+30 days'));

        $stmt = $this->db->prepare("
            SELECT d.*, ds.day, ed.status as availability_status,
                    CASE WHEN r.reservation_id IS NULL THEN 1 ELSE 0 END as available
            FROM disponibilidad_profesores d
            JOIN dias_semana ds ON d.week_day_id = ds.week_day_id
            JOIN estados_disponibilidad ed ON d.availability_status_id = ed.availability_status_id
            LEFT JOIN reservas r ON d.availability_id = r.availability_id
                AND r.class_date BETWEEN ? AND ?
                AND r.reservation_status_id IN (1, 2)
            WHERE d.user_id = ? AND d.availability_status_id = 1
            ORDER BY ds.day, d.start_time
        ");

        $stmt->execute([$startDate, $endDate, $profesorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createReservaWithCheck($data) {
        // Verificar disponibilidad antes de crear
        if (!$this->checkAvailability($data['user_id'], $data['class_date'], $data['availability_id'])) {
            return false; // No disponible
        }

        return $this->createReserva($data);
    }

    public function updateReservaStatus($id, $statusId) {
        $stmt = $this->db->prepare("UPDATE reservas SET reservation_status_id = ? WHERE reservation_id = ?");
        return $stmt->execute([$statusId, $id]);
    }

    public function cancelReserva($reservationId, $userId) {
        // Debug: Obtener información de la reserva
        $stmt = $this->db->prepare("SELECT r.*, er.status as estado_actual FROM reservas r JOIN estados_reserva er ON r.reservation_status_id = er.reservation_status_id WHERE reservation_id = ?");
        $stmt->execute([$reservationId]);
        $reserva = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$reserva) {
            return false; // Reserva no encontrada
        }

        // Verificar que la reserva pertenece al usuario (estudiante o profesor)
        $stmt = $this->db->prepare("SELECT * FROM reservas WHERE reservation_id = ? AND (student_user_id = ? OR user_id = ?)");
        $stmt->execute([$reservationId, $userId, $userId]);
        $reservaPropia = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$reservaPropia) {
            return false; // Reserva no pertenece al usuario
        }

        // Verificar que el estado actual permita cancelación (pendiente o confirmada)
        if (!in_array($reserva['reservation_status_id'], [1, 2])) {
            return false; // Estado no permite cancelación
        }

        // Cambiar estado a cancelado (ID 3)
        $stmt = $this->db->prepare("UPDATE reservas SET reservation_status_id = 3 WHERE reservation_id = ?");
        return $stmt->execute([$reservationId]);
    }

    public function reagendarReserva($reservationId, $newDate, $newAvailabilityId = null) {
        // Verificar que la reserva existe
        $reserva = $this->getReservaById($reservationId);
        if (!$reserva) {
            return false; // Reserva no encontrada
        }

        // Si se proporciona availability_id, verificar que esté disponible en la nueva fecha
        if ($newAvailabilityId) {
            if (!$this->checkAvailability($reserva['user_id'], $newDate, $newAvailabilityId)) {
                return false; // Slot no disponible
            }
        }

        // Actualizar la reserva con la nueva fecha y availability_id si se proporciona
        $stmt = $this->db->prepare("UPDATE reservas SET class_date = ?, availability_id = ? WHERE reservation_id = ?");
        return $stmt->execute([$newDate, $newAvailabilityId, $reservationId]);
    }

    public function fixEstadosReserva() {
        // Corregir los estados en la tabla estados_reserva con minúsculas
        $estados = [
            1 => 'Pendiente',
            2 => 'Confirmada',
            3 => 'Cancelada',
            4 => 'Completada'
        ];

        foreach ($estados as $id => $estado) {
            $stmt = $this->db->prepare("UPDATE estados_reserva SET status = ? WHERE reservation_status_id = ?");
            $stmt->execute([$estado, $id]);
        }

        return true;
    }
}
?>