<?php
session_start();

// Punto de entrada del proyecto MVC en PHP

// Incluir configuración de base de datos
require_once 'config/database.php';

// Parsear la URL para determinar controlador y acción
$url = isset($_GET['url']) ? $_GET['url'] : 'home';

// Remover el prefijo del proyecto si existe
$url = preg_replace('/^plataforma-clases-online\//', '', $url);

// Manejar rutas de auth
if (isset($url[0]) && $url[0] === 'auth') {
    $controller = 'AuthController';
    $action = isset($url[1]) ? $url[1] : 'login';
}

$url = rtrim($url, '/');
$url = explode('/', $url);

// Controlador por defecto
$controller = 'HomeController';

// Acción por defecto
$action = 'index';

if (isset($url[0]) && !empty($url[0])) {
    $controller = ucfirst($url[0]) . 'Controller';
}

if (isset($url[1]) && !empty($url[1])) {
    $action = $url[1];
}

// Manejar rutas específicas para estudiantes
if ($controller === 'HomeController' && isset($url[1]) && $url[1] === 'get_available_slots') {
    $action = 'get_available_slots';
}
if ($controller === 'HomeController' && isset($url[1]) && $url[1] === 'reservar_clase') {
    $action = 'reservar_clase';
}

// Incluir y ejecutar el controlador
$controllerFile = 'controllers/' . $controller . '.php';
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controllerInstance = new $controller();
    if (method_exists($controllerInstance, $action)) {
        $controllerInstance->$action();
    } else {
        echo 'Acción no encontrada';
    }
} else {
    echo 'Controlador no encontrado';
}
?>