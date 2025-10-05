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

    public function updateReservaStatus($id, $statusId) {
        $stmt = $this->db->prepare("UPDATE Reservas SET reservation_status_id = ? WHERE reservation_id = ?");
        return $stmt->execute([$statusId, $id]);
    }
}
?>