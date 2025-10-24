<?php
class EstudianteModel {
    private $db;

    public function __construct() {
        global $pdo;
        $this->db = $pdo;
    }

    public function getEstudiantes() {
        $stmt = $this->db->query("SELECT e.*, u.first_name, u.last_name, u.email FROM estudiante e JOIN usuarios u ON e.user_id = u.user_id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEstudianteById($userId) {
        $stmt = $this->db->prepare("SELECT e.*, u.first_name, u.last_name, u.email FROM estudiante e JOIN usuarios u ON e.user_id = u.user_id WHERE e.user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createEstudiante($userId, $data) {
        $stmt = $this->db->prepare("INSERT INTO estudiante (user_id, estudiante_id, personal_description) VALUES (?, ?, ?)");
        return $stmt->execute([
            $userId,
            $data['estudiante_id'],
            $data['personal_description'] ?? null
        ]);
    }

    public function updateEstudiante($userId, $data) {
        $stmt = $this->db->prepare("UPDATE estudiante SET personal_description = ? WHERE user_id = ?");
        return $stmt->execute([
            $data['personal_description'] ?? null,
            $userId
        ]);
    }
}
?>