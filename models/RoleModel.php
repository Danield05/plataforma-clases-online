<?php
class RoleModel {
    private $db;

    public function __construct() {
        global $pdo;
        $this->db = $pdo;
    }

    public function getRoles() {
        $stmt = $this->db->query("SELECT * FROM Roles");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRoleById($id) {
        $stmt = $this->db->prepare("SELECT * FROM Roles WHERE role_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createRole($data) {
        $stmt = $this->db->prepare("INSERT INTO Roles (role_name, description) VALUES (?, ?)");
        return $stmt->execute([
            $data['role_name'],
            $data['description'] ?? null
        ]);
    }
}
?>