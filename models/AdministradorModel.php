<?php
class AdministradorModel {
    private $db;

    public function __construct() {
        global $pdo;
        $this->db = $pdo;
    }

    public function getAdministradores() {
        $stmt = $this->db->query("SELECT a.*, u.first_name, u.last_name, u.email FROM administrador a JOIN usuarios u ON a.user_id = u.user_id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAdministradorById($userId) {
        $stmt = $this->db->prepare("SELECT a.*, u.first_name, u.last_name, u.email FROM administrador a JOIN usuarios u ON a.user_id = u.user_id WHERE a.user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createAdministrador($userId, $adminId) {
        $stmt = $this->db->prepare("INSERT INTO administrador (user_id, admin_id) VALUES (?, ?)");
        return $stmt->execute([$userId, $adminId]);
    }

    public function updateLastAccess($userId) {
        $stmt = $this->db->prepare("UPDATE administrador SET last_access_date = NOW() WHERE user_id = ?");
        return $stmt->execute([$userId]);
    }
}
?>