# Plataforma de Clases Online

Una plataforma web completa para la gestión de clases en línea, desarrollada con PHP, MySQL y arquitectura MVC.

## 🚀 Características

- **Sistema de Roles**: Administrador, Profesor y Estudiante con permisos específicos
- **Gestión de Usuarios**: Registro, autenticación y perfiles personalizados
- **Dashboard Personalizado**: Interfaces adaptadas según el rol del usuario
- **Gestión de Clases**: Reservas, disponibilidad y pagos
- **Sistema Seguro**: Protección de rutas y validación de permisos

## 🛠️ Tecnologías Utilizadas

- **Backend**: PHP 7.4+
- **Base de Datos**: MySQL 5.7+
- **Arquitectura**: MVC (Modelo-Vista-Controlador)
- **Servidor Web**: Apache (XAMPP recomendado)
- **Frontend**: HTML5, CSS3, JavaScript

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
├── config/                 # Configuración de BD y seguridad
│   ├── database.php        # Credenciales de BD (no versionado)
│   ├── database.example.php # Plantilla de configuración
│   └── .htaccess           # Protección de archivos sensibles
├── controllers/            # Controladores MVC
│   ├── AuthController.php  # Autenticación y sesiones
│   ├── HomeController.php  # Dashboard y navegación
│   └── RegisterController.php # Registro de usuarios
├── models/                 # Modelos de datos
│   ├── UserModel.php       # Gestión de usuarios
│   ├── RoleModel.php       # Roles del sistema
│   └── ...                 # Otros modelos específicos
├── views/                  # Vistas y plantillas
│   ├── views_profesor/     # Vistas específicas de profesores
│   ├── views_estudiante/   # Vistas específicas de estudiantes
│   ├── home.php           # Dashboard administrador
│   ├── login.php          # Formulario de login
│   ├── register.php       # Formulario de registro
│   └── nav.php            # Navegación principal
├── public/                # Archivos estáticos
│   ├── css/               # Hojas de estilo
│   └── js/                # Scripts JavaScript
├── plataforma_clases.sql  # Script de base de datos
├── setup.php             # Script de instalación automática
├── requirements.txt      # Requerimientos del sistema
├── .htaccess             # Reglas de reescritura URL
├── .gitignore           # Archivos ignorados por Git
├── index.php            # Punto de entrada de la aplicación
└── README.md            # Este archivo
```

## 🔐 Sistema de Roles y Permisos

### Administrador
- Acceso completo a todas las funcionalidades
- Gestión de usuarios, profesores y estudiantes
- Visualización de estadísticas y reportes
- Configuración del sistema

### Profesor
- Gestión de su perfil y disponibilidad
- Visualización de reservas asignadas
- Gestión de estudiantes inscritos
- Actualización de información académica

### Estudiante
- Búsqueda y reserva de clases
- Gestión de pagos y facturas
- Visualización de historial de clases
- Perfil personal

## 🛡️ Seguridad

- **Protección de Rutas**: Verificación de permisos en cada controlador
- **Validación de Datos**: Sanitización y validación de inputs
- **Sesiones Seguras**: Manejo seguro de sesiones de usuario
- **Archivos Protegidos**: Configuración de BD inaccesible desde web

## 📝 Desarrollo

### Convenciones de Código
- PSR-4 para autoloading
- Nombres de archivos en PascalCase para clases
- Nombres de tablas en snake_case
- Comentarios en español

### Agregar Nuevas Funcionalidades
1. Crear el modelo en `models/`
2. Crear el controlador en `controllers/`
3. Crear las vistas en `views/`
4. Actualizar la navegación si es necesario
