<?php
class UserModel {
    private $db;

    public function __construct() {
        // Conectar a la base de datos usando la configuración global
        global $pdo;
        $this->db = $pdo;
    }

    public function getUsers() {
        $stmt = $this->db->query("SELECT * FROM usuarios");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE user_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($data) {
        // Insertar en usuarios
        $stmt = $this->db->prepare("INSERT INTO usuarios (role_id, user_status_id, first_name, last_name, email, password, photo_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $result = $stmt->execute([
            $data['role_id'],
            $data['user_status_id'],
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['photo_url'] ?? null
        ]);

        if ($result) {
            $userId = $this->db->lastInsertId();

            // Insertar en tabla específica del rol
            switch ($data['role_id']) {
                case 1: // Administrador
                    require_once 'models/AdministradorModel.php';
                    $adminModel = new AdministradorModel();
                    // Generar admin_id único (usar el mismo user_id por simplicidad, pero verificar duplicados)
                    $adminId = $userId;
                    $adminModel->createAdministrador($userId, $adminId);
                    break;
                case 2: // Profesor
                    require_once 'models/ProfesorModel.php';
                    $profesorModel = new ProfesorModel();
                    // Generar professor_id único (diferente al user_id)
                    $professorId = $userId + 1000; // Offset para evitar conflictos
                    $profesorModel->createProfesor($userId, [
                        'professor_id' => $professorId,
                        'personal_description' => $data['personal_description'] ?? null,
                        'academic_level' => $data['academic_level'] ?? null,
                        'hourly_rate' => $data['hourly_rate'] ?? null
                    ]);
                    break;
                case 3: // Estudiante
                    require_once 'models/EstudianteModel.php';
                    $estudianteModel = new EstudianteModel();
                    // Generar student_id único (diferente al user_id)
                    $studentId = $userId + 2000; // Offset para evitar conflictos
                    $estudianteModel->createEstudiante($userId, [
                        'student_id' => $studentId,
                        'personal_description' => $data['personal_description'] ?? null
                    ]);
                    break;
            }

            return true;
        }

        return false;
    }

    public function updateUser($id, $data) {
        $stmt = $this->db->prepare("UPDATE usuarios SET first_name = ?, last_name = ?, email = ?, photo_url = ? WHERE user_id = ?");
        return $stmt->execute([
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            $data['photo_url'] ?? null,
            $id
        ]);
    }

    public function deleteUser($id) {
        $stmt = $this->db->prepare("DELETE FROM usuarios WHERE user_id = ?");
        return $stmt->execute([$id]);
    }

    public function authenticate($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
?>