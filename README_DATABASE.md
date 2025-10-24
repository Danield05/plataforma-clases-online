# 🗄️ Guía de Configuración de Base de Datos - Plataforma de Clases Online

## 📋 Información General

Esta plataforma utiliza **MySQL/MariaDB** como sistema de base de datos. Los scripts proporcionados crean una estructura completa con IDs autoincrementales y todas las relaciones necesarias.

## 🔧 Configuración Actual del Proyecto

Según tu archivo `config/database.php`, la configuración actual es:

```php
$host = 'localhost';
$dbname = 'plataforma_clases';  // Nombre correcto de tu BD
$username = 'root';             // Usuario XAMPP
$password = '1234';             // Contraseña XAMPP
```

## 📄 Scripts Disponibles

### 1. `plataforma_clases.sql` - Base de Datos Completa
**Script principal para crear la base de datos desde cero** con toda la estructura y datos iniciales.

## 🚀 Cómo Usar los Scripts

### Instalación de Base de Datos

1. **Abrir phpMyAdmin** (http://localhost/phpmyadmin)
2. **Crear nueva base de datos:**
   ```sql
   CREATE DATABASE plataforma_clases CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
3. **Ejecutar el script completo:**
   ```sql
   SOURCE plataforma_clases.sql;
   ```

## 👤 Usuarios y Permisos

### Configuración por Defecto (XAMPP)
- **Usuario:** `root`
- **Contraseña:** `1234` (o vacía)
- **Host:** `localhost`

### Usuario Dedicado (Opcional - Más Seguro)
Si quieres crear un usuario específico para la aplicación:

```sql
-- Crear usuario
CREATE USER 'app_user'@'localhost' IDENTIFIED BY 'tu_password_segura_aqui';

-- Dar permisos
GRANT ALL PRIVILEGES ON plataforma_clases.* TO 'app_user'@'localhost';

-- Aplicar cambios
FLUSH PRIVILEGES;
```

Luego actualiza tu `config/config.php`:
```php
$username = 'app_user';
$password = 'tu_password_segura_aqui';
```

## 📊 Estructura de Tablas Creadas

### Tablas Principales
- `Usuarios` - Usuarios del sistema
- `Roles` - Roles (administrador, profesor, estudiante)
- `Estados_Usuario` - Estados de usuario
- `Reservas` - Reservas de clases
- `Estados_Reserva` - Estados de reserva
- `Disponibilidad_Profesores` - Horarios disponibles
- `Estados_Disponibilidad` - Estados de disponibilidad
- `Materias` - Materias disponibles
- `Pagos` - Sistema de pagos
- `Estados_Pago` - Estados de pago
- `Reviews` - Sistema de reseñas

### Estados Importantes

#### Estados de Reserva
| ID | Estado |
|----|--------|
| 1 | Pendiente |
| 2 | Confirmada |
| 3 | Cancelada |
| 4 | Completada |

#### Estados de Disponibilidad
| ID | Estado |
|----|--------|
| 1 | Disponible |
| 2 | No Disponible |

## 🔍 Verificación de Instalación

Después de ejecutar los scripts, verifica que todo esté correcto:

```sql
-- Ver tablas creadas
SHOW TABLES;

-- Ver estados de reserva
SELECT * FROM Estados_Reserva;

-- Ver estados de disponibilidad
SELECT * FROM Estados_Disponibilidad;

-- Ver estructura de tabla clave
DESCRIBE Disponibilidad_Profesores;
```

## ⚠️ Notas Importantes

1. **IDs Autoincrementales:** Todas las tablas principales tienen IDs que se generan automáticamente.

2. **Foreign Keys:** Todas las relaciones están correctamente definidas.

3. **Charset:** Se usa `utf8mb4` para soporte completo de caracteres Unicode.

4. **Índices:** Se incluyen índices optimizados para mejor performance.

5. **Datos Iniciales:** Los scripts incluyen datos básicos para empezar a usar el sistema.

## 🐛 Solución de Problemas

### Error: "Table already exists"
- Usa el script de migración en lugar del de schema
- O elimina la base de datos y vuelve a crearla

### Error: "Access denied for user"
- Verifica las credenciales en `config/config.php`
- Asegúrate de que XAMPP esté ejecutándose
- Revisa permisos del usuario en phpMyAdmin

### Error: "Unknown column"
- Ejecuta el script de migración para agregar columnas faltantes
- Verifica que estés en la base de datos correcta

## 📞 Soporte

Si tienes problemas con la configuración de la base de datos:

1. Verifica que XAMPP/MySQL esté ejecutándose
2. Revisa las credenciales en `config/database.php`
3. Ejecuta los scripts en phpMyAdmin (no en VS Code)
4. Verifica los logs de error de MySQL

---

**¡La base de datos estará lista para usar la plataforma de clases online!** 🎓