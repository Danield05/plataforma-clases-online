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
    if (file_exists('config/database.example.php')) {
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
    $tables = ['usuarios', 'roles', 'estados_usuario', 'administrador', 'profesor', 'estudiante'];
    $missing_tables = [];

    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() == 0) {
            $missing_tables[] = $table;
        }
    }

    if (!empty($missing_tables)) {
        echo "⚠️  Tablas faltantes: " . implode(', ', $missing_tables) . "\n";
        echo "Ejecuta el archivo plataforma_clases.sql en tu base de datos.\n";
    } else {
        echo "✅ Todas las tablas existen\n";
    }

} catch (Exception $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "\n";
    echo "Verifica tus credenciales en config/database.php\n";
    exit(1);
}

// Verificar permisos de archivos
echo "\n6. Verificando permisos de archivos...\n";
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
echo "- Profesor: profesor@plataforma.com / prof123\n";
echo "- Estudiante: estudiante@plataforma.com / estu123\n\n";

echo "Para registrar nuevos usuarios: http://localhost/plataforma-clases-online/register\n";
echo "Para iniciar sesión: http://localhost/plataforma-clases-online/auth/login\n\n";

echo "¡Disfruta tu plataforma de clases online! 📚\n";
?>