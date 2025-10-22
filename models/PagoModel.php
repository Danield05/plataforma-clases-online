<?php
class PagoModel {
    private $db;

    public function __construct() {
        global $pdo;
        $this->db = $pdo;
    }

    public function getPagos() {
        $stmt = $this->db->query("SELECT p.*, u.first_name, u.last_name, ep.status as payment_status FROM Pagos p JOIN Usuarios u ON p.user_id = u.user_id JOIN Estados_Pago ep ON p.payment_status_id = ep.payment_status_id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPagoById($id) {
        $stmt = $this->db->prepare("SELECT p.*, u.first_name, u.last_name, ep.status as payment_status FROM Pagos p JOIN Usuarios u ON p.user_id = u.user_id JOIN Estados_Pago ep ON p.payment_status_id = ep.payment_status_id WHERE p.payment_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createPago($data) {
        $stmt = $this->db->prepare("INSERT INTO Pagos (user_id, payment_status_id, amount, payment_method, description) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['user_id'],
            $data['payment_status_id'],
            $data['amount'],
            $data['payment_method'],
            $data['description'] ?? null
        ]);
    }

    public function updatePagoStatus($id, $statusId) {
        $stmt = $this->db->prepare("UPDATE Pagos SET payment_status_id = ? WHERE payment_id = ?");
        return $stmt->execute([$statusId, $id]);
    }
    /* NUEVOS MÉTODOS PARA LOS TOTALES */
    public function getPagosByEstudiante($studentUserId) {
        $stmt = $this->db->prepare("SELECT p.*, u.first_name, u.last_name, ep.status as payment_status FROM Pagos p LEFT JOIN Usuarios u ON p.user_id = u.user_id LEFT JOIN Estados_Pago ep ON p.payment_status_id = ep.payment_status_id WHERE p.user_id = ? ORDER BY p.payment_date DESC");
        $stmt->execute([$studentUserId]);
        $pagos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Para cada pago, intentar encontrar la reserva más cercana por fecha
        foreach ($pagos as &$pago) {
            $stmt2 = $this->db->prepare("SELECT reservation_id FROM Reservas WHERE student_user_id = ? AND class_date <= DATE(?) ORDER BY class_date DESC LIMIT 1");
            $stmt2->execute([$studentUserId, $pago['payment_date']]);
            $reserva = $stmt2->fetch(PDO::FETCH_ASSOC);
            if ($reserva) {
                $pago['reservation_id'] = $reserva['reservation_id'];
            } else {
                // Si no hay reserva anterior, usar el ID del pago como referencia temporal
                $pago['reservation_id'] = 'Pago-' . $pago['payment_id'];
            }
        }

        return $pagos;
    }

    public function getPagosByProfesor($profesorUserId) {
        // Los pagos están asociados al estudiante (user_id del estudiante), pero necesitamos pagos de reservas del profesor
        // Usar GROUP BY para evitar duplicados
        $stmt = $this->db->prepare("
            SELECT p.*, u.first_name, u.last_name, ep.status as payment_status, r.reservation_id
            FROM Pagos p
            JOIN Usuarios u ON p.user_id = u.user_id
            JOIN Estados_Pago ep ON p.payment_status_id = ep.payment_status_id
            JOIN Reservas r ON r.student_user_id = p.user_id AND r.user_id = ?
            WHERE p.payment_status_id IN (2, 3)
            GROUP BY p.payment_id
            ORDER BY p.payment_date DESC
        ");
        $stmt->execute([$profesorUserId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotales() {
        $totales = [
            'totalRecaudado' => 0,
            'totalPendientes' => 0,
            'totalPagados' => 0,
            'totalCancelados' => 0
        ];

        // Total recaudado (solo completados/pagados)
        $stmt = $this->db->query("
            SELECT SUM(amount) as total
            FROM Pagos
            WHERE payment_status_id IN (2, 3)
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
                case 'completado':
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