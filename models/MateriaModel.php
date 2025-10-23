<?php
class MateriaModel {
    private $db;

    public function __construct() {
        global $pdo;
        $this->db = $pdo;
    }

    public function getMaterias() {
        $stmt = $this->db->query("
            SELECT m.*,
                   COUNT(pm.profesor_id) as profesor_count
            FROM materias m
            LEFT JOIN profesor_materia pm ON m.subject_id = pm.subject_id
            GROUP BY m.subject_id
            ORDER BY m.subject_name
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMateriaById($subjectId) {
        $stmt = $this->db->prepare("SELECT * FROM materias WHERE subject_id = ?");
        $stmt->execute([$subjectId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProfesoresByMateria($subjectId) {
        $stmt = $this->db->prepare("
            SELECT p.*, u.first_name, u.last_name, u.email, pm.*
            FROM profesor p
            JOIN usuarios u ON p.user_id = u.user_id
            JOIN profesor_materia pm ON p.profesor_id = pm.profesor_id
            WHERE pm.subject_id = ?
            ORDER BY u.first_name, u.last_name
        ");
        $stmt->execute([$subjectId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchMaterias($searchTerm) {
        $stmt = $this->db->prepare("
            SELECT * FROM materias
            WHERE subject_name LIKE ?
            ORDER BY subject_name
        ");
        $stmt->execute(['%' . $searchTerm . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>