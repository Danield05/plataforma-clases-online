<?php
// Verificar GD
if (extension_loaded('gd')) {
    echo json_encode([
        'gd_enabled' => true,
        'gd_version' => gd_info()['GD Version'] ?? 'Desconocida',
        'supported_formats' => [
            'jpeg' => function_exists('imagecreatefromjpeg'),
            'png' => function_exists('imagecreatefrompng'),
            'gif' => function_exists('imagecreatefromgif'),
            'webp' => function_exists('imagecreatefromwebp')
        ]
    ]);
} else {
    echo json_encode(['gd_enabled' => false]);
}
?>