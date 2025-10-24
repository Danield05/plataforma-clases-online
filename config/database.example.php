<?php
// Configuración de la base de datos
$host = 'localhost';
$dbname = ''; // Cambia al nombre de tu base de datos
$username = 'root'; // Usuario por defecto en XAMPP
$password = ''; // Contraseña por defecto en XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>