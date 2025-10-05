<nav>
    <a href="/plataforma-clases-online/">Inicio</a>

    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'administrador'): ?>
        <a href="/plataforma-clases-online/home/profesores">Profesores</a>
        <a href="/plataforma-clases-online/home/estudiantes">Estudiantes</a>
        <a href="/plataforma-clases-online/home/reservas">Reservas</a>
        <a href="/plataforma-clases-online/home/disponibilidad">Disponibilidad</a>
        <a href="/plataforma-clases-online/home/pagos">Pagos</a>
        <a href="/plataforma-clases-online/home/reviews">Reviews</a>
    <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'profesor'): ?>
        <a href="/plataforma-clases-online/home/reservas">Mis Reservas</a>
        <a href="/plataforma-clases-online/home/disponibilidad">Mi Disponibilidad</a>
        <a href="/plataforma-clases-online/home/estudiantes">Mis Estudiantes</a>
    <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'estudiante'): ?>
        <a href="/plataforma-clases-online/home/reservas">Mis Reservas</a>
        <a href="/plataforma-clases-online/home/pagos">Mis Pagos</a>
        <a href="/plataforma-clases-online/home/profesores">Buscar Profesores</a>
    <?php endif; ?>

    <a href="/plataforma-clases-online/home/about">Acerca de</a>

    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="/plataforma-clases-online/auth/logout">Cerrar Sesión</a>
    <?php else: ?>
        <a href="/plataforma-clases-online/register">Registro</a>
        <a href="/plataforma-clases-online/auth/login">Iniciar Sesión</a>
    <?php endif; ?>
</nav>