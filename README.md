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
- **Sistema de Reportes Avanzado**: Reportes detallados con filtros, exportación y estadísticas ⭐
- **Exportación de Datos**: PDF, Excel, CSV y envío por email de reportes
- **Análisis de Rendimiento**: Estadísticas de profesores, estudiantes e ingresos
- **Sistema de Pagos**: Gestión completa de pagos con estados y métodos de pago ⭐
- **Sistema de Reseñas**: Calificaciones y comentarios de estudiantes a profesores ⭐
- **Gestión de Materias**: Exploración y filtrado de materias disponibles
- **Búsqueda Avanzada**: Filtros por precio, materia, profesor y disponibilidad

## 🛠️ Tecnologías Utilizadas

- **Backend**: PHP 7.4+ con arquitectura MVC
- **Base de Datos**: MySQL 5.7+ con sistema de migraciones
- **Servidor Web**: Apache (XAMPP recomendado)
- **Frontend**: HTML5, CSS3, JavaScript
- **Patrón de Diseño**: Modelo-Vista-Controlador (MVC)

## 📋 Requisitos del Sistema

- PHP 7.4 o superior
- MySQL 5.7 o superior (MariaDB 10.0+ compatible)
- Apache con mod_rewrite habilitado
- XAMPP 7.4+ (recomendado para desarrollo local)
- Extensiones PHP: pdo, pdo_mysql, session, mbstring, json
- Espacio en disco: 50MB mínimo para instalación base

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
│   ├── config.php             # Credenciales de BD (no versionado)
│   └── .htaccess              # Protección de archivos sensibles
├── 📁 controllers/            # Controladores MVC
│   ├── AuthController.php     # Autenticación y sesiones
│   ├── HomeController.php     # Dashboard y navegación
│   ├── RegisterController.php # Registro de usuarios
│   └── ReportesController.php # Sistema de reportes avanzado ⭐
├── 📁 models/                 # Modelos de datos
│   ├── AdministradorModel.php # Gestión de administradores
│   ├── DiaSemanaModel.php     # Gestión de días de la semana
│   ├── DisponibilidadModel.php # Gestión de horarios
│   ├── EstadoDisponibilidadModel.php # Estados de disponibilidad
│   ├── EstadoPagoModel.php    # Estados de pagos
│   ├── EstadoReservaModel.php # Estados de reservas
│   ├── EstadoUsuarioModel.php # Estados de usuarios
│   ├── EstudianteModel.php    # Gestión de estudiantes
│   ├── MateriaModel.php       # Gestión de materias
│   ├── PagoModel.php          # Gestión de pagos
│   ├── ProfesorModel.php      # Gestión de profesores
│   ├── ReservaModel.php       # Sistema de reservas ⭐
│   ├── ReviewModel.php        # Gestión de reseñas
│   ├── RoleModel.php          # Gestión de roles
│   └── UserModel.php          # Gestión de usuarios
├── 📁 views/                  # Vistas y plantillas
│   ├── info_pagos_prueba.php  # Información de pagos de prueba
│   ├── layouts/               # Layouts principales
│   │   ├── about.php          # Página acerca de
│   │   ├── crear_clase.php    # Crear clase
│   │   ├── disponibilidad.php # Configuración de horarios
│   │   ├── estudiantes.php    # Gestión de estudiantes
│   │   ├── home.php           # Página principal
│   │   ├── login.php          # Página de login
│   │   ├── mensajes.php       # Sistema de mensajes
│   │   ├── nav.php            # Navegación principal
│   │   ├── pagos.php          # Gestión de pagos
│   │   ├── profesores.php     # Gestión de profesores
│   │   ├── register.php       # Página de registro
│   │   ├── reportes.php       # Sistema de reportes avanzado ⭐
│   │   ├── reservas.php       # Gestión de reservas
│   │   ├── reviews.php        # Gestión de reseñas
│   │   ├── ver_estudiante.php # Ver estudiante
│   │   └── ver_pago.php       # Ver pago
│   ├── reportes/              # Sistema de reportes avanzado ⭐
│   │   ├── reporte_estudiante.php  # Reporte de estudiante
│   │   ├── reporte_general.php     # Reporte administrativo general
│   │   ├── reporte_ingresos.php    # Reporte de ingresos por período
│   │   ├── reporte_pagos.php       # Reporte de pagos
│   │   ├── reporte_profesor.php    # Reporte detallado de profesor
│   │   └── reporte_reservas.php    # Reporte de reservas
│   ├── views_estudiante/      # Dashboard estudiante con reservas
│   │   ├── confirmar_reserva_old.php # Confirmar reserva (antigua)
│   │   ├── confirmar_reserva.php    # Confirmar reserva
│   │   ├── estudiante_dashboard.php # Dashboard del estudiante
│   │   ├── explorar_materias.php    # Explorar materias
│   │   ├── explorar_precio_hora.php # Explorar precio por hora
│   │   ├── explorar_profesores.php  # Explorar profesores
│   │   ├── pago_exitoso.php         # Pago exitoso
│   │   ├── perfil_edit.php          # Editar perfil
│   │   ├── profesores_por_materia.php # Profesores por materia
│   │   ├── profesores_por_precio.php # Profesores por precio
│   │   ├── reserva_confirmada.php   # Reserva confirmada
│   │   └── reservar_clase.php       # Reservar clase
│   └── views_profesor/        # Dashboard profesor con calendario
│       ├── perfil_edit.php    # Editar perfil profesor
│       └── profesor_dashboard.php # Dashboard del profesor
├── 📁 public/                # Archivos estáticos
│   ├── css/                   # Hojas de estilo
│   │   ├── explorar_materias.css    # Estilos para explorar materias
│   │   ├── explorar_profesores.css  # Estilos para explorar profesores
│   │   ├── login.css          # Estilos para login
│   │   ├── profesores_por_materia.css # Estilos para profesores por materia
│   │   ├── register.css       # Estilos para registro
│   │   ├── reservar_clase.css # Estilos para reservar clase
│   │   ├── reservas.css       # Estilos para gestión de reservas
│   │   ├── reviews.css        # Estilos para reseñas
│   │   ├── style.css          # Estilos generales
│   │   └── variables-generales.css # Variables CSS generales
│   ├── js/                    # Scripts JavaScript
│   │   ├── explorar_materias.js     # JS para explorar materias
│   │   ├── explorar_precio_hora.js  # JS para explorar precio por hora
│   │   ├── explorar_profesores.js   # JS para explorar profesores
│   │   ├── profesores_por_materia.js # JS para profesores por materia
│   │   ├── reservar_clase.js  # JS para reservar clase
│   │   ├── reservas.js        # JS para gestión de reservas
│   │   └── script.js          # Script general
│   └── uploads/               # Archivos subidos por usuarios
│       ├── .htaccess          # Protección de archivos
│       └── profile_photos/    # Fotos de perfil de usuarios
├── 📄 .gitignore             # Archivos ignorados por Git
├── 📄 .htaccess              # Reglas de reescritura URL
├── 📄 index.php             # Punto de entrada de la aplicación
├── 📄 README_DATABASE.md    # Documentación de base de datos
├── 📄 README.md             # Este archivo
├── 📄 requirements.txt      # Requerimientos del sistema
└── 📄 setup.php             # Instalación automática
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
- ✅ **Sistema de Reportes**: Reportes detallados de rendimiento y actividad ⭐

