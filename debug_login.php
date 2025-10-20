<?php
// Script de debug avanzado para verificar problemas de login
echo "=== DEBUG LOGIN AVANZADO - VERIFICACIÓN DETALLADA ===\n\n";

// Función para probar autenticación directamente
function testAuthentication($email, $password) {
    echo "🔐 PRUEBA DE AUTENTICACIÓN DIRECTA:\n";
    echo "==================================\n";
    echo "Email: $email\n";
    echo "Password de prueba: $password\n";
    echo "Longitud del password: " . strlen($password) . "\n\n";

    try {
        require_once 'config/database.php';

        // Obtener usuario de la base de datos
        $stmt = $pdo->prepare("SELECT user_id, first_name, last_name, email, password, role_id FROM Usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo "✅ Usuario encontrado en BD:\n";
            echo "   ID: " . $user['user_id'] . "\n";
            echo "   Nombre: " . $user['first_name'] . ' ' . $user['last_name'] . "\n";
            echo "   Email: " . $user['email'] . "\n";
            echo "   Role ID: " . $user['role_id'] . "\n";
            echo "   Password hash: " . $user['password'] . "\n";
            echo "   Longitud del hash: " . strlen($user['password']) . "\n\n";

            // Probar verificación de contraseña
            echo "🔍 VERIFICACIÓN DE CONTRASEÑA:\n";
            echo "=============================\n";

            $hash = $user['password'];
            $password_verify_result = password_verify($password, $hash);

            echo "password_verify('$password', '$hash') = " . ($password_verify_result ? 'TRUE' : 'FALSE') . "\n";

            // Información adicional del hash
            echo "\n📋 INFORMACIÓN DEL HASH:\n";
            echo "=======================\n";
            echo "Algoritmo: " . password_get_info($hash)['algoName'] . "\n";

            // Probar diferentes variaciones
            echo "\n🧪 PRUEBAS ADICIONALES:\n";
            echo "======================\n";

            $variations = [
                'admin123',
                'admin123 ',
                ' admin123',
                'admin123' . "\n",
                'admin123' . "\r\n"
            ];

            foreach ($variations as $i => $variation) {
                $result = password_verify($variation, $hash);
                echo "Prueba " . ($i + 1) . " ('$variation'): " . ($result ? '✅ ÉXITO' : '❌ FALLÓ') . "\n";
            }

        } else {
            echo "❌ Usuario NO encontrado en la base de datos\n";

            // Listar todos los usuarios para debug
            echo "\n📋 LISTA DE TODOS LOS USUARIOS:\n";
            echo "==============================\n";
            $stmt = $pdo->query("SELECT user_id, first_name, last_name, email FROM Usuarios");
            $allUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($allUsers) {
                foreach ($allUsers as $u) {
                    echo "   ID: " . $u['user_id'] . " - " . $u['first_name'] . ' ' . $u['last_name'] . " - " . $u['email'] . "\n";
                }
            } else {
                echo "   No hay usuarios en la base de datos\n";
            }
        }

    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
}

// Buscar archivos de log recientes
echo "=== BÚSQUEDA DE ARCHIVOS DE LOG ===\n\n";

$logFiles = [
    'C:/xampp/php/logs/php_error_log',
    'C:/xampp/apache/logs/error.log',
    'C:/xampp/apache/logs/access.log',
    dirname(__FILE__) . '/debug.log'
];

foreach ($logFiles as $logFile) {
    if (file_exists($logFile)) {
        echo "✅ ENCONTRADO: $logFile\n";
        $size = filesize($logFile);
        echo "   Tamaño: $size bytes\n";

        if (strpos($logFile, 'error.log') !== false) {
            $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $debugLines = array_filter($lines, function($line) {
                return strpos($line, 'LOGIN DEBUG') !== false;
            });

            if (!empty($debugLines)) {
                echo "   LÍNEAS DE DEBUG RECIENTES:\n";
                foreach (array_slice($debugLines, -10) as $line) {
                    echo "   🔍 $line\n";
                }
            }
        }
        echo "\n";
    } else {
        echo "❌ NO ENCONTRADO: $logFile\n\n";
    }
}

// Ejecutar prueba de autenticación
echo "\n";
testAuthentication('admin@plataforma.com', 'admin123');

echo "\n" . str_repeat("=", 60) . "\n";
echo "INSTRUCCIONES PARA CONTINUAR EL DEBUG:\n";
echo str_repeat("=", 60) . "\n";
echo "1. Si la verificación directa funciona arriba ✅:\n";
echo "   - El problema está en el controlador o formulario\n";
echo "   - Revisa el código del AuthController\n\n";

echo "2. Si la verificación directa falla ❌:\n";
echo "   - El problema está en la base de datos\n";
echo "   - Puede ser problema de encoding o datos corruptos\n\n";

echo "3. Para más información, agrega estas líneas al AuthController:\n";
echo "   error_log('LOGIN DEBUG - Hash de BD: ' . \$user['password']);\n";
echo "   error_log('LOGIN DEBUG - password_verify result: ' . (password_verify(\$password, \$user['password']) ? 'true' : 'false'));\n\n";

echo "4. Ejecuta este script después de intentar login para ver logs actualizados\n";

// Función para corregir el hash de contraseña corrupto
function fixAdminPassword() {
    echo "\n🔧 CORRECCIÓN DE CONTRASEÑA ADMIN:\n";
    echo "=================================\n";

    try {
        // Conectar a la base de datos dentro de la función
        $host = 'localhost';
        $dbname = 'plataforma_clases';
        $username = 'root';
        $password = '1234';

        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        $email = 'admin@plataforma.com';
        $correctPassword = 'admin123';
        $newHash = password_hash($correctPassword, PASSWORD_DEFAULT);

        echo "Contraseña correcta: $correctPassword\n";
        echo "Nuevo hash generado: $newHash\n";
        echo "Longitud del nuevo hash: " . strlen($newHash) . "\n\n";

        // Actualizar la contraseña en la base de datos
        $stmt = $pdo->prepare("UPDATE Usuarios SET password = ? WHERE email = ?");
        $result = $stmt->execute([$newHash, $email]);

        if ($result) {
            echo "✅ Contraseña de admin actualizada exitosamente\n";

            // Verificar que la actualización funcionó
            $stmt = $pdo->prepare("SELECT password FROM Usuarios WHERE email = ?");
            $stmt->execute([$email]);
            $updatedUser = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($updatedUser && password_verify($correctPassword, $updatedUser['password'])) {
                echo "✅ Verificación exitosa: la nueva contraseña funciona correctamente\n";
                echo "🔐 Puedes intentar hacer login ahora con:\n";
                echo "   Email: admin@plataforma.com\n";
                echo "   Password: admin123\n";
            } else {
                echo "❌ Error: la verificación sigue fallando después de la actualización\n";
            }
        } else {
            echo "❌ Error al actualizar la contraseña en la base de datos\n";
        }

    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
}

// Ejecutar corrección automática
fixAdminPassword();

// Función para verificar y corregir otros usuarios
function fixOtherUsers() {
    echo "\n🔧 VERIFICACIÓN DE OTROS USUARIOS:\n";
    echo "================================\n";

    $users = [
        ['email' => 'maria.profesor@plataforma.com', 'password' => 'prof123', 'role' => 'profesor'],
        ['email' => 'juan.estudiante@plataforma.com', 'password' => 'estu123', 'role' => 'estudiante']
    ];

    try {
        // Conectar a la base de datos
        $host = 'localhost';
        $dbname = 'plataforma_clases';
        $username = 'root';
        $password = '1234';

        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        foreach ($users as $userData) {
            echo "\n📋 Verificando: " . $userData['email'] . "\n";
            echo "Contraseña esperada: " . $userData['password'] . "\n";

            $stmt = $pdo->prepare("SELECT user_id, first_name, last_name, email, password FROM Usuarios WHERE email = ?");
            $stmt->execute([$userData['email']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                echo "✅ Usuario encontrado\n";
                echo "   Hash actual: " . $user['password'] . "\n";

                // Verificar si la contraseña actual funciona
                if (password_verify($userData['password'], $user['password'])) {
                    echo "✅ Contraseña ya funciona correctamente\n";
                } else {
                    echo "❌ Contraseña no funciona - necesita corrección\n";

                    // Generar nuevo hash y actualizar
                    $newHash = password_hash($userData['password'], PASSWORD_DEFAULT);
                    $updateStmt = $pdo->prepare("UPDATE Usuarios SET password = ? WHERE email = ?");
                    $updateResult = $updateStmt->execute([$newHash, $userData['email']]);

                    if ($updateResult) {
                        echo "✅ Contraseña actualizada exitosamente\n";
                        echo "✅ Nueva verificación: " . (password_verify($userData['password'], $newHash) ? 'FUNCIONA' : 'FALLA') . "\n";
                    }
                }
            } else {
                echo "❌ Usuario NO encontrado en la base de datos\n";
            }
        }

        echo "\n🎉 VERIFICACIÓN COMPLETA DE USUARIOS DE PRUEBA:\n";
        echo "=============================================\n";
        echo "✅ Admin: admin@plataforma.com / admin123\n";
        echo "✅ Profesor: maria.profesor@plataforma.com / prof123\n";
        echo "✅ Estudiante: juan.estudiante@plataforma.com / estu123\n";
        echo "\nTodos los usuarios deberían funcionar ahora correctamente.\n";

    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
}

// Ejecutar verificación y corrección de otros usuarios
fixOtherUsers();

// Función para verificar y corregir Estados_Disponibilidad
function fixEstadosDisponibilidad() {
    echo "\n🔧 VERIFICACIÓN DE ESTADOS_DISPONIBILIDAD:\n";
    echo "========================================\n";

    try {
        // Conectar a la base de datos
        $host = 'localhost';
        $dbname = 'plataforma_clases';
        $username = 'root';
        $password = '1234';

        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        // Verificar estados actuales
        $stmt = $pdo->query("SELECT * FROM Estados_Disponibilidad ORDER BY availability_status_id");
        $estados = $stmt->fetchAll();

        echo "Estados actuales en la tabla:\n";
        if ($estados) {
            foreach ($estados as $estado) {
                echo "   ID: " . $estado['availability_status_id'] . " - Status: '" . $estado['status'] . "'\n";
            }
        } else {
            echo "   ❌ No hay estados en la tabla\n";

            // Crear estados por defecto
            echo "\n📝 Creando estados por defecto...\n";

            $defaultEstados = [
                ['availability_status_id' => 1, 'status' => 'Disponible'],
                ['availability_status_id' => 2, 'status' => 'No Disponible'],
                ['availability_status_id' => 3, 'status' => 'Ocupado'],
                ['availability_status_id' => 4, 'status' => 'En Clase'],
                ['availability_status_id' => 5, 'status' => 'Pausado']
            ];

            foreach ($defaultEstados as $estado) {
                $insertStmt = $pdo->prepare("INSERT IGNORE INTO Estados_Disponibilidad (availability_status_id, status) VALUES (?, ?)");
                $result = $insertStmt->execute([$estado['availability_status_id'], $estado['status']]);
                if ($result) {
                    echo "   ✅ Creado: " . $estado['status'] . "\n";
                }
            }
        }

        // Verificar disponibilidades sin estado correcto
        echo "\n🔍 Verificando disponibilidades con problemas...\n";

        $stmt = $pdo->query("SELECT d.availability_id, d.availability_status_id, u.first_name, u.last_name, ds.day FROM Disponibilidad_Profesores d JOIN Usuarios u ON d.user_id = u.user_id JOIN Dias_Semana ds ON d.week_day_id = ds.week_day_id WHERE d.availability_status_id NOT IN (SELECT availability_status_id FROM Estados_Disponibilidad)");
        $problematicos = $stmt->fetchAll();

        if ($problematicos) {
            echo "   ⚠️ Disponibilidades con estado inválido encontradas:\n";
            foreach ($problematicos as $prob) {
                echo "      - " . $prob['first_name'] . " " . $prob['last_name'] . " (" . $prob['day'] . ") - Estado ID: " . $prob['availability_status_id'] . "\n";

                // Corregir el estado a "Disponible" (ID 1)
                $updateStmt = $pdo->prepare("UPDATE Disponibilidad_Profesores SET availability_status_id = 1 WHERE availability_id = ?");
                $updateResult = $updateStmt->execute([$prob['availability_id']]);
                if ($updateResult) {
                    echo "         ✅ Corregido a 'Disponible'\n";
                }
            }
        } else {
            echo "   ✅ Todas las disponibilidades tienen estados válidos\n";
        }

        // Mostrar estadísticas finales
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM Estados_Disponibilidad");
        $totalEstados = $stmt->fetch()['total'];

        $stmt = $pdo->query("SELECT ed.status, COUNT(d.availability_id) as count FROM Estados_Disponibilidad ed LEFT JOIN Disponibilidad_Profesores d ON ed.availability_status_id = d.availability_status_id GROUP BY ed.availability_status_id, ed.status");
        $estadisticas = $stmt->fetchAll();

        echo "\n📊 Estadísticas finales:\n";
        echo "   Total de estados: $totalEstados\n";
        foreach ($estadisticas as $stat) {
            echo "   " . $stat['status'] . ": " . $stat['count'] . " disponibilidades\n";
        }

    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
}

// Ejecutar verificación y corrección de estados
fixEstadosDisponibilidad();

// Función para verificar estructura de tabla Pagos y corregir consultas
function fixPagosModel() {
    echo "\n🔧 VERIFICACIÓN DE TABLA PAGOS:\n";
    echo "==============================\n";

    try {
        // Conectar a la base de datos
        $host = 'localhost';
        $dbname = 'plataforma_clases';
        $username = 'root';
        $password = '1234';

        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        // Verificar estructura de tabla Pagos
        echo "📋 Verificando columnas de tabla Pagos...\n";
        $stmt = $pdo->query("DESCRIBE Pagos");
        $columns = $stmt->fetchAll();

        echo "Columnas encontradas:\n";
        foreach ($columns as $column) {
            echo "   - " . $column['Field'] . " (" . $column['Type'] . ")\n";
        }

        // Verificar si existe tabla Estados_Pago
        echo "\n📋 Verificando tabla Estados_Pago...\n";
        try {
            $stmt = $pdo->query("SELECT * FROM Estados_Pago");
            $estadosPago = $stmt->fetchAll();
            echo "✅ Tabla Estados_Pago existe\n";
            echo "Estados encontrados:\n";
            foreach ($estadosPago as $estado) {
                echo "   ID: " . $estado['payment_status_id'] . " - Status: '" . $estado['status'] . "'\n";
            }
        } catch (Exception $e) {
            echo "❌ Tabla Estados_Pago no existe o tiene problemas\n";
            echo "Error: " . $e->getMessage() . "\n";

            // Crear tabla Estados_Pago si no existe
            echo "\n📝 Creando tabla Estados_Pago...\n";
            $pdo->exec("CREATE TABLE IF NOT EXISTS Estados_Pago (
                payment_status_id INT PRIMARY KEY,
                status VARCHAR(50) NOT NULL
            )");

            // Insertar estados por defecto
            $defaultEstados = [
                ['payment_status_id' => 1, 'status' => 'Pendiente'],
                ['payment_status_id' => 2, 'status' => 'Procesando'],
                ['payment_status_id' => 3, 'status' => 'Pagado'],
                ['payment_status_id' => 4, 'status' => 'Cancelado'],
                ['payment_status_id' => 5, 'status' => 'Reembolsado']
            ];

            foreach ($defaultEstados as $estado) {
                $insertStmt = $pdo->prepare("INSERT IGNORE INTO Estados_Pago (payment_status_id, status) VALUES (?, ?)");
                $result = $insertStmt->execute([$estado['payment_status_id'], $estado['status']]);
                if ($result) {
                    echo "   ✅ Creado: " . $estado['status'] . "\n";
                }
            }
        }

        // Verificar si hay datos en tabla Pagos
        echo "\n📋 Verificando datos en tabla Pagos...\n";
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM Pagos");
        $totalPagos = $stmt->fetch()['total'];
        echo "Total de pagos registrados: $totalPagos\n";

        if ($totalPagos > 0) {
            $stmt = $pdo->query("SELECT * FROM Pagos LIMIT 3");
            $primerosPagos = $stmt->fetchAll();
            echo "Ejemplos de pagos:\n";
            foreach ($primerosPagos as $pago) {
                echo "   ID: " . $pago['payment_id'] . ", Amount: " . $pago['amount'] . ", Status ID: " . $pago['payment_status_id'] . "\n";
            }
        }

        // Verificar si existe tabla Reservas y su estructura
        echo "\n📋 Verificando tabla Reservas...\n";
        try {
            $stmt = $pdo->query("DESCRIBE Reservas");
            $reservaColumns = $stmt->fetchAll();
            echo "✅ Tabla Reservas existe\n";
            echo "Columnas encontradas:\n";
            foreach ($reservaColumns as $column) {
                echo "   - " . $column['Field'] . " (" . $column['Type'] . ")\n";
            }

            // Verificar si Reservas tiene reservation_id
            $hasReservationId = false;
            foreach ($reservaColumns as $column) {
                if ($column['Field'] === 'reservation_id') {
                    $hasReservationId = true;
                    break;
                }
            }

            if (!$hasReservationId) {
                echo "❌ Tabla Reservas no tiene columna 'reservation_id'\n";
                echo "💡 Sugerencia: revisar la estructura de la tabla Reservas\n";
            } else {
                echo "✅ Tabla Reservas tiene columna 'reservation_id'\n";
            }

        } catch (Exception $e) {
            echo "❌ Tabla Reservas no existe o tiene problemas\n";
            echo "Error: " . $e->getMessage() . "\n";
        }

    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
}

// Ejecutar verificación de tabla Pagos
fixPagosModel();

// Función para verificar y corregir estados de pago
function fixEstadosPago() {
    echo "\n🔧 VERIFICACIÓN Y CORRECCIÓN DE ESTADOS DE PAGO:\n";
    echo "===============================================\n";

    try {
        // Conectar a la base de datos
        $host = 'localhost';
        $dbname = 'plataforma_clases';
        $username = 'root';
        $password = '1234';

        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        // Verificar pagos actuales y sus estados
        echo "📋 Verificando pagos y sus estados actuales...\n";

        $stmt = $pdo->query("
            SELECT p.payment_id, p.amount, p.payment_status_id, ep.status as estado_actual,
                   u.first_name, u.last_name
            FROM Pagos p
            JOIN Estados_Pago ep ON p.payment_status_id = ep.payment_status_id
            JOIN Usuarios u ON p.user_id = u.user_id
            ORDER BY p.payment_id
        ");

        $pagos = $stmt->fetchAll();

        if ($pagos) {
            foreach ($pagos as $pago) {
                echo "   Pago ID {$pago['payment_id']}: \${$pago['amount']} - {$pago['first_name']} {$pago['last_name']} - Estado: {$pago['estado_actual']} (ID: {$pago['payment_status_id']})\n";

                // Si el pago tiene estado "Completado" (ID 2), sugerir cambiar a "Pagado" (ID 3) para cálculos
                if ($pago['payment_status_id'] == 2) {
                    echo "      💡 Este pago completado cuenta para el total recaudado\n";
                }
            }

            // Estadísticas actuales
            echo "\n📊 Estadísticas actuales de pagos:\n";

            $stmt = $pdo->query("
                SELECT
                    SUM(CASE WHEN payment_status_id = 1 THEN 1 ELSE 0 END) as pendientes,
                    SUM(CASE WHEN payment_status_id = 2 THEN 1 ELSE 0 END) as completados,
                    SUM(CASE WHEN payment_status_id = 3 THEN 1 ELSE 0 END) as pagados,
                    SUM(CASE WHEN payment_status_id IN (2, 3) THEN amount ELSE 0 END) as total_recaudado,
                    COUNT(*) as total_pagos
                FROM Pagos
            ");

            $stats = $stmt->fetch();

            echo "   Total pagos: {$stats['total_pagos']}\n";
            echo "   Pendientes: {$stats['pendientes']}\n";
            echo "   Completados: {$stats['completados']}\n";
            echo "   Pagados: {$stats['pagados']}\n";
            echo "   Total recaudado: \${$stats['total_recaudado']}\n";

            // Si hay pagos completados pero no pagados, ofrecer opción de corrección
            if ($stats['completados'] > 0 && $stats['pagados'] == 0) {
                echo "\n💡 Sugerencia: Los pagos 'Completados' (ID 2) deberían ser 'Pagados' (ID 3) para cálculos correctos\n";
                echo "   Esto aseguraría que aparezcan en 'Total Recaudado' y 'Pagos Completados'\n";
            }

        } else {
            echo "   No hay pagos registrados en la base de datos\n";
        }

    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
}

// Ejecutar verificación de estados de pago
fixEstadosPago();

// Función para verificar estructura de tabla Reviews
function fixReviewsModel() {
    echo "\n🔧 VERIFICACIÓN DE TABLA REVIEWS:\n";
    echo "===============================\n";

    try {
        // Conectar a la base de datos
        $host = 'localhost';
        $dbname = 'plataforma_clases';
        $username = 'root';
        $password = '1234';

        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        // Verificar si existe tabla Reviews
        echo "📋 Verificando tabla Reviews...\n";
        try {
            $stmt = $pdo->query("DESCRIBE Reviews");
            $columns = $stmt->fetchAll();
            echo "✅ Tabla Reviews existe\n";
            echo "Columnas encontradas:\n";
            foreach ($columns as $column) {
                echo "   - " . $column['Field'] . " (" . $column['Type'] . ")\n";
            }
        } catch (Exception $e) {
            echo "❌ Tabla Reviews no existe o tiene problemas\n";
            echo "Error: " . $e->getMessage() . "\n";

            // Crear tabla Reviews si no existe
            echo "\n📝 Creando tabla Reviews...\n";
            $pdo->exec("CREATE TABLE IF NOT EXISTS Reviews (
                review_id INT AUTO_INCREMENT PRIMARY KEY,
                reservation_id INT,
                profesor_user_id INT,
                estudiante_user_id INT,
                rating INT NOT NULL,
                comment TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (profesor_user_id) REFERENCES Usuarios(user_id),
                FOREIGN KEY (estudiante_user_id) REFERENCES Usuarios(user_id)
            )");

            echo "✅ Tabla Reviews creada exitosamente\n";
        }

        // Verificar datos existentes
        echo "\n📋 Verificando datos en tabla Reviews...\n";
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM Reviews");
        $totalReviews = $stmt->fetch()['total'];
        echo "Total de reviews registrados: $totalReviews\n";

        if ($totalReviews > 0) {
            $stmt = $pdo->query("SELECT * FROM Reviews LIMIT 3");
            $primerosReviews = $stmt->fetchAll();
            echo "Ejemplos de reviews:\n";
            foreach ($primerosReviews as $review) {
                echo "   ID: " . $review['review_id'] . ", Rating: " . $review['rating'] . ", Profesor ID: " . $review['profesor_user_id'] . ", Estudiante ID: " . $review['estudiante_user_id'] . "\n";
            }
        }

    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
}

// Ejecutar verificación de tabla Reviews
fixReviewsModel();

// Función para crear archivo de reviews corregido
function fixReviewsView() {
    echo "\n🔧 CREANDO ARCHIVO DE REVIEWS CORREGIDO:\n";
    echo "=====================================\n";

    $reviewsContent = '<?php
// Definir la página actual para el header
$currentPage = \'reviews\';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>⭐ Reviews - Plataforma de Clases Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/plataforma-clases-online/public/css/style.css?v=' . time() . '">
    <style>
        .reviews-header {
            background: linear-gradient(135deg, #ffd700, #ffb347);
            color: white;
            border-radius: 10px 10px 0 0;
            padding: 20px;
            margin-bottom: 0;
        }
        .reviews-table {
            background: white;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
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
    <header class="modern-header">
        <div class="header-content">
            <h1 class="header-title">⭐ Reviews</h1>
            ' . '<?php include __DIR__ . \'/nav.php\'; ?>' . '
        </div>
    </header>

    <main class="container my-5">
        <div class="row">
            <div class="col-12">
                <div class="reviews-table">
                    <div class="reviews-header d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1">⭐ Comentarios y Calificaciones</h3>
                            <span class="badge" style="background: rgba(255,255,255,0.2);">Sistema de Reviews</span>
                        </div>
                    </div>
                    <div class="card-body">
                        ' . '<?php if (empty($reviews)): ?>' . '
                            <div class="text-center py-5">
                                <div class="mb-4" style="font-size: 4rem; color: #6c757d;">⭐</div>
                                <h3>No hay reviews registradas</h3>
                                <p class="text-muted">Las reviews aparecerán aquí cuando se creen.</p>
                            </div>
                        ' . '<?php else: ?>' . '
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Profesor</th>
                                            <th>Estudiante</th>
                                            <th>Calificación</th>
                                            <th>Comentario</th>
                                            <th>Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ' . '<?php foreach($reviews as $review): ?>' . '
                                            <tr>
                                                <td>' . '<?php echo htmlspecialchars(($review[\'profesor_name\'] ?? \'\') . \' \' . ($review[\'profesor_last_name\'] ?? \'\')); ?>' . '</td>
                                                <td>' . '<?php echo htmlspecialchars(($review[\'estudiante_name\'] ?? \'\') . \' \' . ($review[\'estudiante_last_name\'] ?? \'\')); ?>' . '</td>
                                                <td>
                                                    <span class="badge bg-warning text-dark">
                                                        ' . '<?php echo $review[\'rating\'] ?? 0; ?>' . '/5 ⭐
                                                    </span>
                                                </td>
                                                <td>' . '<?php echo htmlspecialchars($review[\'comment\'] ?? \'Sin comentario\'); ?>' . '</td>
                                                <td>' . '<?php echo htmlspecialchars($review[\'created_at\'] ?? date(\'Y-m-d H:i:s\')); ?>' . '</td>
                                            </tr>
                                        ' . '<?php endforeach; ?>' . '
                                    </tbody>
                                </table>
                            </div>
                        ' . '<?php endif; ?>' . '
                    </div>
                </div>
            </div>
        </div>
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
                © ' . '<?php echo date(\'Y\'); ?>' . ' Plataforma Clases Online. Todos los derechos reservados.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/plataforma-clases-online/public/js/script.js"></script>
</body>
</html>';

    // Crear el archivo corregido
    $filePath = 'views/layouts/reviews.php';
    $result = file_put_contents($filePath, $reviewsContent);

    if ($result !== false) {
        echo "✅ Archivo de reviews corregido exitosamente\n";
        echo "   Ubicación: $filePath\n";
        echo "   Tamaño: " . strlen($reviewsContent) . " caracteres\n";
    } else {
        echo "❌ Error al crear el archivo de reviews\n";
    }
}

// Ejecutar creación del archivo de reviews
fixReviewsView();