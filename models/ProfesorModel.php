<?php
class ProfesorModel {
    private $db;

    public function __construct() {
        global $pdo;
        $this->db = $pdo;
    }

    public function getProfesores() {
        $stmt = $this->db->query("SELECT p.*, u.first_name, u.last_name, u.email FROM profesor p JOIN usuarios u ON p.user_id = u.user_id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProfesorById($userId) {
        $stmt = $this->db->prepare("SELECT p.*, u.first_name, u.last_name, u.email FROM profesor p JOIN usuarios u ON p.user_id = u.user_id WHERE p.user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createProfesor($userId, $data) {
        $stmt = $this->db->prepare("INSERT INTO profesor (user_id, professor_id, personal_description, academic_level, hourly_rate) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $userId,
            $data['professor_id'],
            $data['personal_description'] ?? null,
            $data['academic_level'] ?? null,
            $data['hourly_rate'] ?? null
        ]);
    }

    public function updateProfesor($userId, $data) {
        $stmt = $this->db->prepare("UPDATE profesor SET personal_description = ?, academic_level = ?, hourly_rate = ? WHERE user_id = ?");
        return $stmt->execute([
            $data['personal_description'] ?? null,
            $data['academic_level'] ?? null,
            $data['hourly_rate'] ?? null,
            $userId
        ]);
    }

    public function getMateriasByProfesor($userId) {
        $stmt = $this->db->prepare("SELECT m.* FROM materias m JOIN profesor_materias pm ON m.materia_id = pm.materia_id WHERE pm.user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>