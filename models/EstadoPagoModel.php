<?php
class EstadoPagoModel {
    private $db;

    public function __construct() {
        global $pdo;
        $this->db = $pdo;
    }

    public function getEstadosPago() {
        $stmt = $this->db->query("SELECT * FROM Estados_Pago");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEstadoPagoById($id) {
        $stmt = $this->db->prepare("SELECT * FROM Estados_Pago WHERE payment_status_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>