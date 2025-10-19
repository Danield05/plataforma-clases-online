<?php
// Sistema básico de migraciones para la plataforma de clases online
require_once 'config/database.php';

echo "🚀 Ejecutando migraciones de base de datos\n";
echo "==========================================\n\n";

try {
    // Migración 1: Agregar estado "No Disponible" si no existe
    echo "1. Verificando estado 'No Disponible'...\n";
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM estados_reserva WHERE reservation_status_id = 5");
    $exists = $stmt->fetch()['count'] > 0;

    if (!$exists) {
        $pdo->exec("INSERT INTO estados_reserva (reservation_status_id, status) VALUES (5, 'No Disponible')");
        echo "✅ Estado 'No Disponible' agregado\n";
    } else {
        echo "✅ Estado 'No Disponible' ya existe\n";
    }

    // Migración 2: Verificar que todos los estados estén en mayúscula
    echo "\n2. Verificando formato de estados...\n";
    $updates = [
        1 => 'Disponible',
        2 => 'Reservado',
        3 => 'Cancelado',
        4 => 'Completado',
        5 => 'No Disponible'
    ];

    foreach ($updates as $id => $status) {
        $pdo->exec("UPDATE estados_reserva SET status = '$status' WHERE reservation_status_id = $id");
    }
    echo "✅ Estados actualizados a formato correcto\n";

    // Migración 3: Verificar días de la semana
    echo "\n3. Verificando días de la semana...\n";
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM dias_semana");
    $count = $stmt->fetch()['count'];

    if ($count < 7) {
        $pdo->exec("INSERT IGNORE INTO dias_semana (week_day_id, day) VALUES
            (1, 'Lunes'),
            (2, 'Martes'),
            (3, 'Miércoles'),
            (4, 'Jueves'),
            (5, 'Viernes'),
            (6, 'Sábado'),
            (7, 'Domingo')");
        echo "✅ Días de la semana completados\n";
    } else {
        echo "✅ Días de la semana completos\n";
    }

    // Migración 4: Verificar integridad referencial
    echo "\n4. Verificando integridad referencial...\n";
    $stmt = $pdo->query("
        SELECT d.availability_id, d.reservation_status_id
        FROM disponibilidad_profesores d
        LEFT JOIN estados_reserva e ON d.reservation_status_id = e.reservation_status_id
        WHERE e.reservation_status_id IS NULL
    ");

    $orphaned = $stmt->fetchAll();
    if (!empty($orphaned)) {
        echo "⚠️  Encontrados " . count($orphaned) . " registros huérfanos. Corrigiendo...\n";
        foreach ($orphaned as $record) {
            // Asignar estado por defecto
            $pdo->exec("UPDATE disponibilidad_profesores SET reservation_status_id = 1 WHERE availability_id = " . $record['availability_id']);
        }
        echo "✅ Registros huérfanos corregidos\n";
    } else {
        echo "✅ Integridad referencial correcta\n";
    }

    echo "\n🎉 Todas las migraciones completadas exitosamente!\n";
    echo "================================================\n";

} catch (PDOException $e) {
    echo "❌ Error en migraciones: " . $e->getMessage() . "\n";
    exit(1);
}
?>