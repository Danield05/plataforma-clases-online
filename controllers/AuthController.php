<?php

class AuthController {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            // Debug: Mostrar exactamente qué datos estamos recibiendo
            error_log("LOGIN DEBUG - Email recibido: '$email' (longitud: " . strlen($email) . ")");
            error_log("LOGIN DEBUG - Password presente: " . (!empty($password) ? 'SÍ' : 'NO'));

            require_once 'models/UserModel.php';
            require_once 'models/RoleModel.php';

            $userModel = new UserModel();
            $roleModel = new RoleModel();

            $user = $userModel->authenticate($email, $password);
            if ($user) {
                error_log("LOGIN DEBUG - Usuario autenticado exitosamente: " . $user['first_name'] . ' ' . $user['last_name']);

                $role = $roleModel->getRoleById($user['role_id']);
                if ($role) {
                    error_log("LOGIN DEBUG - Rol encontrado: " . $role['role_name']);

                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['role'] = $role['role_name'];
                    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                    $_SESSION['first_name'] = $user['first_name'];
                    $_SESSION['last_name'] = $user['last_name'];
                    $_SESSION['user_email'] = $user['email'];

                    // Redirigir basado en rol
                    $this->redirectBasedOnRole($role['role_name']);
                } else {
                    error_log("LOGIN DEBUG - ERROR: Rol no encontrado para role_id = " . $user['role_id']);
                    $error = 'Error interno del sistema. Contacte al administrador.';
                    require_once __DIR__ . '/../views/layouts/login.php';
                }
            } else {
                error_log("LOGIN DEBUG - ERROR: Autenticación fallida para email: '$email'");
                $error = 'Credenciales incorrectas. Email usado: "' . $email . '" (longitud: ' . strlen($email) . ')';
                require_once __DIR__ . '/../views/layouts/login.php';
            }
        } else {
            require_once __DIR__ . '/../views/layouts/login.php';
        }
    }

    public function logout() {
        session_destroy();
        header('Location: /plataforma-clases-online/auth/login');
        exit;
    }

    private function redirectBasedOnRole($role) {
        switch ($role) {
            case 'administrador':
                header('Location: /plataforma-clases-online/');
                break;
            case 'profesor':
                header('Location: /plataforma-clases-online/home/profesor_dashboard');
                break;
            case 'estudiante':
                header('Location: /plataforma-clases-online/home/estudiante_dashboard');
                break;
            default:
                header('Location: /plataforma-clases-online/');
                break;
        }
        exit;
    }

    public static function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /plataforma-clases-online/auth/login');
            exit;
        }
    }

    public static function checkRole($allowedRoles) {
        if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowedRoles)) {
            header('Location: /plataforma-clases-online/');
            exit;
        }
    }
}