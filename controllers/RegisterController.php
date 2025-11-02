<?php

// Configurar logging
ini_set('log_errors', 1);
ini_set('error_log', 'C:\xampp\php\logs\php_error_log');

class RegisterController {
    public function index() {
        // Mostrar formulario de registro
        require_once 'views/layouts/register.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $role = $_POST['role'] ?? '';
            $firstName = $_POST['first_name'] ?? '';
            $lastName = $_POST['last_name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Validaciones básicas
            if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
                $error = 'Todos los campos son obligatorios';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Email inválido';
            } elseif (!in_array($role, ['1', '2', '3'])) {
                $error = 'Por favor selecciona un tipo de usuario válido';
            } else {
                // Crear usuario
                require_once 'models/UserModel.php';
                $userModel = new UserModel();

                // Verificar si email ya existe
                if ($userModel->emailExists($email)) {
                    $error = 'El email ya está registrado';
                } else {
                    $data = [
                        'role_id' => $role,
                        'user_status_id' => 1, // Activo
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'email' => $email,
                        'phone' => $_POST['phone'] ?? null,
                        'password' => $password,
                        'personal_description' => $_POST['personal_description'] ?? null,
                        'academic_level' => $_POST['academic_level'] ?? null,
                        'hourly_rate' => $_POST['hourly_rate'] ?? null,
                        'meeting_link' => $_POST['meeting_link'] ?? null
                    ];

                    if ($userModel->createUser($data)) {
                        // Registro exitoso - redirigir al login con mensaje
                        session_start();
                        $_SESSION['register_success'] = '¡Registro exitoso! Tu cuenta ha sido creada correctamente. Ya puedes iniciar sesión.';
                        header('Location: /plataforma-clases-online/auth/login');
                        exit;
                    } else {
                        $error = 'Error al registrar usuario. Por favor intenta de nuevo.';
                    }
                }
            }

            // Volver a mostrar el formulario con mensaje
            require_once 'views/layouts/register.php';
        } else {
            header('Location: /plataforma-clases-online/register');
            exit;
        }
    }
}
?>