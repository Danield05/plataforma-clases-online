<?php
class EstadoReservaModel {
    private $db;

    public function __construct() {
        global $pdo;
        $this->db = $pdo;
    }

    public function getEstadosReserva() {
        $stmt = $this->db->query("SELECT * FROM Estados_Reserva");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEstadoReservaById($id) {
        $stmt = $this->db->prepare("SELECT * FROM Estados_Reserva WHERE reservation_status_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>