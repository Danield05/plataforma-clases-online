<?php

// models/AvailabilityModel.php
class AvailabilityModel {
    private $db;
    public function __construct(PDO $db) { $this->db = $db; }

    public function getOrCreate(int $userId, int $date, string $start, string $end): int {
        // 1) Buscar slot igual
        $sql = "SELECT availability_id FROM disponibilidad_profesores
                WHERE user_id=? AND week_day_id=? AND start_time=? AND end_time=? LIMIT 1";
        $st  = $this->db->prepare($sql);
        $st->execute([$userId, $date, $start, $end]);
        $id = (int)($st->fetchColumn() ?: 0);
        if ($id > 0) return $id;

        // 2) Crear slot
        $ins = $this->db->prepare(
            "INSERT INTO availability (user_id, available_date, start_time, end_time, created_at)
             VALUES (?, ?, ?, ?, NOW())"
        );
        $ins->execute([$userId, $date, $start, $end]);
        return (int)$this->db->lastInsertId();
    }

    // (Opcional) validar que no se cruce con otras reservas
    public function hasConflict(int $userId, string $date, string $start, string $end): bool {
        $sql = "SELECT 1
                  FROM reservas
                 WHERE user_id = ?
                   AND class_date = ?
                   AND (
                        (class_time BETWEEN ? AND ?) /* si guardas solo hora inicio */
                       OR (? BETWEEN class_time AND ADDTIME(class_time, TIMEDIFF(?, ?)))
                   )
                 LIMIT 1";
        $st = $this->db->prepare($sql);
        $st->execute([$userId, $date, $start, $end, $start, $end, $start]);
        return (bool)$st->fetchColumn();
    }
}


?>