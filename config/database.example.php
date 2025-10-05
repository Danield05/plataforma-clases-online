<?php
// Configuración de la base de datos
// COPIAR ESTE ARCHIVO COMO database.php Y CONFIGURAR CON TUS CREDENCIALES REALES

$host = 'localhost';                    // Servidor de BD (localhost para desarrollo local)
$dbname = 'plataforma_clases';          // Nombre de la base de datos
$username = 'root';                     // Usuario de MySQL (root por defecto en XAMPP)
$password = '1234';                     // Contraseña de MySQL (1234 por defecto en XAMPP)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>