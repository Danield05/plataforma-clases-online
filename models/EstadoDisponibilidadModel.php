<?php
class EstadoDisponibilidadModel {
    private $db;

    public function __construct() {
        global $pdo;
        $this->db = $pdo;
    }

    public function getEstadosDisponibilidad() {
        $stmt = $this->db->query("SELECT * FROM Estados_Disponibilidad ORDER BY availability_status_id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEstadoDisponibilidadById($id) {
        $stmt = $this->db->prepare("SELECT * FROM Estados_Disponibilidad WHERE availability_status_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getEstadoDisponibilidadByStatus($status) {
        $stmt = $this->db->prepare("SELECT * FROM Estados_Disponibilidad WHERE status = ?");
        $stmt->execute([$status]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>