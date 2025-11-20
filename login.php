<?php
require_once 'includes/session.php';
require_once 'config/firebase-config.php';
require_once 'includes/auth.php';

// Si ya está logueado, redirigir al dashboard
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Por favor ingresa email y contraseña';
    } else {
        $config = require 'config/firebase-config.php';
        $auth = new FirebaseAuth($config['apiKey']);
        
        $result = $auth->signIn($email, $password);
        
        if ($result['success']) {
            // Login exitoso
            $userData = $result['data'];
            setUserSession(
                $userData['localId'],
                $userData['email'],
                $userData['idToken']
            );
            
            header('Location: dashboard.php');
            exit();
        } else {
            // Error en login
            $errorMessage = $result['error'];
            
            // Traducir errores comunes
            if (strpos($errorMessage, 'INVALID_PASSWORD') !== false || 
                strpos($errorMessage, 'EMAIL_NOT_FOUND') !== false) {
                $error = 'Email o contraseña incorrectos';
            } elseif (strpos($errorMessage, 'TOO_MANY_ATTEMPTS') !== false) {
                $error = 'Demasiados intentos fallidos. Intenta más tarde';
            } else {
                $error = 'Error al iniciar sesión: ' . $errorMessage;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Gestión de Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script type="module">
        // Importar Firebase
        import { initializeApp } from 'https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js';
        import { getAuth, signInWithPopup, GoogleAuthProvider } from 'https://www.gstatic.com/firebasejs/10.7.1/firebase-auth.js';
        
        // Configuración de Firebase
        const firebaseConfig = <?php echo json_encode(require 'config/firebase-config.php'); ?>;
        
        // Inicializar Firebase
        const app = initializeApp(firebaseConfig);
        const auth = getAuth(app);
        const provider = new GoogleAuthProvider();
        
        // Exponer función global para login con Google
        window.signInWithGoogle = async function() {
            const btn = document.getElementById('googleBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Conectando...';
            
            try {
                const result = await signInWithPopup(auth, provider);
                const user = result.user;
                const idToken = await user.getIdToken();
                
                // Enviar token al servidor para crear sesión
                const response = await fetch('google-callback.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        idToken: idToken,
                        email: user.email,
                        uid: user.uid,
                        displayName: user.displayName,
                        photoURL: user.photoURL
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    window.location.href = 'dashboard.php';
                } else {
                    throw new Error(data.error || 'Error al iniciar sesión');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al iniciar sesión con Google: ' + error.message);
                btn.disabled = false;
                btn.innerHTML = '<i class="fab fa-google"></i> Continuar con Google';
            }
        };
    </script>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            max-width: 400px;
            width: 100%;
        }
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .login-header i {
            font-size: 50px;
            margin-bottom: 15px;
        }
        .login-body {
            padding: 40px;
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }
        .btn-google {
            border: 1px solid #ddd;
            background: white;
            color: #333;
            padding: 12px;
            font-weight: 600;
        }
        .btn-google:hover {
            background: #f8f9fa;
        }
        .divider {
            text-align: center;
            margin: 20px 0;
            position: relative;
        }
        .divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 45%;
            height: 1px;
            background: #ddd;
        }
        .divider::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            width: 45%;
            height: 1px;
            background: #ddd;
        }
        .alert {
            border-radius: 10px;
        }
    </style>
</head>
<body>
    
    <div class="login-container">
        <div class="login-header">
            <i class="fas fa-users"></i>
            <h2>Gestión de Clientes</h2>
            <p class="mb-0">Inicia sesión para continuar</p>
        </div>
        <div class="login-body">
            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope"></i> Email
                    </label>
                    <input type="email" class="form-control" id="email" name="email" 
                           placeholder="correo@ejemplo.com" required>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock"></i> Contraseña
                    </label>
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="********" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-login w-100">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                </button>
            </form>
            
            <div class="divider">
                <span class="bg-white px-2 text-muted">o</span>
            </div>
            
            <button class="btn btn-google w-100" id="googleBtn" onclick="signInWithGoogle()">
                <i class="fab fa-google"></i> Continuar con Google
            </button>
            
            <div class="text-center mt-4">
                <small class="text-muted">
                    ¿No tienes cuenta? <a href="register.php">Regístrate aquí</a>
                </small>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
