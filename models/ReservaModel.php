<?php
class ReservaModel {
    private $db;

    public function __construct() {
        global $pdo;
        $this->db = $pdo;
    }

    public function getReservas() {
        $stmt = $this->db->query("SELECT r.*, u.first_name as profesor_name, s.first_name as estudiante_name, er.status as reservation_status FROM Reservas r JOIN Usuarios u ON r.user_id = u.user_id JOIN Usuarios s ON r.student_user_id = s.user_id JOIN Estados_Reserva er ON r.reservation_status_id = er.reservation_status_id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReservaById($id) {
        $stmt = $this->db->prepare("SELECT r.*, u.first_name as profesor_name, s.first_name as estudiante_name, er.status as reservation_status FROM Reservas r JOIN Usuarios u ON r.user_id = u.user_id JOIN Usuarios s ON r.student_user_id = s.user_id JOIN Estados_Reserva er ON r.reservation_status_id = er.reservation_status_id WHERE r.reservation_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getReservasByEstudiante($studentUserId) {
        $stmt = $this->db->prepare("SELECT r.*, u.first_name as profesor_name, er.status as reservation_status FROM Reservas r JOIN Usuarios u ON r.user_id = u.user_id JOIN Estados_Reserva er ON r.reservation_status_id = er.reservation_status_id WHERE r.student_user_id = ?");
        $stmt->execute([$studentUserId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReservasByProfesor($profesorUserId) {
        $stmt = $this->db->prepare("SELECT r.*, s.first_name as estudiante_name, er.status as reservation_status FROM Reservas r JOIN Usuarios s ON r.student_user_id = s.user_id JOIN Estados_Reserva er ON r.reservation_status_id = er.reservation_status_id WHERE r.user_id = ?");
        $stmt->execute([$profesorUserId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createReserva($data) {
        $stmt = $this->db->prepare("INSERT INTO Reservas (reservation_id, user_id, student_user_id, availability_id, reservation_status_id, class_date) VALUES (?, ?, ?, ?, ?, ?)");
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
        $query = "SELECT COUNT(*) as count FROM Reservas r
                  JOIN Disponibilidad_Profesores d ON r.availability_id = d.availability_id
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
            FROM Disponibilidad_Profesores d
            JOIN Dias_Semana ds ON d.week_day_id = ds.week_day_id
            JOIN Estados_Disponibilidad ed ON d.availability_status_id = ed.availability_status_id
            LEFT JOIN Reservas r ON d.availability_id = r.availability_id
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
        $stmt = $this->db->prepare("UPDATE Reservas SET reservation_status_id = ? WHERE reservation_id = ?");
        return $stmt->execute([$statusId, $id]);
    }

    public function cancelReserva($reservationId, $userId) {
        // Debug: Obtener información de la reserva
        $stmt = $this->db->prepare("SELECT r.*, er.status as estado_actual FROM Reservas r JOIN Estados_Reserva er ON r.reservation_status_id = er.reservation_status_id WHERE reservation_id = ?");
        $stmt->execute([$reservationId]);
        $reserva = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$reserva) {
            return false; // Reserva no encontrada
        }

        // Verificar que la reserva pertenece al usuario (estudiante o profesor)
        $stmt = $this->db->prepare("SELECT * FROM Reservas WHERE reservation_id = ? AND (student_user_id = ? OR user_id = ?)");
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
        $stmt = $this->db->prepare("UPDATE Reservas SET reservation_status_id = 3 WHERE reservation_id = ?");
        return $stmt->execute([$reservationId]);
    }

    public function fixEstadosReserva() {
        // Corregir los estados en la tabla Estados_Reserva con mayúsculas
        $estados = [
            1 => 'Pendiente',
            2 => 'Confirmada',
            3 => 'Cancelada',
            4 => 'Completada'
        ];

        foreach ($estados as $id => $estado) {
            $stmt = $this->db->prepare("UPDATE Estados_Reserva SET status = ? WHERE reservation_status_id = ?");
            $stmt->execute([$estado, $id]);
        }

        return true;
    }
}
?>