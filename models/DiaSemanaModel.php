<?php
class DiaSemanaModel {
    private $db;

    public function __construct() {
        global $pdo;
        $this->db = $pdo;
    }

    public function getDiasSemana() {
        $stmt = $this->db->query("SELECT * FROM Dias_Semana");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDiaSemanaById($id) {
        $stmt = $this->db->prepare("SELECT * FROM Dias_Semana WHERE week_day_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>