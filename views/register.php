<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Plataforma de Clases Online</title>
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css">
</head>
<body>
    <div class="register-container">
        <h2>Registro de Usuario</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        <form action="/plataforma-clases-online/register/register" method="POST">
            <div class="form-group">
                <label for="role">Tipo de Usuario:</label>
                <select id="role" name="role" required onchange="toggleFields()">
                    <option value="">Seleccionar...</option>
                    <option value="1">Administrador</option>
                    <option value="2">Profesor</option>
                    <option value="3">Estudiante</option>
                </select>
            </div>

            <div class="form-group">
                <label for="first_name">Nombre:</label>
                <input type="text" id="first_name" name="first_name" required>
            </div>

            <div class="form-group">
                <label for="last_name">Apellido:</label>
                <input type="text" id="last_name" name="last_name" required>
            </div>

            <div class="form-group">
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirmar Contraseña:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <!-- Campos específicos para profesor -->
            <div id="profesor-fields" style="display: none;">
                <div class="form-group">
                    <label for="academic_level">Nivel Académico:</label>
                    <input type="text" id="academic_level" name="academic_level">
                </div>
                <div class="form-group">
                    <label for="hourly_rate">Tarifa por Hora:</label>
                    <input type="number" id="hourly_rate" name="hourly_rate" step="0.01">
                </div>
            </div>

            <!-- Campo común para profesor y estudiante -->
            <div id="description-field" style="display: none;">
                <div class="form-group">
                    <label for="personal_description">Descripción Personal:</label>
                    <textarea id="personal_description" name="personal_description"></textarea>
                </div>
            </div>

            <button type="submit">Registrar</button>
        </form>

        <p><a href="/plataforma-clases-online/auth/login">¿Ya tienes cuenta? Inicia sesión</a></p>
    </div>

    <script>
        function toggleFields() {
            const role = document.getElementById('role').value;
            const profesorFields = document.getElementById('profesor-fields');
            const descriptionField = document.getElementById('description-field');

            if (role === '2') { // Profesor
                profesorFields.style.display = 'block';
                descriptionField.style.display = 'block';
            } else if (role === '3') { // Estudiante
                profesorFields.style.display = 'none';
                descriptionField.style.display = 'block';
            } else {
                profesorFields.style.display = 'none';
                descriptionField.style.display = 'none';
            }
        }
    </script>
</body>
</html>