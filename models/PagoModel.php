<?php
class PagoModel {
    private $db;

    public function __construct() {
        global $pdo;
        $this->db = $pdo;
    }

    public function getPagos() {
        $stmt = $this->db->query("
            SELECT p.*, u.first_name, u.last_name, ep.status as payment_status, r.class_date, r.class_time, r.user_id as profesor_user_id
            FROM pagos p
            JOIN usuarios u ON p.user_id = u.user_id
            JOIN estados_pago ep ON p.payment_status_id = ep.payment_status_id
            LEFT JOIN reservas r ON r.student_user_id = p.user_id
                AND CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(p.description, 'Reserva: ', -1), ' ', 1) AS UNSIGNED) = r.reservation_id
            ORDER BY p.payment_date DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPagoById($id) {
        $stmt = $this->db->prepare("
            SELECT 
                p.*, 
                u.first_name, 
                u.last_name, 
                u.email,
                ep.status as payment_status,
                r.reservation_id,
                r.class_date,
                r.class_time,
                u_profesor.first_name as profesor_name,
                u_profesor.last_name as profesor_last_name,
                prof.hourly_rate,
                prof.academic_level,
                d.start_time,
                d.end_time,
                mat.subject_name
            FROM pagos p 
            JOIN usuarios u ON p.user_id = u.user_id 
            JOIN estados_pago ep ON p.payment_status_id = ep.payment_status_id
            LEFT JOIN reservas r ON p.description LIKE CONCAT('%', r.reservation_id, '%') 
                AND r.student_user_id = p.user_id
            LEFT JOIN usuarios u_profesor ON r.user_id = u_profesor.user_id
            LEFT JOIN profesor prof ON r.user_id = prof.user_id
            LEFT JOIN disponibilidad_profesores d ON r.availability_id = d.availability_id
            LEFT JOIN materias mat ON d.subject_id = mat.subject_id
            WHERE p.payment_id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createPago($data) {
        // No incluimos payment_id porque es AUTO_INCREMENT
        // Guardamos TODOS los campos disponibles (payment_date y created_at se generan automáticamente)
        $stmt = $this->db->prepare("
            INSERT INTO pagos (
                user_id, 
                amount, 
                payment_status_id, 
                transaction_id, 
                payment_method, 
                description
            ) VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        $result = $stmt->execute([
            $data['user_id'],
            $data['amount'],
            $data['payment_status_id'] ?? 1, // Default: Pendiente
            $data['transaction_id'] ?? null,
            $data['payment_method'] ?? 'PayPal',
            $data['description'] ?? null
        ]);
        
        // Retornar el ID del pago creado
        if ($result) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }

    public function updatePagoStatus($id, $statusId) {
        $stmt = $this->db->prepare("UPDATE pagos SET payment_status_id = ? WHERE payment_id = ?");
        return $stmt->execute([$statusId, $id]);
    }
    /* NUEVOS MÉTODOS PARA LOS TOTALES */
    public function getPagosByEstudiante($studentUserId) {
        $stmt = $this->db->prepare("
            SELECT p.*, u.first_name, u.last_name, ep.status as payment_status, r.class_date, r.class_time, r.reservation_id
            FROM pagos p
            LEFT JOIN usuarios u ON p.user_id = u.user_id
            LEFT JOIN estados_pago ep ON p.payment_status_id = ep.payment_status_id
            LEFT JOIN reservas r ON r.student_user_id = p.user_id
                AND CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(p.description, 'Reserva: ', -1), ' ', 1) AS UNSIGNED) = r.reservation_id
            WHERE p.user_id = ?
            ORDER BY p.payment_date DESC, p.payment_id DESC
        ");
        $stmt->execute([$studentUserId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPagosByProfesor($profesorUserId) {
        // Los pagos están asociados al estudiante (user_id del estudiante), pero necesitamos pagos de reservas del profesor
        // Usar GROUP BY para evitar duplicados
        $stmt = $this->db->prepare("
            SELECT p.*, u.first_name, u.last_name, ep.status as payment_status, r.reservation_id, r.class_date, r.class_time
            FROM pagos p
            JOIN usuarios u ON p.user_id = u.user_id
            JOIN estados_pago ep ON p.payment_status_id = ep.payment_status_id
            JOIN reservas r ON r.student_user_id = p.user_id AND r.user_id = ?
                AND CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(p.description, 'Reserva: ', -1), ' ', 1) AS UNSIGNED) = r.reservation_id
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
            FROM pagos
            WHERE payment_status_id IN (2, 3)
        ");
        $totales['totalRecaudado'] = $stmt->fetchColumn() ?? 0;

        // Contar cada estado
        $stmt = $this->db->query("
            SELECT ep.status, COUNT(*) as cantidad
            FROM pagos p
            JOIN estados_pago ep ON p.payment_status_id = ep.payment_status_id
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