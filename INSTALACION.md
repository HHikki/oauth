# 📋 Guía de Instalación - Sistema de Gestión de Clientes

## ⚠️ Requisitos que debes instalar

Tu sistema actualmente **NO tiene instalado** PHP ni Composer. Aquí está todo lo que necesitas:

---

## 🔧 PASO 1: Instalar PHP

### Opción A: XAMPP (Recomendado - Todo en uno)

1. **Descargar XAMPP:**
   - Ve a: https://www.apachefriends.org/download.html
   - Descarga la versión para Windows (PHP 8.x)

2. **Instalar XAMPP:**
   - Ejecuta el instalador
   - Instala en `C:\xampp` (ruta por defecto)
   - Selecciona: Apache, MySQL, PHP, phpMyAdmin

3. **Agregar PHP al PATH:**
   - Abre "Variables de entorno" (busca en Windows)
   - En "Variables del sistema", selecciona "Path" → Editar
   - Agrega: `C:\xampp\php`
   - Clic en Aceptar

4. **Verificar instalación:**
   - Abre una **nueva** terminal PowerShell
   - Ejecuta: `php --version`
   - Deberías ver la versión de PHP instalada

### Opción B: PHP Standalone (Solo PHP)

1. **Descargar PHP:**
   - Ve a: https://windows.php.net/download/
   - Descarga "VS16 x64 Thread Safe" (archivo .zip)

2. **Instalar:**
   - Extrae el ZIP en `C:\php`
   - Copia `php.ini-development` y renómbralo a `php.ini`
   - Edita `php.ini` y descomenta (quita el `;`):
     ```ini
     extension=curl
     extension=mbstring
     extension=openssl
     ```

3. **Agregar al PATH:**
   - Variables de entorno → Path → Agregar: `C:\php`

---

## 🎼 PASO 2: Instalar Composer

1. **Descargar Composer:**
   - Ve a: https://getcomposer.org/download/
   - Descarga "Composer-Setup.exe" para Windows

2. **Instalar:**
   - Ejecuta el instalador
   - El instalador detectará automáticamente PHP
   - Sigue las instrucciones (dejar opciones por defecto)

3. **Verificar instalación:**
   - Abre una **nueva** terminal PowerShell
   - Ejecuta: `composer --version`
   - Deberías ver la versión de Composer

---

## 🚀 PASO 3: Instalar dependencias del proyecto

Una vez que tengas PHP y Composer instalados:

```powershell
# Navega a la carpeta del proyecto (ya estás aquí)
cd C:\Users\Hikki\Documents\oauth

# Instala las dependencias
composer install
```

Esto instalará TCPDF (librería para generar PDFs).

---

## 🔥 PASO 4: Configurar Firebase

1. **Crear proyecto en Firebase:**
   - Ve a: https://console.firebase.google.com/
   - Clic en "Crear proyecto"
   - Dale un nombre (ej: "gestion-clientes")

2. **Habilitar Authentication:**
   - En el menú lateral: Build → Authentication
   - Clic en "Comenzar"
   - Habilita "Correo electrónico/contraseña"
   - Guarda los cambios

3. **Crear Realtime Database:**
   - En el menú lateral: Build → Realtime Database
   - Clic en "Crear base de datos"
   - Selecciona ubicación: United States (us-central1)
   - Inicia en **modo de prueba** (test mode)
   - Clic en "Habilitar"
   - **IMPORTANTE:** Copia la URL de tu base de datos (algo como: `https://tu-proyecto-default-rtdb.firebaseio.com`)

4. **Obtener credenciales:**
   - Ícono de engranaje (arriba izquierda) → Configuración del proyecto
   - En "Tus apps", clic en el ícono `</>` (Web)
   - Registra la app con un nombre (ej: "Web App")
   - **Copia toda la configuración** que aparece

5. **Configurar el proyecto:**
   - Edita el archivo: `config/firebase-config.php`
   - Reemplaza los valores con los de tu proyecto Firebase:
   ```php
   return [
       'apiKey' => "AIzaSyXXXXXXXXXXXXXXXXXXXXXXXXX",  // Tu API Key
       'authDomain' => "tu-proyecto.firebaseapp.com",
       'databaseURL' => "https://tu-proyecto-default-rtdb.firebaseio.com",
       'projectId' => "tu-proyecto",
       'storageBucket' => "tu-proyecto.appspot.com",
       'messagingSenderId' => "123456789",
       'appId' => "1:123456789:web:abc123"
   ];
   ```

---

## ▶️ PASO 5: Iniciar el proyecto

### Si instalaste XAMPP:

1. **Opción 1 - Panel de Control XAMPP:**
   - Abre el Panel de Control de XAMPP
   - Inicia Apache
   - Copia tu proyecto a: `C:\xampp\htdocs\oauth`
   - Accede desde: `http://localhost/oauth`

2. **Opción 2 - Servidor PHP integrado:**
   ```powershell
   cd C:\Users\Hikki\Documents\oauth
   php -S localhost:8000
   ```
   - Accede desde: `http://localhost:8000`

### Si instalaste PHP standalone:

```powershell
cd C:\Users\Hikki\Documents\oauth
php -S localhost:8000
```
- Accede desde: `http://localhost:8000`

---

## ✅ Verificación final

Antes de iniciar, verifica que TODO esté instalado:

```powershell
# Verifica PHP
php --version
# Debe mostrar: PHP 8.x.x

# Verifica Composer
composer --version
# Debe mostrar: Composer version 2.x.x

# Verifica extensión cURL (necesaria para Firebase)
php -m | Select-String curl
# Debe mostrar: curl

# Verifica que las dependencias estén instaladas
Test-Path ".\vendor"
# Debe mostrar: True
```

---

## 🎯 Resumen rápido

**Necesitas instalar (en orden):**

1. ✅ **PHP 8.x** (vía XAMPP o standalone)
2. ✅ **Composer** (gestor de dependencias)
3. ✅ **Crear proyecto Firebase** (gratis)
4. ✅ **Configurar credenciales** en `config/firebase-config.php`
5. ✅ **Instalar dependencias** con `composer install`
6. ✅ **Iniciar servidor** con `php -S localhost:8000`

---

## 🆘 Problemas comunes

### "php no se reconoce como comando"
- ❌ PHP no está en el PATH
- ✅ Reinicia la terminal después de instalar
- ✅ Verifica que agregaste PHP al PATH correctamente

### "composer no se reconoce como comando"
- ❌ Composer no está instalado o no está en el PATH
- ✅ Reinicia la terminal después de instalar
- ✅ Reinstala Composer si es necesario

### "Call to undefined function curl_init()"
- ❌ Extensión cURL no está habilitada
- ✅ Edita `php.ini` y descomenta: `extension=curl`
- ✅ Reinicia Apache (si usas XAMPP)

---

## 📞 ¿Necesitas ayuda?

Si tienes problemas en algún paso, avísame y te ayudo a resolverlo. 

**¡Éxito!** 🚀
