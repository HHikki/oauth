# 🔧 Solución: Error al iniciar sesión con Google

## Problema
```
Error al iniciar sesión con Google: Firebase: Error (auth/internal-error)
```

Este error ocurre porque el dominio de Railway (`oauth-production-7fac.up.railway.app`) no está autorizado en Firebase.

## ✅ Solución Paso a Paso

### 1. Ir a Firebase Console
Abre: https://console.firebase.google.com/

### 2. Seleccionar tu proyecto
- Selecciona: **auth-4edc2**

### 3. Ir a Authentication
- En el menú lateral → **Authentication**
- Pestaña → **Settings** (Configuración)

### 4. Agregar Dominio Autorizado
- Scroll hacia abajo hasta **"Authorized domains"** (Dominios autorizados)
- Click en **"Add domain"** (Agregar dominio)
- Ingresa: `oauth-production-7fac.up.railway.app`
- Click en **"Add"** (Agregar)

### 5. Verificar Dominios Autorizados
Deberías tener estos dominios:
- ✅ `localhost` (para desarrollo local)
- ✅ `oauth-production-7fac.up.railway.app` (para producción)
- ✅ `auth-4edc2.firebaseapp.com` (automático)

### 6. Esperar 1-2 minutos
Firebase necesita propagar los cambios. Espera un momento y reintenta.

## 🖼️ Capturas de Pantalla de Referencia

**Ubicación de Authorized Domains:**
```
Firebase Console
└── Authentication
    └── Settings (pestaña)
        └── Authorized domains (sección)
            └── [Add domain] (botón)
```

## 🔍 Verificación

Después de agregar el dominio:
1. Refresca la página de login
2. Click en "Continuar con Google"
3. Debería abrir el popup de Google sin errores

## 🛠️ Soluciones Alternativas

### Opción A: Usar Login con Email/Contraseña
- El login con email funciona normalmente
- No requiere configuración de dominios

### Opción B: Desarrollo Local
- Ejecuta el proyecto localmente: `php -S localhost:8000`
- `localhost` ya está autorizado por defecto en Firebase

### Opción C: Agregar Múltiples Dominios
Si Railway cambia tu URL, agrega:
- `*.railway.app` (Firebase no acepta wildcards, debes agregar cada uno)
- O cada URL específica que Railway te asigne

## 📝 Comandos Útiles

### Ver dominio actual de Railway:
```bash
railway domain
```

### Ver todas las URLs del proyecto:
```bash
railway status
```

## ⚡ Automatización (Opcional)

Puedes agregar un script que muestre el dominio actual:

```php
<?php
// En cualquier página
$currentDomain = $_SERVER['HTTP_HOST'] ?? 'localhost';
echo "Dominio actual: " . $currentDomain;
?>
```

## 🔗 Enlaces Útiles

- Firebase Console: https://console.firebase.google.com/
- Tu proyecto: https://console.firebase.google.com/project/auth-4edc2/authentication/settings
- Railway Dashboard: https://railway.app/dashboard

## ❓ Preguntas Frecuentes

### ¿Por qué no aparece el error en localhost?
`localhost` está autorizado por defecto en Firebase.

### ¿Cuánto tiempo tarda en aplicar?
Usualmente 1-2 minutos, máximo 5 minutos.

### ¿Qué pasa si Railway cambia mi URL?
Deberás agregar la nueva URL a Firebase.

### ¿Puedo usar un dominio personalizado?
Sí, configura un dominio personalizado en Railway y agrégalo a Firebase.

## ✅ Checklist Post-Configuración

- [ ] Dominio agregado en Firebase
- [ ] Esperado 1-2 minutos
- [ ] Página refrescada
- [ ] Google OAuth funcionando
- [ ] Login con email funcionando (backup)

---

**Actualizado:** 20 de noviembre, 2025
