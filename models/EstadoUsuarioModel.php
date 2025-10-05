<?php
class EstadoUsuarioModel {
    private $db;

    public function __construct() {
        global $pdo;
        $this->db = $pdo;
    }

    public function getEstadosUsuario() {
        $stmt = $this->db->query("SELECT * FROM Estados_Usuario");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEstadoUsuarioById($id) {
        $stmt = $this->db->prepare("SELECT * FROM Estados_Usuario WHERE user_status_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>