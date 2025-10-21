<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📝 Registro - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
</head>

<body>
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">📝 Registro de Usuario</h1>
        </div>
    </header>

    <div class="register-container">
        <div class="col-lg-10 col-xl-9 mx-auto">
            <div class="card flex-row my-5 border-0 shadow rounded-3 overflow-hidden">
                <div class="card-img-left d-none d-md-flex"></div>
                    
                    <div class="card-body p-sm-4">
                        <h4 class="card-title text-center mb-4 fw-bold fs-5">Ingresa tus datos</h4>


                        <?php if (isset($error)): ?>
                            <p class="error"><?php echo $error; ?></p>
                        <?php endif; ?>
                        <?php if (isset($success)): ?>
                            <p class="success"><?php echo $success; ?></p>
                        <?php endif; ?>

                        <form action="/plataforma-clases-online/register/register" method="POST">
                            <div class="form-select mb-3">
                                <label for="role">Tipo de Usuario:</label>
                                <select id="role" name="role" class="form-control" placeholder="" required
                                    onchange="toggleFields()">
                                    <option value="">Seleccionar...</option>
                                    <option value="1">Administrador</option>
                                    <option value="2">Profesor</option>
                                    <option value="3">Estudiante</option>
                                </select>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="first_name" name="first_name"
                                    placeholder="Ingresa tu Nombre" required autofocus>
                                <label for="first_name">Nombre:</label>

                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="last_name" name="last_name"
                                    placeholder="Ingresa tu apellido" required>
                                <label for="last_name">Apellido:</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Ingresa tu email" required>
                                <label for="email">Correo Electrónico:</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Ingresa tu Contraseña" required>
                                <label for="password">Contraseña:</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="confirm_password"
                                    name="confirm_password" placeholder="Confirma tu Contraseña" required>
                                <label for="confirm_password">Confirmar Contraseña:</label>
                            </div>

                            <!-- Campos específicos para profesor -->
                            <div id="profesor-fields" style="display: none;">
                                <div class="form-floating mb-3">
                                    <input type="text" id="academic_level" class="form-control" name="academic_level"
                                        placeholder="Ingresa tu Nivel Académico">
                                    <label for="academic_level">Nivel Académico:</label>

                                </div>
                                <div class="form-floating mb-3">
                                    <input type="number" id="hourly_rate" class="form-control" name="hourly_rate"
                                        placeholder="Ingresa tu Tarifa por Hora">
                                    <label for="hourly_rate">Tarifa por Hora:</label>

                                </div>
                            </div>

                            <!-- Campo común para profesor y estudiante -->
                            <div id="description-field" style="display: none;">
                                <div class="form-floating mb-3">
                                    <textarea id="personal_description" class="form-control" name="personal_description"
                                        placeholder="Ingresa una breve descripción sobre ti"></textarea>
                                    <label for="personal_description">Descripción Personal:</label>
                                </div>
                            </div>

                            <div class="d-grid mb-2">
                                <button class="btn btn-register btn-primary fw-bold text-uppercase"
                                    type="submit">Registrar</button>
                            </div>
                        </form>

                        <div class="text-center">
                            <p><a href="/plataforma-clases-online/auth/login">¿Ya tienes cuenta? Inicia sesión</a></p>
                        </div>


                    </div>
            </div>
        </div>

    <footer class="modern-footer">
        <div class="footer-content">
            <div class="footer-info">
                <div class="footer-brand">
                    <span>💎</span>
                    <span>Plataforma Clases Online</span>
                </div>
                <div class="footer-links">
                    <a href="#privacidad">Privacidad</a>
                    <a href="#terminos">Términos</a>
                    <a href="#soporte">Soporte</a>
                    <a href="#contacto">Contacto</a>
                </div>
            </div>
            <div class="footer-copy">
                © <?php echo date('Y'); ?> Plataforma Clases Online. Todos los derechos reservados.
            </div>
        </div>
    </footer>

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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>