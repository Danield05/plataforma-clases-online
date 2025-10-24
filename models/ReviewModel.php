<?php
class ReviewModel {
    private $db;

    public function __construct() {
        global $pdo;
        $this->db = $pdo;
    }

    public function getReviews() {
        $stmt = $this->db->query("SELECT r.*, prof.first_name as profesor_name, prof.last_name as profesor_last_name, est.first_name as estudiante_name, est.last_name as estudiante_last_name FROM Reviews r JOIN Usuarios prof ON r.reviewer_user_id = prof.user_id JOIN Usuarios est ON r.reviewed_user_id = est.user_id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReviewById($id) {
        $stmt = $this->db->prepare("SELECT r.*, prof.first_name as profesor_name, prof.last_name as profesor_last_name, est.first_name as estudiante_name, est.last_name as estudiante_last_name FROM Reviews r JOIN Usuarios prof ON r.reviewer_user_id = prof.user_id JOIN Usuarios est ON r.reviewed_user_id = est.user_id WHERE r.review_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getReviewsByProfesor($userId, $fechaInicio = null, $fechaFin = null) {
        // Las reviews están hechas por estudiantes (reviewer_user_id = estudiante), pero queremos reviews del profesor (reviewed_user_id = profesor)
        $query = "SELECT r.*, est.first_name as estudiante_name, est.last_name as estudiante_last_name FROM Reviews r JOIN Usuarios est ON r.reviewer_user_id = est.user_id WHERE r.reviewed_user_id = ?";

        $params = [$userId];

        if ($fechaInicio) {
            $query .= " AND DATE(r.created_at) >= ?";
            $params[] = $fechaInicio;
        }

        if ($fechaFin) {
            $query .= " AND DATE(r.created_at) <= ?";
            $params[] = $fechaFin;
        }

        $query .= " ORDER BY r.created_at DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createReview($data) {
        $stmt = $this->db->prepare("INSERT INTO Reviews (reservation_id, reviewer_user_id, reviewed_user_id, rating, comment) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['reservation_id'],
            $data['reviewer_user_id'], // profesor
            $data['reviewed_user_id'], // estudiante
            $data['rating'],
            $data['comment'] ?? null
        ]);
    }
}
?>