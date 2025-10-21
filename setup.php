-- Active: 1760930496821@@localhost@3306@phpmyadmin
<?php
// Script de instalación automática para Plataforma de Clases Online

echo "🚀 Instalador de Plataforma de Clases Online\n";
echo "==========================================\n\n";

// Verificar PHP
echo "1. Verificando versión de PHP...\n";
if (version_compare(PHP_VERSION, '7.4.0', '<')) {
    die("❌ Error: Se requiere PHP 7.4 o superior. Versión actual: " . PHP_VERSION . "\n");
}
echo "✅ PHP " . PHP_VERSION . " - OK\n\n";

// Verificar extensiones requeridas
echo "2. Verificando extensiones PHP...\n";
$required_extensions = ['pdo', 'pdo_mysql', 'session', 'mbstring'];
$missing_extensions = [];

foreach ($required_extensions as $ext) {
    if (!extension_loaded($ext)) {
        $missing_extensions[] = $ext;
    }
}

if (!empty($missing_extensions)) {
    echo "❌ Extensiones faltantes: " . implode(', ', $missing_extensions) . "\n";
    echo "Por favor, instala las extensiones faltantes en tu servidor.\n";
    exit(1);
}
echo "✅ Todas las extensiones requeridas están instaladas\n\n";

// Verificar archivo de configuración
echo "3. Verificando configuración de base de datos...\n";
$config_file = 'config/database.php';

if (!file_exists($config_file)) {
    if (file_exists('config/database.php')) {
        echo "⚠️  Archivo database.php no encontrado.\n";
        echo "Copiando archivo de ejemplo...\n";
        copy('config/database.example.php', $config_file);
        echo "✅ Archivo creado. Edítalo con tus credenciales reales.\n";
    } else {
        echo "❌ No se encontró archivo de configuración ni plantilla.\n";
        exit(1);
    }
} else {
    echo "✅ Archivo de configuración encontrado\n";
}

