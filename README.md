# 💎 Plataforma de Clases Online

Una plataforma web completa para la gestión de clases en línea, desarrollada con **PHP**, **MySQL** y arquitectura **MVC**.

## 🚀 Características

- **Sistema de Roles**: Administrador, Profesor y Estudiante con permisos específicos
- **Gestión de Usuarios**: Registro, autenticación y perfiles personalizados
- **Dashboard Personalizado**: Interfaces adaptadas según el rol del usuario
- **Sistema de Reservas**: Calendarios interactivos, disponibilidad horaria y reservas en tiempo real
- **Gestión de Clases**: Reservas, disponibilidad y pagos
- **Sistema Seguro**: Protección de rutas y validación de permisos
- **Sistema de Migraciones**: Mantenimiento automático de la base de datos

## 🛠️ Tecnologías Utilizadas

- **Backend**: PHP 7.4+ con arquitectura MVC
- **Base de Datos**: MySQL 5.7+ con sistema de migraciones
- **Servidor Web**: Apache (XAMPP recomendado)
- **Frontend**: HTML5, CSS3, JavaScript
- **Patrón de Diseño**: Modelo-Vista-Controlador (MVC)

## 📋 Requisitos del Sistema

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Apache con mod_rewrite habilitado
- XAMPP (recomendado para desarrollo local)

## 🚀 Instalación y Configuración

### 1. Clonar el Repositorio

```bash
git clone https://github.com/tu-usuario/plataforma-clases-online.git
cd plataforma-clases-online
```

### 2. Instalación Automática (Recomendado)

Ejecuta el script de instalación automática:

```bash
php setup.php
```

Este script verificará:
- ✅ Versión de PHP y extensiones requeridas
- ✅ Configuración de base de datos
- ✅ Estructura de tablas
- ✅ Permisos de archivos

#### Nota sobre PHP en Windows

Si al ejecutar `php setup.php` obtienes el error "php: The term 'php' is not recognized", significa que PHP no está en la variable PATH. Para solucionarlo:

- **Opción 1 (Recomendada)**: Agregar PHP al PATH del sistema:
  1. Busca "Variables de entorno" en el menú Inicio.
  2. Haz clic en "Variables de entorno".
  3. En "Variables del sistema", selecciona "Path" y haz clic en "Editar".
  4. Agrega una nueva entrada: `C:\xampp\php`
  5. Reinicia la terminal.

- **Opción 2**: Usar la ruta completa:
  ```bash
  C:\xampp\php\php.exe setup.php
  ```

### 3. Instalación Manual

#### Base de Datos
1. Crear una base de datos MySQL llamada `plataforma_clases`
2. Importar el archivo `plataforma_clases.sql`:
   ```bash
   mysql -u root -p plataforma_clases < plataforma_clases.sql
   ```

#### Archivo de Configuración
1. Copiar el archivo de ejemplo:
   ```bash
   cp config/database.example.php config/database.php
   ```

2. Editar `config/database.php` con tus credenciales:
   ```php
   $host = 'localhost';
   $dbname = 'plataforma_clases';
   $username = 'tu_usuario_mysql';
   $password = 'tu_password_mysql';
   ```

### 4. Sistema de Migraciones

Para mantener la base de datos actualizada con nuevas funcionalidades:

#### Ejecutar Migraciones
```bash
# Opción 1: Usando PHP del sistema (si está en PATH)
php migrations.php

# Opción 2: Usando PHP de XAMPP
C:\xampp\php\php.exe migrations.php

# Opción 3: Desde navegador
# http://localhost/plataforma-clases-online/migrations.php
```

#### Qué hacen las migraciones:
- ✅ Verifican y corrigen estados de reserva
- ✅ Aseguran que los días de la semana estén completos
- ✅ Validan integridad referencial
- ✅ Corrigen formato de datos (mayúsculas, etc.)
- ✅ Se pueden ejecutar múltiples veces sin problemas

#### Comandos Útiles para Desarrollo:
```bash
# Ver tablas de la base de datos
mysql -u root -p plataforma_clases -e "SHOW TABLES;"

# Ver contenido de una tabla
mysql -u root -p plataforma_clases -e "SELECT * FROM estados_reserva;"

# Backup de la base de datos
mysqldump -u root -p plataforma_clases > backup.sql

# Ejecutar consultas específicas
mysql -u root -p plataforma_clases -e "INSERT INTO estados_reserva (reservation_status_id, status) VALUES (6, 'Nuevo Estado');"
```

### 3. Configurar el Servidor Web

#### Con XAMPP:
1. Colocar el proyecto en `C:\xampp\htdocs\plataforma-clases-online`
2. Iniciar Apache y MySQL desde el panel de control de XAMPP
3. Acceder a: `http://localhost/plataforma-clases-online`

#### Con Apache Manual:
Asegurarse de que `mod_rewrite` esté habilitado y configurar el DocumentRoot apuntando a la carpeta del proyecto.


## 📁 Estructura del Proyecto

