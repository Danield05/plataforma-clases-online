<?php
class ReviewModel {
    private $db;

    public function __construct() {
        global $pdo;
        $this->db = $pdo;
    }

    public function getReviews() {
        $stmt = $this->db->query("SELECT r.*, u.first_name as profesor_name, s.first_name as estudiante_name FROM Reviews r JOIN Usuarios u ON r.user_id = u.user_id JOIN Usuarios s ON r.student_user_id = s.user_id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReviewById($id) {
        $stmt = $this->db->prepare("SELECT r.*, u.first_name as profesor_name, s.first_name as estudiante_name FROM Reviews r JOIN Usuarios u ON r.user_id = u.user_id JOIN Usuarios s ON r.student_user_id = s.user_id WHERE r.review_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getReviewsByProfesor($userId) {
        $stmt = $this->db->prepare("SELECT r.*, s.first_name as estudiante_name FROM Reviews r JOIN Usuarios s ON r.student_user_id = s.user_id WHERE r.user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createReview($data) {
        $stmt = $this->db->prepare("INSERT INTO Reviews (reservation_id, user_id, student_user_id, rating, comment) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['reservation_id'],
            $data['user_id'],
            $data['student_user_id'],
            $data['rating'],
            $data['comment'] ?? null
        ]);
    }
}
?>