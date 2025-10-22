<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔍 Buscar Profesores - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php 
    // Definir la página actual para el header
    $currentPage = 'profesores';
    ?>
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">🔍 Buscar Profesores</h1>
            <?php include __DIR__ . '/../layouts/nav.php'; ?>
        </div>
    </header>
    
    <main>
        <h2>Lista de Profesores</h2>

        <!-- Formulario de búsqueda -->
        <div class="mb-4">
            <form method="GET" action="/plataforma-clases-online/home/explorar_profesores" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Buscar por nombre..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                <button type="submit" class="btn btn-primary">Buscar</button>
                <?php if (!empty($_GET['search'])): ?>
                    <a href="/plataforma-clases-online/home/explorar_profesores" class="btn btn-secondary ms-2">Limpiar</a>
                <?php endif; ?>
            </form>
        </div>

        <?php
        $profesores = isset($profesores) ? $profesores : [];
        $showForm = isset($showForm) ? $showForm : false;
        $profesor = isset($profesor) ? $profesor : null;
        ?>

        <?php if (!empty($showForm)): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <form method="post" action="<?php echo $profesor ? '/plataforma-clases-online/home/profesores_update' : '/plataforma-clases-online/home/profesores_store'; ?>">
                        <?php if ($profesor): ?>
                            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($profesor['user_id']); ?>">
                        <?php endif; ?>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Nombre</label>
                                <input name="first_name" class="form-control" value="<?php echo htmlspecialchars($profesor['first_name'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Apellido</label>
                                <input name="last_name" class="form-control" value="<?php echo htmlspecialchars($profesor['last_name'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Email</label>
                                <input name="email" type="email" class="form-control" value="<?php echo htmlspecialchars($profesor['email'] ?? ''); ?>" required>
                            </div>
                        </div>
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label class="form-label">Nivel Académico</label>
                                <input name="academic_level" class="form-control" value="<?php echo htmlspecialchars($profesor['academic_level'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tarifa por Hora</label>
                                <input name="hourly_rate" type="number" step="0.01" class="form-control" value="<?php echo htmlspecialchars($profesor['hourly_rate'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="form-label">Descripción personal</label>
                            <textarea name="personal_description" class="form-control"><?php echo htmlspecialchars($profesor['personal_description'] ?? ''); ?></textarea>
                        </div>
                        <div class="mt-3">
                            <label class="form-label">Contraseña<?php echo $profesor ? ' (opcional)' : ''; ?></label>
                            <input type="password" name="password" class="form-control"<?php echo !$profesor ? ' required' : ''; ?>>
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-primary"><?php echo $profesor ? 'Actualizar' : 'Crear'; ?></button>
                            <a href="/plataforma-clases-online/home/profesores" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        <?php else: ?>
        <?php endif; ?>

        <table class="table tabla-pagos-nueva">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Nivel Académico</th>
                    <th>Tarifa por Hora</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach((array)$profesores as $pr): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($pr['first_name'] . ' ' . $pr['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($pr['email']); ?></td>
                        <td><?php echo htmlspecialchars($pr['academic_level'] ?? 'N/A'); ?></td>
                        <td><?php echo $pr['hourly_rate'] ? '$' . htmlspecialchars($pr['hourly_rate']) : 'N/A'; ?></td>
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