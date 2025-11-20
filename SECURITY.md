# 🔒 Guía de Seguridad - Sistema OAuth

## Medidas de Seguridad Implementadas

### 1. Autenticación y Autorización
- ✅ Firebase Authentication con verificación de tokens
- ✅ Sistema de roles (Usuario/Administrador)
- ✅ Verificación de permisos en cada página protegida
- ✅ Sesiones seguras con PHP

### 2. Protección contra Ataques

#### Rate Limiting
- Límite de 5 intentos de login en 5 minutos
- Límite de 10 intentos de Google OAuth en 5 minutos
- Bloqueo temporal después de exceder el límite

#### Validación y Sanitización
- ✅ Validación de emails con `filter_var()`
- ✅ Sanitización de entrada con `htmlspecialchars()`
- ✅ Validación de longitud de contraseña (mínimo 6 caracteres)
- ✅ Prevención de inyección XSS

#### Headers de Seguridad HTTP
```
X-Frame-Options: SAMEORIGIN
X-Content-Type-Options: nosniff
X-XSS-Protection: 1; mode=block
Content-Security-Policy: ...
Referrer-Policy: strict-origin-when-cross-origin
Strict-Transport-Security: max-age=31536000 (HTTPS)
```

#### SSL/TLS
- ✅ Verificación SSL habilitada en todas las peticiones cURL
- ✅ Timeouts configurados (30s request, 10s connection)

### 3. Base de Datos
- ✅ Firebase Realtime Database con reglas de seguridad
- ✅ Validación de datos antes de guardar
- ✅ Sanitización de todos los campos

### 4. Gestión de Sesiones
- ✅ Regeneración de ID de sesión
- ✅ Verificación de sesión en páginas protegidas
- ✅ Cierre de sesión seguro

## Configuración de Seguridad para Producción

### 1. Configurar Reglas de Firebase Database

En la consola de Firebase → Realtime Database → Rules:

```json
{
  "rules": {
    ".read": false,
    ".write": false,
    
    "clients": {
      ".read": "auth != null",
      ".write": "auth != null"
    },
    
    "admins": {
      ".read": "auth != null",
      ".write": false
    }
  }
}
```

### 2. Variables de Entorno (Railway/Render)

Configura estas variables en tu plataforma de hosting:

```bash
FIREBASE_API_KEY=tu_api_key
FIREBASE_AUTH_DOMAIN=tu_proyecto.firebaseapp.com
FIREBASE_DATABASE_URL=https://tu_proyecto.firebaseio.com
FIREBASE_PROJECT_ID=tu_proyecto
FIREBASE_STORAGE_BUCKET=tu_proyecto.appspot.com
FIREBASE_MESSAGING_SENDER_ID=tu_sender_id
FIREBASE_APP_ID=tu_app_id
```

### 3. Configurar Dominios Autorizados en Firebase

1. Ve a Firebase Console → Authentication → Settings
2. En "Authorized domains" agrega:
   - Tu dominio de producción (ej: `tuapp.railway.app`)
   - `localhost` (solo para desarrollo)

### 4. Habilitar HTTPS

**Obligatorio en producción:**
- Railway/Render proporcionan HTTPS automáticamente
- Verifica que todas las URLs usen `https://`
- Los headers HSTS se activan automáticamente con HTTPS

### 5. Proteger Credenciales

**NUNCA subas a Git:**
- ❌ Credenciales de Firebase en texto plano
- ❌ Contraseñas de administradores
- ❌ Tokens de API

**Siempre usa:**
- ✅ Variables de entorno
- ✅ Archivos `.env` en `.gitignore`
- ✅ Secretos de Railway/Render

## Checklist de Seguridad

### Antes de Desplegar
- [ ] Variables de entorno configuradas
- [ ] Reglas de Firebase Database actualizadas
- [ ] HTTPS habilitado
- [ ] Dominios autorizados en Firebase
- [ ] `.gitignore` actualizado
- [ ] Credenciales eliminadas del código

### Después de Desplegar
- [ ] Probar login con rate limiting
- [ ] Verificar headers de seguridad
- [ ] Probar roles de usuario/admin
- [ ] Validar sesiones
- [ ] Probar logout

### Mantenimiento Regular
- [ ] Revisar logs de errores
- [ ] Monitorear intentos de login fallidos
- [ ] Actualizar dependencias (Composer)
- [ ] Revisar reglas de Firebase
- [ ] Auditar lista de administradores

## Vulnerabilidades Conocidas a Evitar

### ❌ No Hacer
- Exponer credenciales en el código
- Usar `SSL_VERIFYPEER = false` en producción
- Permitir contraseñas débiles (<6 caracteres)
- Guardar contraseñas en texto plano
- Confiar en datos del cliente sin validar
- Omitir sanitización de entrada

### ✅ Hacer
- Usar variables de entorno
- Validar todos los inputs
- Sanitizar todas las salidas
- Implementar rate limiting
- Usar HTTPS siempre
- Mantener dependencias actualizadas

## Respuesta a Incidentes

### Si detectas acceso no autorizado:
1. **Cambiar inmediatamente:**
   - Contraseñas de administradores
   - API Keys de Firebase
   - Regenerar tokens

2. **Revisar:**
   - Logs de Firebase Authentication
   - Logs de acceso del servidor
   - Lista de administradores

3. **Actualizar:**
   - Reglas de seguridad de Firebase
   - Variables de entorno
   - Código con últimos parches

## Contacto y Soporte

Para reportar vulnerabilidades de seguridad:
- NO abras issues públicos
- Contacta directamente al equipo de desarrollo
- Proporciona detalles específicos pero no exploits públicos

## Referencias

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [Firebase Security Rules](https://firebase.google.com/docs/rules)
- [PHP Security Best Practices](https://www.php.net/manual/en/security.php)
