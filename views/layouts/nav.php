<?php
// Detectar la página actual desde la URL
$currentUrl = $_SERVER['REQUEST_URI'];
$currentPage = $currentPage ?? '';

// Función para detectar si un enlace está activo
function isActive($page, $currentUrl, $currentPage = '') {
    if (!empty($currentPage) && $currentPage === $page) {
        return 'active';
    }
    if (strpos($currentUrl, $page) !== false) {
        return 'active';
    }
    return '';
}

// Función para obtener el nombre del rol formateado
function getRoleDisplayName($role) {
    switch ($role) {
        case 'administrador':
            return 'Administrador';
        case 'profesor':
            return 'Profesor';
        case 'estudiante':
            return 'Estudiante';
        default:
            return 'Usuario';
    }
}
?>

<!-- Información del usuario logueado -->
<?php if (isset($_SESSION['user_id'])): ?>
<div class="user-info-bar">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center py-2 px-3" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <div class="d-flex align-items-center">
                        <?php 
                        // Incluir funciones helper para avatares
                        require_once __DIR__ . '/../../helpers/avatar_helper.php';
                        
                        // Obtener foto de perfil o avatar generado
                        $profilePhotoUrl = getProfilePhotoUrl($_SESSION['user_id']);
                        
                        if ($profilePhotoUrl): ?>
                            <div class="user-avatar-small me-3">
                                <img src="<?php echo $profilePhotoUrl; ?>" alt="Foto de perfil" class="nav-profile-photo">
                            </div>
                        <?php else: 
                            // Generar avatar con color basado en user_id
                            $avatarClass = 'nav-avatar-' . (($_SESSION['user_id'] % 8) + 1);
                            $initials = strtoupper(substr($_SESSION['first_name'] ?? $_SESSION['user_name'] ?? 'U', 0, 1));
                        ?>
                            <div class="user-avatar-small me-3 <?php echo $avatarClass; ?>">
                                <?php echo $initials; ?>
                            </div>
                        <?php endif; ?>
                        <div class="user-details">
                            <div class="user-name"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Usuario'); ?></div>
                            <div class="user-role"><?php echo getRoleDisplayName($_SESSION['role'] ?? ''); ?></div>
                        </div>
                    </div>
                    <div class="nav-quick-stats">
                        <span class="quick-stat">
                            <span class="stat-icon">🕒</span>
                            <span class="current-time" id="current-time"><?php echo date('H:i'); ?></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Navegación principal -->
