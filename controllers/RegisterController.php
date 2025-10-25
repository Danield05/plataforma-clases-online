<?php

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
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // Validaciones básicas
            if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
                $error = 'Todos los campos son obligatorios';
            } elseif ($password !== $confirmPassword) {
                $error = 'Las contraseñas no coinciden';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Email inválido';
            } elseif (!in_array($role, ['1', '2', '3'])) {
                $error = 'Rol inválido';
            } else {
                // Crear usuario
                require_once 'models/UserModel.php';
                $userModel = new UserModel();

                // Verificar si email ya existe
                $existingUser = $userModel->authenticate($email, 'dummy');
                if ($existingUser !== false) {
                    $error = 'El email ya está registrado';
                } else {
                    $data = [
                        'role_id' => $role,
                        'user_status_id' => 1, // Activo
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'email' => $email,
                        'password' => $password,
                        'personal_description' => $_POST['personal_description'] ?? null,
                        'academic_level' => $_POST['academic_level'] ?? null,
                        'hourly_rate' => $_POST['hourly_rate'] ?? null
                    ];

                    if ($userModel->createUser($data)) {
                        $success = 'Usuario registrado exitosamente';
                    } else {
                        $error = 'Error al registrar usuario';
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