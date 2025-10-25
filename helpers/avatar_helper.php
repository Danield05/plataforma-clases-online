<?php
/**
 * Funciones helper globales para la aplicación
 */

/**
 * Obtener la URL de la foto de perfil de un usuario
 * @param int $userId ID del usuario
 * @return string|null URL de la foto o null si no existe
 */
function getProfilePhotoUrl($userId) {
    if (empty($userId)) {
        return null;
    }
    
    $extensions = ['.jpg', '.jpeg', '.png', '.gif', '.webp'];
    $baseDir = $_SERVER['DOCUMENT_ROOT'] . '/plataforma-clases-online/public/uploads/profile_photos/';
    $baseUrl = '/plataforma-clases-online/public/uploads/profile_photos/';
    
    foreach ($extensions as $ext) {
        $filePath = $baseDir . 'user_' . $userId . $ext;
        if (file_exists($filePath)) {
            return $baseUrl . 'user_' . $userId . $ext . '?v=' . filemtime($filePath);
        }
    }
    return null;
}

/**
 * Generar avatar con color basado en el ID del usuario
 * @param int $userId ID del usuario
 * @param string $initials Iniciales del usuario
 * @param string $size Tamaño del avatar (small, medium, large)
 * @return string HTML del avatar
 */
function generateColoredAvatar($userId, $initials, $size = 'small') {
    $avatarClass = '';
    $sizeClass = '';
    
    switch ($size) {
        case 'small':
            $avatarClass = 'nav-avatar-' . (($userId % 8) + 1);
            $sizeClass = 'user-avatar-small';
            break;
        case 'medium':
            $avatarClass = 'avatar-' . (($userId % 8) + 1);
            $sizeClass = 'profile-avatar';
            break;
        case 'large':
            $avatarClass = 'avatar-' . (($userId % 8) + 1);
            $sizeClass = 'profile-avatar-large';
            break;
    }
    
    return '<div class="' . $sizeClass . ' ' . $avatarClass . '">' . htmlspecialchars($initials) . '</div>';
}

/**
 * Obtener el HTML completo del avatar (foto o generado)
 * @param int $userId ID del usuario
 * @param string $initials Iniciales del usuario
 * @param string $size Tamaño del avatar
 * @return string HTML del avatar
 */
function getUserAvatarHtml($userId, $initials, $size = 'small') {
    $profilePhotoUrl = getProfilePhotoUrl($userId);
    
    if ($profilePhotoUrl) {
        $imgClass = $size === 'small' ? 'nav-profile-photo' : 'profile-photo';
        $containerClass = $size === 'small' ? 'user-avatar-small' : 'profile-avatar-container';
        
        return '<div class="' . $containerClass . '"><img src="' . $profilePhotoUrl . '" alt="Foto de perfil" class="' . $imgClass . '"></div>';
    } else {
        return generateColoredAvatar($userId, $initials, $size);
    }
}
?>