<?php
class PagoModel {
    private $db;

    public function __construct() {
        global $pdo;
        $this->db = $pdo;
    }

    public function getPagos() {
        $stmt = $this->db->query("SELECT p.*, r.reservation_id, ep.status as payment_status FROM Pagos p JOIN Reservas r ON p.reservation_id = r.reservation_id JOIN Estados_Pago ep ON p.payment_status_id = ep.payment_status_id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPagoById($id) {
        $stmt = $this->db->prepare("SELECT p.*, r.reservation_id, ep.status as payment_status FROM Pagos p JOIN Reservas r ON p.reservation_id = r.reservation_id JOIN Estados_Pago ep ON p.payment_status_id = ep.payment_status_id WHERE p.payment_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createPago($data) {
        $stmt = $this->db->prepare("INSERT INTO Pagos (reservation_id, payment_status_id, amount, payment_method) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $data['reservation_id'],
            $data['payment_status_id'],
            $data['amount'],
            $data['payment_method']
        ]);
    }

    public function updatePagoStatus($id, $statusId) {
        $stmt = $this->db->prepare("UPDATE Pagos SET payment_status_id = ? WHERE payment_id = ?");
        return $stmt->execute([$statusId, $id]);
    }
}
?>