### 🎓 Estudiante
- ✅ **Explorar Profesores**: Buscar profesores con filtros avanzados
- ✅ **Sistema de Reservas**: Ver disponibilidad y reservar clases en tiempo real
- ✅ **Calendario Personal**: Ver todas las reservas programadas
- ✅ **Gestión de Pagos**: Historial de pagos y facturas
- ✅ **Perfil Personal**: Gestionar información y preferencias
- ✅ **Sistema de Reseñas**: Calificar y comentar clases tomadas ⭐
- ✅ **Búsqueda por Materia**: Explorar profesores por materia específica
- ✅ **Búsqueda por Precio**: Filtrar profesores por rango de precio por hora

## 📅 Sistema de Reservas ⭐

### Funcionalidades Principales:
- **📆 Calendarios Interactivos**: Tanto profesores como estudiantes tienen calendarios visuales
- **⏰ Gestión de Disponibilidad**: Profesores configuran horarios semanales
- **🔍 Búsqueda Inteligente**: Estudiantes encuentran profesores disponibles
- **⚡ Reservas en Tiempo Real**: Verificación automática de conflictos
- **📊 Estados de Reserva**: Disponible → Reservado → Completado/Cancelado
- **💰 Integración con Pagos**: Reservas requieren confirmación de pago
- **⭐ Sistema de Reseñas**: Calificaciones después de clases completadas

