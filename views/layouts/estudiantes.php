<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎓 Estudiantes - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php 
    // Definir la página actual para el header
    $currentPage = 'estudiantes';
    ?>
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">🎓 Estudiantes</h1>
            <?php include __DIR__ . '/nav.php'; ?>
        </div>
    </header>
    
    <main>
            <?php
            // Inicializaciones defensivas para evitar warnings si la variable no viene definida
            $estudiantes = isset($estudiantes) ? $estudiantes : [];
            $showForm = isset($showForm) ? $showForm : false;
            $estudiante = isset($estudiante) ? $estudiante : null;
            $user = isset($user) ? $user : null;
            ?>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2><?php echo $_SESSION['role'] === 'profesor' ? 'Mis Estudiantes' : 'Estudiantes'; ?></h2>
                <div>
                    <?php if ($_SESSION['role'] === 'administrador'): ?>
                        <a href="/plataforma-clases-online/home/estudiantes_create" class="btn btn-primary">Crear estudiante</a>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($showForm): ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><?= $estudiante ? 'Editar estudiante' : 'Nuevo estudiante' ?></h5>
                        <form method="post" action="<?= $estudiante ? '/plataforma-clases-online/home/estudiantes_update' : '/plataforma-clases-online/home/estudiantes_store' ?>">
                            <?php if ($estudiante): ?>
                                <input type="hidden" name="user_id" value="<?= $estudiante['user_id'] ?>">
                            <?php endif; ?>
                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="first_name" class="form-control" value="<?= $user ? htmlspecialchars($user['first_name']) : '' ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Apellido</label>
                                <input type="text" name="last_name" class="form-control" value="<?= $user ? htmlspecialchars($user['last_name']) : '' ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="<?= $user ? htmlspecialchars($user['email']) : '' ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Contraseña<?= $estudiante ? ' (opcional)' : '' ?></label>
                                <input type="password" name="password" class="form-control"<?= !$estudiante ? ' required' : '' ?>>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Descripción personal</label>
                                <textarea name="personal_description" class="form-control"><?= $estudiante ? htmlspecialchars($estudiante['personal_description']) : '' ?></textarea>
                            </div>
                            <button class="btn btn-success" type="submit">Guardar</button>
                            <a href="/plataforma-clases-online/home/estudiantes" class="btn btn-secondary">Cancelar</a>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Email</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ((array)$estudiantes as $e): ?>
                        <tr>
                            <td><?= $e['user_id'] ?></td>
                            <td><?= htmlspecialchars($e['first_name']) ?></td>
                            <td><?= htmlspecialchars($e['last_name']) ?></td>
                            <td><?= htmlspecialchars($e['email']) ?></td>
                            <td><?= htmlspecialchars($e['personal_description'] ?? '') ?></td>
                            <td>
                                <?php if ($_SESSION['role'] === 'administrador'): ?>
                                    <a href="/plataforma-clases-online/home/estudiantes_edit?id=<?= $e['user_id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                                    <a href="/plataforma-clases-online/home/estudiantes_delete?id=<?= $e['user_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar estudiante?');">Eliminar</a>
                                <?php else: ?>
                                    <a href="/plataforma-clases-online/home/ver_estudiante?id=<?= $e['user_id'] ?>" class="btn btn-sm btn-outline-primary">Ver Perfil</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
    </main>
    
    <footer class="modern-footer">
        <div class="footer-content">
            <div class="footer-info">
                <div class="footer-brand">
                    <span>💎</span>
                    <span>Plataforma Clases Online</span>
                </div>
                <div class="footer-links">
                    <a href="#privacidad">Privacidad</a>
                    <a href="#terminos">Términos</a>
                    <a href="#soporte">Soporte</a>
                    <a href="#contacto">Contacto</a>
                </div>
            </div>
            <div class="footer-copy">
                © <?php echo date('Y'); ?> Plataforma Clases Online. Todos los derechos reservados.
            </div>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/plataforma-clases-online/public/js/script.js"></script>
</body>
</html>