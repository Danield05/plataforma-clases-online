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
    /* NUEVOS MÉTODOS PARA LOS TOTALES */
    public function getTotales() {
        $totales = [
            'totalRecaudado' => 0,
            'totalPendientes' => 0,
            'totalPagados' => 0,
            'totalCancelados' => 0
        ];

        // Total recaudado (solo pagados)
        $stmt = $this->db->query("
            SELECT SUM(amount) as total
            FROM Pagos
            WHERE payment_status_id = 3
        ");
        $totales['totalRecaudado'] = $stmt->fetchColumn() ?? 0;

        // Contar cada estado
        $stmt = $this->db->query("
            SELECT ep.status, COUNT(*) as cantidad
            FROM Pagos p
            JOIN Estados_Pago ep ON p.payment_status_id = ep.payment_status_id
            GROUP BY ep.status
        ");
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $estado = strtolower($row['status']);
            switch ($estado) {
                case 'pendiente':
                    $totales['totalPendientes'] = $row['cantidad'];
                    break;
                case 'pagado':
                    $totales['totalPagados'] = $row['cantidad'];
                    break;
                case 'cancelado':
                    $totales['totalCancelados'] = $row['cantidad'];
                    break;
            }
        }

        return $totales;
    }
}
?>