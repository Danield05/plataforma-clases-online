<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🕒 Disponibilidad - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=<?php echo time(); ?>">
    <style>
        .availability-header {
            background: linear-gradient(135deg, #6f42c1, #8e44ad);
            color: white;
            border-radius: 10px 10px 0 0;
            padding: 20px;
            margin-bottom: 0;
        }
        .availability-table {
            background: white;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .profesor-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 10px;
        }
        .estado-disponible {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        .btn-editar {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        .btn-eliminar {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        .table th {
            background: #f8f9fa;
            border: none;
            font-weight: 600;
            color: #495057;
        }
        .table td {
            border: none;
            vertical-align: middle;
        }
    </style>
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
        <h2>📅 Gestionar Horarios</h2>
        <?php if (!empty($_GET['status'])): ?>
            <div class="alert alert-info">Estado: <?php echo htmlspecialchars($_GET['status']); ?></div>
        <?php endif; ?>

        <?php if (empty($showForm)): ?>
            <!-- Estado vacío con reloj - SIEMPRE visible arriba -->
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8">
                    <div class="text-center py-4" style=" border-radius: 15px; margin: 20px 0;">
                        <div class="mb-4" style="font-size: 6rem; color: #6f42c1;">⏰</div>
                        <h1 class="mb-3" style="color: #333; font-weight: 600;">Configurar Horarios de Disponibilidad</h1>
                        <p class="text-muted mb-4" style="font-size: 1.1rem; line-height: 1.6;">Define tus horarios semanales para que los estudiantes puedan reservar clases contigo.</p>
                        <a href="/plataforma-clases-online/home/disponibilidad_create" class="btn btn-primary btn-lg px-5 py-3" style="background: linear-gradient(135deg, #007bff, #0056b3); border: none; border-radius: 30px; font-size: 1.1rem; box-shadow: 0 4px 15px rgba(0,123,255,0.3);">
                            🚀 Comenzar a Configurar Horarios
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tabla de horarios - aparece debajo del reloj si existen horarios -->
            <?php if (!empty($disponibilidades)): ?>
                <div class="row">
                    <div class="col-12">
                        <div class="availability-table">
                            <div class="availability-header d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="mb-1">📋 Mis Horarios Configurados</h3>
                                    <span class="badge" style="background: rgba(255,255,255,0.2);">Gestión de Disponibilidad</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Profesor</th>
                                                <th>Día</th>
                                                <th>Inicio</th>
                                                <th>Fin</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach((array)$disponibilidades as $disp): ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="profesor-avatar">
                                                                <?php
                                                                $nombres = explode(' ', htmlspecialchars($disp['first_name'] . ' ' . $disp['last_name']));
                                                                echo strtoupper(substr($nombres[0], 0, 1) . (isset($nombres[1]) ? substr($nombres[1], 0, 1) : ''));
                                                                ?>
                                                            </div>
                                                            <span><?php echo htmlspecialchars($disp['first_name'] . ' ' . $disp['last_name']); ?></span>
                                                        </div>
                                                    </td>
                                                    <td><span class="badge bg-light text-dark"><?php echo htmlspecialchars($disp['day']); ?></span></td>
                                                    <td><?php echo htmlspecialchars($disp['start_time']); ?></td>
                                                    <td><?php echo htmlspecialchars($disp['end_time']); ?></td>
                                                    <td>
                                                        <span class="estado-disponible">
                                                            <?php echo htmlspecialchars($disp['status']); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="/plataforma-clases-online/home/disponibilidad_edit?id=<?php echo $disp['availability_id']; ?>" class="btn btn-editar btn-sm me-1">Editar</a>
                                                        <a href="/plataforma-clases-online/home/disponibilidad_delete?id=<?php echo $disp['availability_id']; ?>" class="btn btn-eliminar btn-sm" onclick="return confirm('¿Eliminar este horario?');">Eliminar</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (!empty($showForm)): ?>
            <div class="row">
                <div class="col-12">
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3><?php echo isset($disponibilidad) ? '✏️ Editar Horario' : '➕ Crear Nuevo Horario'; ?></h3>
                            <span class="badge bg-primary">Configuración de Disponibilidad</span>
                        </div>
                        <div class="card-body">
                            <form method="post" action="<?php echo isset($disponibilidad) ? '/plataforma-clases-online/home/disponibilidad_update' : '/plataforma-clases-online/home/disponibilidad_store'; ?>">
                                <?php if (!empty($disponibilidad)): ?>
                                    <input type="hidden" name="availability_id" value="<?php echo htmlspecialchars($disponibilidad['availability_id']); ?>">
                                <?php endif; ?>
                                <div class="row g-3">
                                    <?php if ($_SESSION['role'] === 'administrador'): ?>
                                    <div class="col-md-6">
                                        <label class="form-label">Profesor</label>
                                        <select name="user_id" class="form-select" required>
                                            <option value="">Seleccionar profesor</option>
                                            <?php foreach($profesores as $p): ?>
                                                <option value="<?php echo $p['user_id']; ?>" <?php echo (!empty($disponibilidad) && $disponibilidad['user_id'] == $p['user_id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($p['first_name'].' '.$p['last_name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <?php endif; ?>
                                    <div class="col-md-3">
                                        <label class="form-label">Día de la Semana</label>
                                        <select name="week_day_id" class="form-select" required>
                                            <option value="">Seleccionar día</option>
                                            <?php foreach($dias as $d): ?>
                                                <option value="<?php echo $d['week_day_id']; ?>" <?php echo (!empty($disponibilidad) && $disponibilidad['week_day_id'] == $d['week_day_id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($d['day']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Hora de Inicio</label>
                                        <input type="time" name="start_time" class="form-control" required value="<?php echo htmlspecialchars($disponibilidad['start_time'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Hora de Fin</label>
                                        <input type="time" name="end_time" class="form-control" required value="<?php echo htmlspecialchars($disponibilidad['end_time'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Estado de Disponibilidad</label>
                                        <select name="reservation_status_id" class="form-select" required>
                                            <option value="">Seleccionar estado</option>
                                            <option value="1" <?php echo (!empty($disponibilidad) && $disponibilidad['reservation_status_id'] == 1) ? 'selected' : ''; ?>>✅ Disponible</option>
                                            <option value="5" <?php echo (!empty($disponibilidad) && $disponibilidad['reservation_status_id'] == 5) ? 'selected' : ''; ?>>❌ No Disponible</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <?php echo isset($disponibilidad) ? '💾 Actualizar Horario' : '➕ Crear Horario'; ?>
                                    </button>
                                    <a href="/plataforma-clases-online/home/disponibilidad" class="btn btn-outline-secondary ms-2">
                                        ❌ Cancelar
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
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