### Flujo de Reserva:
1. **Profesor** configura su disponibilidad horaria
2. **Estudiante** explora profesores y ve horarios disponibles
3. **Estudiante** selecciona fecha/hora y confirma reserva
4. **Sistema** verifica disponibilidad y crea reserva
5. **Estudiante** realiza el pago de la clase
6. **Profesor** ve la reserva en su calendario
7. **Clase** se completa y cambia de estado automáticamente
8. **Estudiante** puede calificar la clase (opcional)

## 📊 Sistema de Reportes Avanzado ⭐

### Funcionalidades Principales:
- **📈 Reportes Personalizados**: Reportes específicos por rol (Administrador, Profesor, Estudiante)
- **🔍 Filtros Avanzados**: Filtrado por fechas, tipos de reporte y criterios específicos
- **📤 Exportación Múltiple**: Exportar reportes en PDF, Excel, CSV
- **📧 Envío por Email**: Enviar reportes automáticamente por email
- **📊 Estadísticas en Tiempo Real**: Métricas actualizadas de rendimiento y actividad

### Tipos de Reportes Disponibles:

#### 👨‍🏫 Reporte de Profesor
- **Estadísticas de Clases**: Total, completadas, pendientes, canceladas
- **Ingresos Totales**: Suma de todos los pagos recibidos
- **Calificaciones Promedio**: Rating promedio de estudiantes
- **Top Estudiantes**: Lista de estudiantes más activos
- **Historial de Clases**: Detalle completo de todas las clases impartidas

#### 🎓 Reporte de Estudiante
- **Historial de Clases**: Todas las clases tomadas
- **Total Invertido**: Suma de pagos realizados
- **Profesores Activos**: Número de profesores diferentes
- **Estado de Reservas**: Pendientes, completadas, canceladas

#### 👑 Reporte Administrativo General
- **Estadísticas Globales**: Total profesores, estudiantes, reservas, ingresos
- **Rendimiento por Profesor**: Clases, ingresos, estudiantes por profesor
- **Actividad por Estudiante**: Clases tomadas, inversión, profesores utilizados
- **Análisis de Reservas**: Tasas de completación y cancelación

#### 💰 Reporte de Pagos
- **Totales por Estado**: Pagos completados, pendientes, cancelados
- **Métodos de Pago**: Estadísticas por método de pago utilizado
- **Historial Completo**: Detalle de todos los pagos realizados

#### 📈 Reporte de Ingresos por Período
- **Ingresos por Mes/Año**: Evolución temporal de ingresos
- **Promedios por Período**: Cálculos estadísticos
- **Transacciones**: Número de transacciones por período

#### 📚 Reporte de Reservas
- **Estados de Reserva**: Completadas, pendientes, canceladas
- **Tasa de Completación**: Porcentaje de clases finalizadas exitosamente
- **Historial Detallado**: Todas las reservas con información completa

#### 💰 Reporte de Pagos
- **Estados de Pago**: Completados, pendientes, cancelados
- **Métodos de Pago**: Estadísticas por método de pago utilizado
- **Historial Completo**: Detalle de todos los pagos realizados
- **Totales por Estado**: Suma de montos según estado de pago

#### ⭐ Reporte de Reseñas
- **Calificaciones Promedio**: Rating promedio por profesor
- **Distribución de Calificaciones**: Estadísticas de calificaciones 1-5 estrellas
- **Comentarios Recientes**: Últimas reseñas y comentarios
- **Tendencias de Calidad**: Evolución de calificaciones por período

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
2. **Crear el controlador** en `controllers/` (verificar permisos con AuthController)
3. **Crear las vistas** en `views/` (responsive y accesibles)
4. **Actualizar migraciones** si se modifica la BD (`php migrations.php`)
5. **Agregar al sistema de reportes** si genera datos analíticos
6. **Probar la funcionalidad** completamente en todos los roles

### Sistema de Pagos - Desarrollo
Para agregar nuevos métodos de pago:
1. **Actualizar tabla pagos** con nuevos campos si es necesario
2. **Modificar PagoModel** para manejar nuevos métodos
3. **Crear vistas de pago** en `views/` para cada método
4. **Implementar validación** de transacciones
5. **Actualizar reportes** para incluir nuevos métodos

### Sistema de Reseñas - Desarrollo
Para mejorar el sistema de reseñas:
1. **Modificar ReviewModel** para agregar funcionalidades
2. **Crear vistas de reseñas** en `views/views_estudiante/`
3. **Implementar validación** de reseñas por reserva completada
4. **Agregar moderación** de reseñas inapropiadas
5. **Actualizar reportes** con estadísticas de reseñas

