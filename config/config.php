<?php
// Configuración general de la aplicación

// URL base de la aplicación (detecta automáticamente el puerto del servidor)
define('BASE_URL', 'http://localhost' . (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != 80 ? ':' . $_SERVER['SERVER_PORT'] : '') . '/plataforma-clases-online');

?>