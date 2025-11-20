# 🔥 Sistema de Gestión de Clientes - PHP + Firebase

Sistema CRUD completo con autenticación, gestión de clientes y generación de PDF usando PHP y Firebase Realtime Database.

## 📋 Características

- ✅ Autenticación con Firebase (Email/Contraseña)
- ✅ Registro de nuevos usuarios
- ✅ CRUD completo de clientes (Crear, Leer, Actualizar, Eliminar)
- ✅ Generación de PDF con lista de clientes
- ✅ Interfaz moderna y responsive con Bootstrap 5
- ✅ Sesiones seguras
- ✅ Preparado para login con Google (próximamente)

## 🛠️ Requisitos

- PHP 7.4 o superior
- Servidor web (Apache/Nginx) o PHP built-in server
- Composer (para instalar dependencias)
- Cuenta de Firebase (gratuita)
- Extensión PHP cURL habilitada

## 📦 Instalación

### 1. Clonar o descargar el proyecto

El proyecto ya está en: `c:\Users\Hikki\Documents\oauth`

### 2. Instalar dependencias con Composer

Abre una terminal en la carpeta del proyecto y ejecuta:

```bash
composer install
```

Esto instalará la librería TCPDF para generar PDFs.

### 3. Configurar Firebase

#### 3.1 Crear proyecto en Firebase

1. Ve a [Firebase Console](https://console.firebase.google.com/)
2. Crea un nuevo proyecto o selecciona uno existente
3. En el panel lateral, ve a **Build → Authentication**
4. Habilita el método de autenticación "Email/password"

#### 3.2 Habilitar Realtime Database

1. En el panel lateral, ve a **Build → Realtime Database**
2. Clic en "Create Database"
3. Selecciona una ubicación (ej: us-central1)
4. Inicia en modo de prueba (test mode) para desarrollo
5. Copia la URL de tu base de datos (algo como: `https://tu-proyecto-default-rtdb.firebaseio.com`)

#### 3.3 Obtener credenciales de Firebase

1. En el panel lateral, ve a **Configuración del proyecto** (ícono de engranaje)
2. En la pestaña "General", busca "Tus apps"
3. Si no tienes una app web, clic en el ícono `</>` para agregar una
4. Registra la app y copia la configuración (apiKey, authDomain, etc.)

#### 3.4 Configurar el proyecto

Edita el archivo `config/firebase-config.php` y reemplaza con tus datos:

```php
return [
    'apiKey' => "TU_API_KEY_AQUI",
    'authDomain' => "tu-proyecto.firebaseapp.com",
    'databaseURL' => "https://tu-proyecto-default-rtdb.firebaseio.com",
    'projectId' => "tu-proyecto",
    'storageBucket' => "tu-proyecto.appspot.com",
    'messagingSenderId' => "123456789",
    'appId' => "1:123456789:web:abc123def456"
];
```

### 4. Iniciar el servidor

#### Opción A: Servidor PHP integrado (recomendado para desarrollo)

```bash
php -S localhost:8000
```

#### Opción B: Usar XAMPP/WAMP

Copia el proyecto a la carpeta `htdocs` o `www` y accede desde el navegador.

### 5. Acceder a la aplicación

Abre tu navegador y ve a:
- `http://localhost:8000/login.php` (si usas PHP integrado)
- `http://localhost/oauth/login.php` (si usas XAMPP/WAMP)

## 🚀 Uso

### Primer acceso

1. Ve a la página de **Registro** (link en el login)
2. Crea tu cuenta con email y contraseña
3. Serás redirigido automáticamente al dashboard

### Gestión de clientes

En el dashboard puedes:

- **➕ Crear cliente**: Clic en "Nuevo Cliente"
- **👁️ Ver cliente**: Botón amarillo en la tabla
- **✏️ Editar cliente**: Botón azul en la tabla
- **🗑️ Eliminar cliente**: Botón rojo en la tabla
- **📄 Generar PDF**: Botón rojo "Generar PDF" para descargar lista de clientes

## 📁 Estructura del proyecto

```
oauth/
├── config/
│   ├── firebase-config.php    # Configuración de Firebase
│   └── database.php            # Clase para Realtime Database
├── includes/
│   ├── auth.php                # Clase de autenticación
│   ├── session.php             # Manejo de sesiones
│   └── clients.php             # Gestión de clientes
├── vendor/                     # Dependencias (Composer)
├── composer.json               # Archivo de dependencias
├── login.php                   # Página de inicio de sesión
├── register.php                # Página de registro
├── dashboard.php               # Panel principal con CRUD
├── generate_pdf.php            # Generador de PDF
├── logout.php                  # Cerrar sesión
└── README.md                   # Este archivo
```

## 🔒 Seguridad

### Para producción, asegúrate de:

1. **Cambiar reglas de Firebase Database**: En la consola de Firebase, ve a Realtime Database → Rules y configura:

```json
{
  "rules": {
    "clients": {
      ".read": "auth != null",
      ".write": "auth != null"
    }
  }
}
```

2. **No exponer credenciales**: No subas `firebase-config.php` a repositorios públicos
3. **Usar HTTPS**: En producción, siempre usa conexión segura
4. **Configurar CORS**: Si usas API desde otros dominios

## 🐛 Solución de problemas

### Error: "Librería TCPDF no instalada"
- Ejecuta `composer install` en la carpeta del proyecto

### Error: "Call to undefined function curl_init()"
- Habilita la extensión cURL en tu `php.ini`

### Error de Firebase: "INVALID_API_KEY"
- Verifica que copiaste correctamente el API Key en `firebase-config.php`

### Error: "Permission denied"
- Verifica las reglas de seguridad en Firebase Realtime Database

### Los clientes no se guardan
- Verifica que la URL de la base de datos sea correcta
- Revisa que las reglas permitan escritura en modo de prueba

## 🔮 Próximas mejoras

- [ ] Login con Google OAuth
- [ ] Recuperación de contraseña
- [ ] Subir imagen de perfil de clientes
- [ ] Exportar a Excel
- [ ] Búsqueda y filtros
- [ ] Paginación de resultados
- [ ] Roles de usuario (Admin/Usuario)

## 📝 Notas importantes

- Firebase tiene un plan gratuito generoso (Spark Plan)
- En modo de prueba, la base de datos es pública por 30 días
- Las reglas de seguridad deben configurarse antes de producción
- El login con Google requiere configuración OAuth adicional

## 📧 Soporte

Si tienes problemas:
1. Verifica que PHP y Composer estén instalados
2. Revisa que las extensiones de PHP estén habilitadas
3. Confirma que la configuración de Firebase sea correcta
4. Revisa los logs de error de PHP

---

**¡Listo!** Tu sistema de gestión de clientes está funcionando. 🎉
