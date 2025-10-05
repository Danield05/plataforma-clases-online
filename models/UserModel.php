<?php
class UserModel {
    private $db;

    public function __construct() {
        // Conectar a la base de datos usando la configuración global
        global $pdo;
        $this->db = $pdo;
    }

    public function getUsers() {
        $stmt = $this->db->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Puedes agregar más métodos como createUser, updateUser, etc.
}
?>