// Intentar conectar a la base de datos
echo "\n4. Probando conexión a base de datos...\n";
try {
    require_once $config_file;

    // Verificar que las variables existen
    if (!isset($pdo)) {
        throw new Exception("Variable \$pdo no definida en config/database.php");
    }

    // Probar conexión
    $stmt = $pdo->query("SELECT 1");
    echo "✅ Conexión a base de datos exitosa\n";

    // Verificar si las tablas existen
    echo "\n5. Verificando estructura de base de datos...\n";
    $tables = ['usuarios', 'roles', 'estados_usuario', 'administrador', 'profesor', 'estudiante', 'estados_pago', 'pagos', 'estados_reserva', 'estados_disponibilidad', 'dias_semana', 'materias', 'profesor_materia', 'estudiante_materia', 'disponibilidad_profesores', 'reservas', 'reviews'];
    $missing_tables = [];

    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() == 0) {
            $missing_tables[] = $table;
        }
    }

    if (!empty($missing_tables)) {
        echo "❌ Tablas faltantes: " . implode(', ', $missing_tables) . "\n";
        echo "Ejecuta primero el script plataforma_clases.sql en phpMyAdmin\n";
        exit(1);
    } else {
        echo "✅ Todas las tablas existen\n";
    }

    // Insertar datos de prueba
    echo "\n6. Insertando datos de prueba...\n";

    try {
        // Insertar estados de usuario básicos
        $pdo->exec("INSERT IGNORE INTO Estados_Usuario (user_status_id, status, description) VALUES
            (1, 'Activo', 'Usuario activo en el sistema'),
            (2, 'Inactivo', 'Usuario inactivo')");

        // Insertar estados de pago básicos
        $pdo->exec("INSERT IGNORE INTO Estados_Pago (payment_status_id, status, description) VALUES
            (1, 'Pendiente', 'Pago pendiente de procesamiento'),
            (2, 'Completado', 'Pago completado exitosamente')");

        // Insertar estados de reserva básicos
        $pdo->exec("INSERT IGNORE INTO Estados_Reserva (reservation_status_id, status, description) VALUES
            (1, 'Pendiente', 'Reserva solicitada pero no confirmada'),
            (2, 'Confirmada', 'Reserva confirmada por el profesor'),
            (3, 'Cancelada', 'Reserva cancelada'),
            (4, 'Completada', 'Clase completada')");

        // Insertar estados de disponibilidad básicos
        $pdo->exec("INSERT IGNORE INTO Estados_Disponibilidad (availability_status_id, status, description) VALUES
            (1, 'Disponible', 'Horario disponible para reservas'),
            (2, 'No Disponible', 'Horario no disponible para reservas')");

        // Insertar días de la semana
        $pdo->exec("INSERT IGNORE INTO Dias_Semana (week_day_id, day, day_order) VALUES
            (1, 'Lunes', 1),
            (2, 'Martes', 2),
            (3, 'Miércoles', 3),
            (4, 'Jueves', 4),
            (5, 'Viernes', 5),
            (6, 'Sábado', 6),
            (7, 'Domingo', 7)");

        // Insertar roles
        $pdo->exec("INSERT IGNORE INTO Roles (role_id, role_name, description) VALUES
            (1, 'administrador', 'Usuario con acceso completo al sistema'),
            (2, 'profesor', 'Usuario que imparte clases'),
            (3, 'estudiante', 'Usuario que toma clases')");

        // Insertar materias
        $pdo->exec("INSERT IGNORE INTO Materias (subject_id, subject_name, description, price_per_hour) VALUES
            (1, 'Matemáticas', 'Clases de matemáticas básicas y avanzadas', 15.00),
            (2, 'Física', 'Clases de física teórica y práctica', 18.00),
            (3, 'Química', 'Clases de química general y orgánica', 16.00),
            (4, 'Programación', 'Clases de desarrollo de software', 20.00),
            (5, 'Inglés', 'Clases de inglés conversacional y académico', 12.00),
            (6, 'Español', 'Clases de literatura y gramática española', 14.00)");

        // Generar hashes de contraseñas reales
        $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
        $professor_password = password_hash('prof123', PASSWORD_DEFAULT);
        $student_password = password_hash('estu123', PASSWORD_DEFAULT);

        // Insertar usuarios de prueba
        $pdo->exec("INSERT IGNORE INTO Usuarios (user_id, first_name, last_name, email, password, phone, role_id, user_status_id, bio) VALUES
            (1, 'Admin', 'Sistema', 'admin@plataforma.com', '$admin_password', '1234-5678', 1, 1, 'Administrador del sistema'),
            (2, 'María', 'González', 'maria.profesor@plataforma.com', '$professor_password', '2222-1111', 2, 1, 'Profesora de Matemáticas y Física'),
            (3, 'Carlos', 'Rodríguez', 'carlos.profesor@plataforma.com', '$professor_password', '3333-2222', 2, 1, 'Profesor de Programación'),
            (4, 'Ana', 'Martínez', 'ana.profesor@plataforma.com', '$professor_password', '4444-3333', 2, 1, 'Profesora de Inglés'),
            (5, 'Juan', 'Pérez', 'juan.estudiante@plataforma.com', '$student_password', '5555-4444', 3, 1, 'Estudiante de Ingeniería'),
            (6, 'María', 'López', 'maria.estudiante@plataforma.com', '$student_password', '6666-5555', 3, 1, 'Estudiante de Administración'),
            (7, 'Pedro', 'Sánchez', 'pedro.estudiante@plataforma.com', '$student_password', '7777-6666', 3, 1, 'Estudiante de secundaria')");

        // Insertar horarios de profesores
        $pdo->exec("INSERT IGNORE INTO Disponibilidad_Profesores (availability_id, user_id, week_day_id, availability_status_id, start_time, end_time, subject_id, price_per_hour) VALUES
            (1, 2, 1, 1, '08:00:00', '10:00:00', 1, 15.00),
            (2, 2, 1, 1, '14:00:00', '16:00:00', 2, 18.00),
            (3, 2, 3, 1, '09:00:00', '11:00:00', 1, 15.00),
            (4, 3, 2, 1, '16:00:00', '18:00:00', 4, 20.00),
            (5, 3, 4, 1, '17:00:00', '19:00:00', 4, 20.00),
            (6, 4, 1, 1, '11:00:00', '13:00:00', 5, 12.00),
            (7, 4, 2, 1, '11:00:00', '13:00:00', 5, 12.00),
            (8, 4, 3, 1, '11:00:00', '13:00:00', 5, 12.00)");

        // Insertar reservas de prueba
        $pdo->exec("INSERT IGNORE INTO Reservas (reservation_id, user_id, student_user_id, availability_id, reservation_status_id, class_date, class_time, notes) VALUES
            (1, 2, 5, 1, 1, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '08:00:00', 'Clase de Matemáticas básicas'),
            (2, 2, 5, 2, 2, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '14:00:00', 'Repaso de Física'),
            (3, 3, 6, 4, 1, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '16:00:00', 'Introducción a Programación'),
            (4, 4, 7, 6, 2, DATE_ADD(CURDATE(), INTERVAL 4 DAY), '11:00:00', 'Inglés conversacional')");

        // Insertar pagos de prueba (solo PayPal)
        $pdo->exec("INSERT IGNORE INTO Pagos (payment_id, user_id, amount, payment_status_id, payment_date, transaction_id, payment_method, description) VALUES
            (1, 5, 15.00, 2, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'TXN_001', 'paypal', 'Pago por clase de Matemáticas'),
            (2, 5, 18.00, 2, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'TXN_002', 'paypal', 'Pago por clase de Física'),
            (3, 6, 20.00, 1, CURDATE(), 'TXN_003', 'paypal', 'Pago pendiente por clase de Programación')");

        // Insertar reviews de prueba
        $pdo->exec("INSERT IGNORE INTO Reviews (review_id, reservation_id, reviewer_user_id, reviewed_user_id, rating, comment) VALUES
            (1, 1, 5, 2, 5, 'Excelente profesora, muy paciente y explica muy bien los conceptos'),
            (2, 2, 5, 2, 4, 'Buena clase de física, me ayudó mucho con los conceptos básicos'),
            (3, 3, 6, 3, 5, 'Carlos es un excelente profesor de programación')");

        // Crear relaciones entre usuarios y sus tablas específicas
        echo "Creando relaciones de usuarios...\n";

        // Administradores
        $pdo->exec("INSERT IGNORE INTO Administrador (user_id, permissions) VALUES
            (1, 'full_access')");

        // Profesores
        $pdo->exec("INSERT IGNORE INTO Profesor (user_id, personal_description) VALUES
            (2, 'Profesora de Matemáticas y Física'),
            (3, 'Profesor de Programación'),
            (4, 'Profesora de Inglés')");

        // Estudiantes
        $pdo->exec("INSERT IGNORE INTO Estudiante (user_id, personal_description) VALUES
            (5, 'Estudiante de Ingeniería'),
            (6, 'Estudiante de Administración'),
            (7, 'Estudiante de secundaria')");

        // Insertar relaciones Profesor-Materia
        $pdo->exec("INSERT IGNORE INTO Profesor_Materia (profesor_id, subject_id, experience_years, certification) VALUES
            (1, 1, 5, 'Certificación en Matemáticas Avanzadas'),
            (1, 2, 3, 'Certificación en Física'),
            (2, 4, 8, 'Certificación en Desarrollo de Software'),
            (3, 5, 6, 'Certificación TEFL'),
            (3, 6, 4, 'Certificación en Literatura Española')");

        // Insertar relaciones Estudiante-Materia
        $pdo->exec("INSERT IGNORE INTO Estudiante_Materia (estudiante_id, subject_id, enrollment_date, status) VALUES
            (1, 1, DATE_SUB(CURDATE(), INTERVAL 30 DAY), 'activo'),
            (1, 2, DATE_SUB(CURDATE(), INTERVAL 25 DAY), 'activo'),
            (1, 4, DATE_SUB(CURDATE(), INTERVAL 20 DAY), 'activo'),
            (2, 4, DATE_SUB(CURDATE(), INTERVAL 15 DAY), 'activo'),
            (2, 5, DATE_SUB(CURDATE(), INTERVAL 10 DAY), 'activo'),
            (3, 1, DATE_SUB(CURDATE(), INTERVAL 35 DAY), 'completado'),
            (3, 5, DATE_SUB(CURDATE(), INTERVAL 5 DAY), 'activo')");

        echo "✅ Relaciones de usuarios creadas correctamente\n";
        echo "✅ Datos de prueba insertados correctamente\n";

    } catch (Exception $e) {
        echo "❌ Error insertando datos de prueba: " . $e->getMessage() . "\n";
    }


} catch (Exception $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "\n";
    echo "Verifica tus credenciales en config/database.php\n";
    exit(1);
}

// Verificar permisos de archivos
echo "\n7. Verificando permisos de archivos...\n";
$writable_dirs = ['config', 'public'];
$permission_errors = [];

foreach ($writable_dirs as $dir) {
    if (!is_writable($dir)) {
        $permission_errors[] = $dir;
    }
}

if (!empty($permission_errors)) {
    echo "⚠️  Directorios sin permisos de escritura: " . implode(', ', $permission_errors) . "\n";
} else {
    echo "✅ Permisos de archivos correctos\n";
}

echo "\n🎉 Instalación completada!\n";
echo "========================\n";
echo "Tu plataforma está lista. Accede a: http://localhost/plataforma-clases-online\n\n";

echo "Usuarios de prueba:\n";
echo "- Admin: admin@plataforma.com / admin123\n";
echo "- Profesores: maria.profesor@plataforma.com, carlos.profesor@plataforma.com, ana.profesor@plataforma.com / prof123\n";
echo "- Estudiantes: juan.estudiante@plataforma.com, maria.estudiante@plataforma.com, pedro.estudiante@plataforma.com / estu123\n\n";

echo "Para registrar nuevos usuarios: http://localhost:8080/plataforma-clases-online/register\n";
echo "Para iniciar sesión: http://localhost/plataforma-clases-online/auth/login\n\n";

echo "¡Disfruta tu plataforma de clases online! 📚\n";
?>