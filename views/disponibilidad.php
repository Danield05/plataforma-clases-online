<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🕒 Disponibilidad - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php 
    // Definir la página actual para el header
    $currentPage = 'disponibilidad';
    ?>
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">🕒 Disponibilidad</h1>
            <?php include 'nav.php'; ?>
        </div>
    </header>
    
    <main>
        <?php
        // Evitar advertencias si el controlador no pasa estas variables
        $disponibilidades = isset($disponibilidades) ? $disponibilidades : [];
        // Definir todos los días de la semana para asegurar que se desplieguen siempre
        $dias = [
            ['week_day_id' => 1, 'day' => 'Lunes'],
            ['week_day_id' => 2, 'day' => 'Martes'],
            ['week_day_id' => 3, 'day' => 'Miércoles'],
            ['week_day_id' => 4, 'day' => 'Jueves'],
            ['week_day_id' => 5, 'day' => 'Viernes'],
            ['week_day_id' => 6, 'day' => 'Sábado'],
            ['week_day_id' => 7, 'day' => 'Domingo'],
        ];
        $estados = isset($estados) ? $estados : [];
        $profesores = isset($profesores) ? $profesores : [];
        $disponibilidad = isset($disponibilidad) ? $disponibilidad : null;
        $showForm = isset($showForm) ? $showForm : false;
        ?>
        <h2>Horarios Disponibles</h2>
        <?php if (!empty($_GET['status'])): ?>
            <div class="alert alert-info">Estado: <?php echo htmlspecialchars($_GET['status']); ?></div>
        <?php endif; ?>

        <?php if (!empty($showForm)): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <form method="post" action="<?php echo isset($disponibilidad) ? '/plataforma-clases-online/home/disponibilidad_update' : '/plataforma-clases-online/home/disponibilidad_store'; ?>">
                        <?php if (!empty($disponibilidad)): ?>
                            <input type="hidden" name="availability_id" value="<?php echo htmlspecialchars($disponibilidad['availability_id']); ?>">
                        <?php endif; ?>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Profesor</label>
                                <select name="user_id" class="form-select">
                                    <?php foreach($profesores as $p): ?>
                                        <option value="<?php echo $p['user_id']; ?>" <?php echo (!empty($disponibilidad) && $disponibilidad['user_id'] == $p['user_id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($p['first_name'].' '.$p['last_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Día</label>
                                <select name="week_day_id" class="form-select">
                                    <?php foreach($dias as $d): ?>
                                        <option value="<?php echo $d['week_day_id']; ?>" <?php echo (!empty($disponibilidad) && $disponibilidad['week_day_id'] == $d['week_day_id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($d['day']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Inicio</label>
                                <input type="time" name="start_time" class="form-control" value="<?php echo htmlspecialchars($disponibilidad['start_time'] ?? ''); ?>">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Fin</label>
                                <input type="time" name="end_time" class="form-control" value="<?php echo htmlspecialchars($disponibilidad['end_time'] ?? ''); ?>">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Estado</label>
                                <select name="reservation_status_id" class="form-select">
                                    <?php foreach($estados as $e): ?>
                                        <option value="<?php echo $e['reservation_status_id']; ?>" <?php echo (!empty($disponibilidad) && $disponibilidad['reservation_status_id'] == $e['reservation_status_id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($e['status']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-primary"><?php echo isset($disponibilidad) ? 'Actualizar' : 'Crear'; ?></button>
                            <a href="/plataforma-clases-online/home/disponibilidad" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="mb-3">
                <a href="/plataforma-clases-online/home/disponibilidad_create" class="btn btn-primary">Crear nueva disponibilidad</a>
            </div>
        <?php endif; ?>
        <table class="table tabla-pagos-nueva">
            <thead>
                <tr>
                    <th>Profesor</th>
                    <th>Día</th>
                    <th>Hora Inicio</th>
                    <th>Hora Fin</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach((array)$disponibilidades as $disp): ?>
                    <tr>
                        <td><?php echo $disp['first_name'] . ' ' . $disp['last_name']; ?></td>
                        <td><?php echo $disp['day']; ?></td>
                        <td><?php echo $disp['start_time']; ?></td>
                        <td><?php echo $disp['end_time']; ?></td>
                        <td><?php echo $disp['status']; ?></td>
                        <td>
                            <a href="/plataforma-clases-online/home/disponibilidad_edit?id=<?php echo $disp['availability_id']; ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                            <a href="/plataforma-clases-online/home/disponibilidad_delete?id=<?php echo $disp['availability_id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar disponibilidad?');">Eliminar</a>
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