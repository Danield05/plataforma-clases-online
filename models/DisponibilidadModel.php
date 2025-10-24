<?php
class DisponibilidadModel {
    private $db;

    public function __construct() {
        global $pdo;
        $this->db = $pdo;
    }

    public function getDisponibilidades() {
        $stmt = $this->db->query("SELECT d.*, u.first_name, u.last_name, ds.day, ed.status as availability_status FROM disponibilidad_profesores d JOIN usuarios u ON d.user_id = u.user_id JOIN dias_semana ds ON d.week_day_id = ds.week_day_id JOIN estados_disponibilidad ed ON d.availability_status_id = ed.availability_status_id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDisponibilidadById($id) {
        $stmt = $this->db->prepare("SELECT d.*, u.first_name, u.last_name, ds.day, ed.status as availability_status FROM disponibilidad_profesores d JOIN usuarios u ON d.user_id = u.user_id JOIN dias_semana ds ON d.week_day_id = ds.week_day_id JOIN estados_disponibilidad ed ON d.availability_status_id = ed.availability_status_id WHERE d.availability_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getDisponibilidadesByProfesor($userId) {
        $stmt = $this->db->prepare("SELECT d.*, u.first_name, u.last_name, ds.day, ed.status as availability_status FROM disponibilidad_profesores d JOIN usuarios u ON d.user_id = u.user_id JOIN dias_semana ds ON d.week_day_id = ds.week_day_id JOIN estados_disponibilidad ed ON d.availability_status_id = ed.availability_status_id WHERE d.user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createDisponibilidad($data) {
        $stmt = $this->db->prepare("INSERT INTO disponibilidad_profesores (user_id, week_day_id, availability_status_id, start_time, end_time) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['user_id'],
            $data['week_day_id'],
            $data['availability_status_id'] ?? 1, // Por defecto Disponible
            $data['start_time'],
            $data['end_time']
        ]);
    }

    public function updateDisponibilidad($id, $data) {
        $stmt = $this->db->prepare("UPDATE disponibilidad_profesores SET week_day_id = ?, availability_status_id = ?, start_time = ?, end_time = ? WHERE availability_id = ?");
        return $stmt->execute([
            $data['week_day_id'],
            $data['availability_status_id'] ?? 1, // Por defecto Disponible
            $data['start_time'],
            $data['end_time'],
            $id
        ]);
    }

    // Borrar disponibilidad
    public function deleteDisponibilidad($id) {
        $stmt = $this->db->prepare("DELETE FROM disponibilidad_profesores WHERE availability_id = ?");
        return $stmt->execute([$id]);
    }
}
?>