```
plataforma-clases-online/
├── 📁 config/                 # Configuración de BD y seguridad
│   ├── database.php           # Credenciales de BD (no versionado)
│   ├── database.example.php   # Plantilla de configuración
│   └── .htaccess              # Protección de archivos sensibles
├── 📁 controllers/            # Controladores MVC
│   ├── AuthController.php     # Autenticación y sesiones
│   ├── HomeController.php     # Dashboard y navegación
│   └── RegisterController.php # Registro de usuarios
├── 📁 models/                 # Modelos de datos
│   ├── UserModel.php          # Gestión de usuarios
│   ├── ReservaModel.php       # Sistema de reservas ⭐
│   ├── DisponibilidadModel.php # Gestión de horarios
│   └── ...                    # Otros modelos específicos
├── 📁 views/                  # Vistas y plantillas
│   ├── views_profesor/        # Dashboard profesor con calendario
│   ├── views_estudiante/      # Dashboard estudiante con reservas
│   ├── explorar_profesores.php # Búsqueda y reserva de clases ⭐
│   ├── disponibilidad.php     # Configuración de horarios
│   └── nav.php               # Navegación principal
├── 📁 public/                # Archivos estáticos
│   ├── css/                   # Hojas de estilo
│   └── js/                    # Scripts JavaScript
├── 📄 plataforma_clases.sql   # Script de base de datos
├── 📄 setup.php              # Instalación automática
├── 📄 migrations.php         # Migraciones de BD ⭐
├── 📄 requirements.txt       # Requerimientos del sistema
├── 📄 .htaccess              # Reglas de reescritura URL
├── 📄 .gitignore            # Archivos ignorados por Git
├── 📄 index.php             # Punto de entrada de la aplicación
└── 📄 README.md             # Este archivo
```

## 🔐 Sistema de Roles y Permisos

### 👑 Administrador
- ✅ Acceso completo a todas las funcionalidades
- ✅ Gestión de usuarios, profesores y estudiantes
- ✅ Visualización de estadísticas y reportes
- ✅ Configuración del sistema

### 👨‍🏫 Profesor
- ✅ **Sistema de Disponibilidad**: Configurar horarios semanales (Disponible/No Disponible)
- ✅ **Calendario de Clases**: Ver reservas de estudiantes con indicadores visuales
- ✅ **Gestión de Reservas**: Aceptar, rechazar o cancelar reservas
- ✅ **Perfil Académico**: Actualizar información personal y académica
- ✅ **Estadísticas**: Ver ingresos, estudiantes activos, calificaciones

### 🎓 Estudiante
- ✅ **Explorar Profesores**: Buscar profesores con filtros avanzados
- ✅ **Sistema de Reservas**: Ver disponibilidad y reservar clases en tiempo real
- ✅ **Calendario Personal**: Ver todas las reservas programadas
- ✅ **Gestión de Pagos**: Historial de pagos y facturas
- ✅ **Perfil Personal**: Gestionar información y preferencias

## 📅 Sistema de Reservas ⭐

### Funcionalidades Principales:
- **📆 Calendarios Interactivos**: Tanto profesores como estudiantes tienen calendarios visuales
- **⏰ Gestión de Disponibilidad**: Profesores configuran horarios semanales
- **🔍 Búsqueda Inteligente**: Estudiantes encuentran profesores disponibles
- **⚡ Reservas en Tiempo Real**: Verificación automática de conflictos
- **📊 Estados de Reserva**: Disponible → Reservado → Completado/Cancelado

### Flujo de Reserva:
1. **Profesor** configura su disponibilidad horaria
2. **Estudiante** explora profesores y ve horarios disponibles
3. **Estudiante** selecciona fecha/hora y confirma reserva
4. **Sistema** verifica disponibilidad y crea reserva
5. **Profesor** ve la reserva en su calendario
6. **Clase** se completa y cambia de estado automáticamente

## 🛡️ Seguridad

- **Protección de Rutas**: Verificación de permisos en cada controlador
- **Validación de Datos**: Sanitización y validación de inputs
- **Sesiones Seguras**: Manejo seguro de sesiones de usuario
- **Archivos Protegidos**: Configuración de BD inaccesible desde web

## 🛠️ Desarrollo y Mantenimiento

### Sistema de Migraciones
```bash
# Ejecutar migraciones para actualizar la base de datos
php migrations.php
# o
C:\xampp\php\php.exe migrations.php
```

### Convenciones de Código
- ✅ PSR-4 para autoloading de clases
- ✅ Nombres de archivos en PascalCase para clases
- ✅ Nombres de tablas en snake_case
- ✅ Comentarios descriptivos en español
- ✅ Validación de datos en todos los formularios

### Agregar Nuevas Funcionalidades
1. **Crear el modelo** en `models/` (extender de clase base si aplica)
2. **Crear el controlador** en `controllers/` (verificar permisos)
3. **Crear las vistas** en `views/` (responsive y accesibles)
4. **Actualizar migraciones** si se modifica la BD
5. **Probar la funcionalidad** completamente

### Comandos de Desarrollo Útiles
```bash
# Verificar estado del proyecto
php setup.php

# Actualizar base de datos
php migrations.php

# Ver logs de errores (si los habilitas)
tail -f /xampp/apache/logs/error.log

# Backup de base de datos
mysqldump -u root -p plataforma_clases > backup_$(date +%Y%m%d).sql
```

## 🎯 Estado del Proyecto

### ✅ Funcionalidades Completadas
- [x] Sistema de autenticación y roles
- [x] Dashboards personalizados por rol
- [x] Sistema completo de reservas con calendarios
- [x] Gestión de disponibilidad horaria
- [x] Sistema de migraciones automático
- [x] Interfaz responsive y moderna

### 🚀 Próximas Mejoras (Opcionales)
- [ ] Notificaciones por email
- [ ] Sistema de pagos integrado
- [ ] Chat en tiempo real
- [ ] API REST para móviles
- [ ] Reportes avanzados con gráficos

## 📞 Soporte

Si encuentras algún problema:
1. Ejecuta `php setup.php` para verificar la instalación
2. Ejecuta `php migrations.php` para actualizar la BD
3. Revisa los logs de Apache en `xampp/apache/logs/`
4. Verifica la configuración en `config/database.php`

---
**¡Tu plataforma de clases online está lista para usar! 🎓✨**