### Sistema de Reportes - Desarrollo
Para agregar nuevos tipos de reportes:
1. **Crear método en ReportesController** con filtros y permisos
2. **Crear vista específica** en `views/reportes/` con diseño responsive
3. **Implementar exportación** (PDF, Excel, CSV) en el controlador
4. **Agregar navegación** en el layout de reportes
5. **Probar filtros y exportación** en diferentes escenarios

### Comandos de Desarrollo Útiles
```bash
# Verificar estado del proyecto
php setup.php

# Actualizar base de datos
php migrations.php

# Ver logs de errores en tiempo real
tail -f xampp/apache/logs/error.log

# Backup de base de datos
mysqldump -u root -p plataforma_clases > backup_$(date +%Y%m%d).sql

# Acceder a reportes desde navegador
# http://localhost/plataforma-clases-online/reportes

# Ver estructura de base de datos
mysql -u root -p plataforma_clases -e "DESCRIBE users; DESCRIBE reservations;"

# Limpiar cache (si se implementa)
# rm -rf cache/*
```

## 🎯 Estado del Proyecto

### ✅ Funcionalidades Completadas (100% Completado)
- [x] Sistema de autenticación y roles
- [x] Dashboards personalizados por rol
- [x] Sistema completo de reservas con calendarios
- [x] Gestión de disponibilidad horaria
- [x] Sistema de migraciones automático
- [x] Interfaz responsive y moderna
- [x] **Sistema de Reportes Avanzado** ⭐
- [x] **Exportación de Reportes** (PDF, Excel, CSV, Email)
- [x] **Análisis de Rendimiento** con estadísticas detalladas
- [x] **Filtros y Búsqueda Avanzada** en reportes
- [x] **Sistema de Pagos Completo** ⭐
- [x] **Sistema de Reseñas y Calificaciones** ⭐
- [x] **Búsqueda Avanzada de Profesores** (por materia, precio, disponibilidad)
- [x] **Gestión de Perfiles con Fotos** ⭐
- [x] **Sistema de Estados de Reserva y Pago** ⭐

### 🚀 Próximas Mejoras (Fase 2 - Opcionales)
- [ ] Notificaciones por email automáticas
- [ ] Sistema de pagos integrado (PayPal, Stripe)
- [ ] Chat en tiempo real entre profesor-estudiante
- [ ] API REST para aplicaciones móviles
- [ ] Dashboard con gráficos interactivos (Chart.js avanzado)
- [ ] Backup automático de base de datos
- [ ] Logs de auditoría para acciones administrativas
- [ ] Sistema de cupones y descuentos
- [ ] Integración con calendario externo (Google Calendar)
- [ ] Sistema de recordatorios automáticos
- [ ] App móvil nativa

## 📞 Soporte

Si encuentras algún problema:

### 🔧 Solución de Problemas Comunes
1. **Error de conexión a BD**: Verifica credenciales en `config/database.php`
2. **PHP no reconocido**: Agrega PHP al PATH del sistema o usa ruta completa
3. **Permisos de archivos**: Asegúrate que Apache tenga permisos de escritura
4. **Migraciones fallidas**: Ejecuta `php migrations.php` para actualizar BD

### 🐛 Reportar Errores
1. Ejecuta `php setup.php` para diagnóstico automático
2. Revisa logs de Apache en `xampp/apache/logs/error.log`
3. Verifica configuración en `config/database.php`
4. Para reportes: Accede a `/plataforma-clases-online/reportes` y genera un reporte de error
5. Verifica permisos de archivos y carpetas
6. Comprueba que todas las extensiones PHP requeridas estén habilitadas

### 📋 Comandos Útiles para Desarrollo
```bash
# Verificar instalación completa
php setup.php

# Ver estado de la base de datos
mysql -u root -p plataforma_clases -e "SHOW TABLES;"

# Backup de base de datos
mysqldump -u root -p plataforma_clases > backup_$(date +%Y%m%d).sql

# Ver logs de errores en tiempo real
tail -f xampp/apache/logs/error.log

# Verificar permisos de archivos
ls -la

# Limpiar cache (si se implementa)
# rm -rf cache/*

# Ver estructura de base de datos completa
mysql -u root -p plataforma_clases -e "DESCRIBE users; DESCRIBE reservations; DESCRIBE payments; DESCRIBE reviews;"

# Ejecutar pruebas (si se implementan)
# phpunit tests/
```

---
**¡Tu plataforma de clases online está lista para usar! 🎓✨**