<nav class="modern-nav">
    <div class="nav-container">
        <a href="/plataforma-clases-online/" class="nav-link <?= isActive('inicio', $currentUrl, $currentPage); ?>">
            <span class="nav-icon">🏠</span>
            <span class="nav-text">Inicio</span>
        </a>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'administrador'): ?>
            <a href="/plataforma-clases-online/reportes/general" class="nav-link <?= isActive('reportes', $currentUrl, $currentPage); ?>">
                <span class="nav-icon">📊</span>
                <span class="nav-text">Reportes</span>
            </a>
            <a href="/plataforma-clases-online/home/profesores" class="nav-link <?= isActive('profesores', $currentUrl, $currentPage); ?>">
                <span class="nav-icon">👨‍🏫</span>
                <span class="nav-text">Profesores</span>
            </a>
            <a href="/plataforma-clases-online/home/estudiantes" class="nav-link <?= isActive('estudiantes', $currentUrl, $currentPage); ?>">
                <span class="nav-icon">👨‍🎓</span>
                <span class="nav-text">Estudiantes</span>
            </a>
            <a href="/plataforma-clases-online/home/reservas" class="nav-link <?= isActive('reservas', $currentUrl, $currentPage); ?>">
                <span class="nav-icon">📅</span>
                <span class="nav-text">Reservas</span>
            </a>
            <a href="/plataforma-clases-online/home/disponibilidad" class="nav-link <?= isActive('disponibilidad', $currentUrl, $currentPage); ?>">
                <span class="nav-icon">🕒</span>
                <span class="nav-text">Disponibilidad</span>
            </a>
            <a href="/plataforma-clases-online/home/pagos" class="nav-link <?= isActive('pagos', $currentUrl, $currentPage); ?>">
                <span class="nav-icon">💰</span>
                <span class="nav-text">Pagos</span>
            </a>
            <a href="/plataforma-clases-online/home/reviews" class="nav-link <?= isActive('reviews', $currentUrl, $currentPage); ?>">
                <span class="nav-icon">⭐</span>
                <span class="nav-text">Reviews</span>
            </a>
        <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'profesor'): ?>
            <a href="/plataforma-clases-online/reportes/profesor" class="nav-link <?= isActive('reportes', $currentUrl, $currentPage); ?>">
                <span class="nav-icon">📊</span>
                <span class="nav-text">Ver Reportes</span>
            </a>
            <a href="/plataforma-clases-online/home/reservas" class="nav-link <?= isActive('reservas', $currentUrl, $currentPage); ?>">
                <span class="nav-icon">📅</span>
                <span class="nav-text">Mis Reservas</span>
            </a>
            <a href="/plataforma-clases-online/home/disponibilidad" class="nav-link <?= isActive('disponibilidad', $currentUrl, $currentPage); ?>">
                <span class="nav-icon">🕒</span>
                <span class="nav-text">Mi Disponibilidad</span>
            </a>
            <a href="/plataforma-clases-online/home/estudiantes" class="nav-link <?= isActive('estudiantes', $currentUrl, $currentPage); ?>">
                <span class="nav-icon">👨‍🎓</span>
                <span class="nav-text">Mis Estudiantes</span>
            </a>
            <a href="/plataforma-clases-online/home/pagos" class="nav-link <?= isActive('pagos', $currentUrl, $currentPage); ?>">
                <span class="nav-icon">💰</span>
                <span class="nav-text">Ingresos</span>
            </a>
            <a href="/plataforma-clases-online/home/perfil_edit" class="nav-link <?= isActive('perfil_edit', $currentUrl, $currentPage); ?>">
                <span class="nav-icon">👤</span>
                <span class="nav-text">Mi Perfil</span>
            </a>
        <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'estudiante'): ?>
            <a href="/plataforma-clases-online/reportes/estudiante" class="nav-link <?= isActive('reportes', $currentUrl, $currentPage); ?>">
                <span class="nav-icon">📊</span>
                <span class="nav-text">Mis Reportes</span>
            </a>
            <a href="/plataforma-clases-online/home/reservas" class="nav-link <?= isActive('reservas', $currentUrl, $currentPage); ?>">
                <span class="nav-icon">📅</span>
                <span class="nav-text">Mis Reservas</span>
            </a>
            <a href="/plataforma-clases-online/home/pagos" class="nav-link <?= isActive('pagos', $currentUrl, $currentPage); ?>">
                <span class="nav-icon">💰</span>
                <span class="nav-text">Mis Pagos</span>
            </a>
            <a href="/plataforma-clases-online/home/explorar_profesores" class="nav-link <?= isActive('explorar_profesores', $currentUrl, $currentPage); ?>">
                <span class="nav-icon">🔍</span>
                <span class="nav-text">Buscar Profesores</span>
            </a>
            <a href="/plataforma-clases-online/home/perfil_view" class="nav-link <?= isActive('perfil_view', $currentUrl, $currentPage); ?>">
                <span class="nav-icon">👤</span>
                <span class="nav-text">Mi Perfil</span>
            </a>
        <?php endif; ?>

        <a href="/plataforma-clases-online/home/about" class="nav-link <?= isActive('about', $currentUrl, $currentPage); ?>">
            <span class="nav-icon">ℹ️</span>
            <span class="nav-text">Acerca de</span>
        </a>

        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/plataforma-clases-online/auth/logout" class="nav-link logout">
                <span class="nav-icon">🚪</span>
                <span class="nav-text">Cerrar Sesión</span>
            </a>
        <?php else: ?>
            <a href="/plataforma-clases-online/register" class="nav-link">
                <span class="nav-icon">📝</span>
                <span class="nav-text">Registro</span>
            </a>
            <a href="/plataforma-clases-online/auth/login" class="nav-link">
                <span class="nav-icon">🔑</span>
                <span class="nav-text">Iniciar Sesión</span>
            </a>
        <?php endif; ?>
    </div>
</nav>

<!-- Script para actualizar el reloj en tiempo real -->
<script>
function updateClock() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('es-ES', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false
    });
    
    const clockElement = document.getElementById('current-time');
    if (clockElement) {
        clockElement.textContent = timeString;
    }
}

// Actualizar el reloj inmediatamente al cargar la página
updateClock();

// Actualizar el reloj cada segundo
setInterval(updateClock, 1000);
</script>