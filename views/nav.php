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
?>

<nav class="modern-nav">
    <div class="nav-container">
        <a href="/plataforma-clases-online/" class="nav-link <?= isActive('inicio', $currentUrl, $currentPage); ?>">
            <span class="nav-icon">🏠</span>
            <span class="nav-text">Inicio</span>
        </a>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'administrador'): ?>
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
        <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'estudiante'): ?>
            <a href="/plataforma-clases-online/home/reservas" class="nav-link <?= isActive('reservas', $currentUrl, $currentPage); ?>">
                <span class="nav-icon">📅</span>
                <span class="nav-text">Mis Reservas</span>
            </a>
            <a href="/plataforma-clases-online/home/pagos" class="nav-link <?= isActive('pagos', $currentUrl, $currentPage); ?>">
                <span class="nav-icon">💰</span>
                <span class="nav-text">Mis Pagos</span>
            </a>
            <a href="/plataforma-clases-online/home/profesores" class="nav-link <?= isActive('profesores', $currentUrl, $currentPage); ?>">
                <span class="nav-icon">🔍</span>
                <span class="nav-text">Buscar Profesores</span>
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