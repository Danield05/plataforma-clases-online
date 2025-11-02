<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔐 Registro - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/register.css?v=<?php echo time(); ?>">
</head>

<body class="register-page">
    <!-- Estrellas espaciales -->
    <div class="stars">
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show notification-alert" role="alert" id="errorAlert">
            <strong>¡Error!</strong> <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="register-container">
        <!-- Panel izquierdo con el formulario -->
        <div class="register-panel">
            <!-- Formulario de registro moderno -->
            <form action="/plataforma-clases-online/register" method="POST" id="registerForm"
                class="modern-register-form">
                <div class="form-header">
                    <div class="logo-container">
                        <span class="logo-icon">📝</span>
                    </div>
                    <h2>Regístrate</h2>
                    <p>¿Ya tienes una cuenta? <a href="/plataforma-clases-online/auth/login"
                            class="register-link">Inicia sesión aquí</a></p>
                </div>
                <div class="form-select mb-3">
                    <label for="id_role">Tipo de Usuario:</label>
                    <select id="id_role" name="role" class="form-control" required onchange="toggleFields()">
                        <option value="">Seleccionar...</option>
                        <option value="2">Profesor</option>
                        <option value="3">Estudiante</option>
                    </select>
                </div>

                <div class="form-group-modern">
                    <label for="first_name">Nombre</label>
                    <input type="text" id="first_name" name="first_name" required placeholder="Ingresa tu nombre" suggested="Nombre">
                </div>

                <div class="form-group-modern">
                    <label for="last_name">Apellido</label>
                    <input type="text" id="last_name" name="last_name" required placeholder="Ingresa tu apellido" suggested="Apellido">
                </div>

                <div class="form-group-modern">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" required placeholder="Ingresa tu correo electrónico" suggested="email">
                </div>

                <div class="form-group-modern">
                    <label for="phone">Teléfono</label>
                    <input type="tel" id="phone" name="phone" placeholder="Ingresa tu número de teléfono" suggested="phone">
                </div>

                <div class="form-group-modern">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required placeholder="Ingresa tu contraseña" autocomplete="current-password">
                </div>

                <!-- Campos específicos para profesor -->
                <div id="profesor-fields" style="display: none;">
                    <div class="form-group-modern">
                        <label for="academic_level">Nivel Académico:</label>
                        <input type="text" id="academic_level" name="academic_level"
                            placeholder="Ej: Licenciatura, Maestría, Doctorado">
                    </div>
                    <div class="form-group-modern">
                        <label for="hourly_rate">Tarifa por Hora ($):</label>
                        <input type="number" id="hourly_rate" name="hourly_rate" step="0.01" min="0"
                            placeholder="Ej: 25.00">
                    </div>
                    <div class="form-group-modern">
                        <label for="meeting_link">Enlace de Reunión (Opcional):</label>
                        <input type="url" id="meeting_link" name="meeting_link"
                            placeholder="https://meet.google.com/... o https://zoom.us/...">
                    </div>
                </div>

                <!-- Campo común para profesor y estudiante -->
                <div id="description-field" style="display: none;">
                    <div class="form-group-modern">
                        <label for="personal_description">Descripción Personal:</label>
                        <textarea id="personal_description" name="personal_description"
                            placeholder="Ingresa una breve descripción sobre ti"></textarea>

                    </div>
                </div>

                <button type="submit" class="btn-register">Registrarse</button>

                <div class="form-footer">
                    <p>© <?php echo date('Y'); ?> Plataforma Clases Online. Todos los derechos reservados.</p>
                </div>
            </form>
        </div>

        <!-- Panel derecho con ilustración educativa -->
        <div class="image-panel">
            <div class="hero-illustration">
                <div class="illustration-container">
                    <div class="education-elements">
                        <div class="book book-1">📖</div>
                        <div class="book book-2">📚</div>
                        <div class="book book-3">📗</div>
                        <div class="pencil pencil-1">✏️</div>
                        <div class="pencil pencil-2">🖍️</div>
                        <div class="pencil pencil-3">🖼️</div>
                        <div class="item item-1">🎨</div>
                        <div class="item item-2">📐</div>
                        <div class="item item-3">📏</div>
                        <div class="item item-4">🖊️</div>
                        <div class="school school-1">🏫</div>
                        <div class="students students-1">👨‍🎓</div>
                        <div class="students students-2">👩‍🎓</div>
                        <div class="students students-3">👩‍🎓</div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleFields() {
            const role = document.getElementById('id_role').value;
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

        // Debug para el formulario
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registerForm');
            form.addEventListener('submit', function(e) {
                console.log('Formulario enviado!');
                
                // Obtener todos los datos del formulario
                const formData = new FormData(form);
                console.log('Datos del formulario:');
                for (let [key, value] of formData.entries()) {
                    console.log(key + ': ' + value);
                }
                
                // Verificar campos requeridos
                const role = document.getElementById('id_role').value;
                const firstName = document.getElementById('first_name').value;
                const lastName = document.getElementById('last_name').value;
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;
                
                if (!role || !firstName || !lastName || !email || !password) {
                    console.error('Campos faltantes:', {
                        role: role,
                        firstName: firstName,
                        lastName: lastName,
                        email: email,
                        password: password ? 'filled' : 'empty'
                    });
                    alert('Por favor completa todos los campos requeridos');
                    e.preventDefault();
                    return false;
                }
                
                console.log('Todos los campos están completos, enviando...');
            });
        });
    </script>
    <script>
        // Auto-ocultar la notificación de error después de 5 segundos
        document.addEventListener('DOMContentLoaded', function () {
            const errorAlert = document.getElementById('errorAlert');
            if (errorAlert) {
                setTimeout(function () {
                    const bsAlert = new bootstrap.Alert(errorAlert);
                    bsAlert.close();
                }, 5000); // 5 segundos
            }
            
            const successAlert = document.getElementById('successAlert');
            if (successAlert) {
                setTimeout(function () {
                    const bsAlert = new bootstrap.Alert(successAlert);
                    bsAlert.close();
                }, 5000); // 5 segundos
            }
            
            // Debug del formulario
            const form = document.getElementById('registerForm');
            form.addEventListener('submit', function(e) {
                console.log('Formulario enviado');
                console.log('Action:', form.action);
                console.log('Method:', form.method);
                
                const formData = new FormData(form);
                console.log('Datos del formulario:');
                for (let [key, value] of formData.entries()) {
                    console.log(key, value);
                }
            });
        });
    </script>
</body>

</html>