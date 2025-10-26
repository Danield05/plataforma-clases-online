<?php
class ReviewModel {
    private $db;

    public function __construct() {
        global $pdo;
        $this->db = $pdo;
    }

    public function getReviews() {
        $query = "SELECT r.*, 
                        res.class_date, res.class_time,
                        est.first_name as estudiante_name, est.last_name as estudiante_last_name,
                        prof.first_name as profesor_name, prof.last_name as profesor_last_name
                 FROM Reviews r
                 JOIN Reservas res ON r.reservation_id = res.reservation_id
                 JOIN Usuarios est ON r.reviewer_user_id = est.user_id
                 JOIN Usuarios prof ON r.reviewed_user_id = prof.user_id
                 ORDER BY r.created_at DESC";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReviewById($id) {
        $query = "SELECT r.*, 
                        res.class_date, res.class_time,
                        est.first_name as estudiante_name, est.last_name as estudiante_last_name,
                        prof.first_name as profesor_name, prof.last_name as profesor_last_name
                 FROM Reviews r
                 JOIN Reservas res ON r.reservation_id = res.reservation_id
                 JOIN Usuarios est ON r.reviewer_user_id = est.user_id
                 JOIN Usuarios prof ON r.reviewed_user_id = prof.user_id
                 WHERE r.review_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getReviewsByProfesor($profesorId, $fechaInicio = null, $fechaFin = null) {
        $query = "SELECT r.*, 
                        res.class_date, res.class_time,
                        est.first_name as estudiante_name, est.last_name as estudiante_last_name
                 FROM Reviews r
                 JOIN Reservas res ON r.reservation_id = res.reservation_id
                 JOIN Usuarios est ON r.reviewer_user_id = est.user_id
                 WHERE r.reviewed_user_id = ?";
        
        $params = [$profesorId];

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

    public function getReviewsByEstudiante($estudianteId) {
        $query = "SELECT r.*, 
                        res.class_date, res.class_time,
                        prof.first_name as profesor_name, prof.last_name as profesor_last_name
                 FROM Reviews r
                 JOIN Reservas res ON r.reservation_id = res.reservation_id
                 JOIN Usuarios prof ON r.reviewed_user_id = prof.user_id
                 WHERE r.reviewer_user_id = ?
                 ORDER BY r.created_at DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([$estudianteId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createReview($data) {
        try {
            $stmt = $this->db->prepare("INSERT INTO Reviews (reservation_id, reviewer_user_id, reviewed_user_id, rating, comment) 
                                      VALUES (?, ?, ?, ?, ?)");
            return $stmt->execute([
                $data['reservation_id'],
                $data['reviewer_user_id'],
                $data['reviewed_user_id'],
                $data['rating'],
                $data['comment'] ?? null
            ]);
        } catch (PDOException $e) {
            error_log("Error creating review: " . $e->getMessage());
            return false;
        }
    }

    public function checkReviewExists($reservationId) {
        $query = "SELECT COUNT(*) as count FROM Reviews WHERE reservation_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$reservationId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }
}
?>