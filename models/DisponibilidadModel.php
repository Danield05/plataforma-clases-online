<?php
class DisponibilidadModel {
    private $db;

    public function __construct() {
        global $pdo;
        $this->db = $pdo;
    }

    public function getDisponibilidades() {
        $stmt = $this->db->query("SELECT d.*, u.first_name, u.last_name, ds.day, er.status FROM Disponibilidad_Profesores d JOIN Usuarios u ON d.user_id = u.user_id JOIN Dias_Semana ds ON d.week_day_id = ds.week_day_id JOIN Estados_Reserva er ON d.reservation_status_id = er.reservation_status_id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDisponibilidadById($id) {
        $stmt = $this->db->prepare("SELECT d.*, u.first_name, u.last_name, ds.day, er.status FROM Disponibilidad_Profesores d JOIN Usuarios u ON d.user_id = u.user_id JOIN Dias_Semana ds ON d.week_day_id = ds.week_day_id JOIN Estados_Reserva er ON d.reservation_status_id = er.reservation_status_id WHERE d.availability_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getDisponibilidadesByProfesor($userId) {
        $stmt = $this->db->prepare("SELECT d.*, ds.day, er.status FROM Disponibilidad_Profesores d JOIN Dias_Semana ds ON d.week_day_id = ds.week_day_id JOIN Estados_Reserva er ON d.reservation_status_id = er.reservation_status_id WHERE d.user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createDisponibilidad($data) {
        $stmt = $this->db->prepare("INSERT INTO Disponibilidad_Profesores (user_id, week_day_id, reservation_status_id, start_time, end_time) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['user_id'],
            $data['week_day_id'],
            $data['reservation_status_id'],
            $data['start_time'],
            $data['end_time']
        ]);
    }

    public function updateDisponibilidad($id, $data) {
        $stmt = $this->db->prepare("UPDATE Disponibilidad_Profesores SET week_day_id = ?, reservation_status_id = ?, start_time = ?, end_time = ? WHERE availability_id = ?");
        return $stmt->execute([
            $data['week_day_id'],
            $data['reservation_status_id'],
            $data['start_time'],
            $data['end_time'],
            $id
        ]);
    }
}
?>