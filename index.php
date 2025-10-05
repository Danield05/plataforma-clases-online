<?php
// Punto de entrada del proyecto MVC en PHP

// Incluir configuración de base de datos
require_once 'config/database.php';

// Parsear la URL para determinar controlador y acción
$url = isset($_GET['url']) ? $_GET['url'] : 'home';
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