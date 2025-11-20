<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Gestión de Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 0;
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
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
        }
        .btn-register:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }
        .alert {
            border-radius: 10px;
        }
    </style>
</head>
<body>
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
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        if (empty($email) || empty($password) || empty($confirmPassword)) {
            $error = 'Por favor completa todos los campos';
        } elseif ($password !== $confirmPassword) {
            $error = 'Las contraseñas no coinciden';
        } elseif (strlen($password) < 6) {
            $error = 'La contraseña debe tener al menos 6 caracteres';
        } else {
            $config = require 'config/firebase-config.php';
            $auth = new FirebaseAuth($config['apiKey']);
            
            $result = $auth->signUp($email, $password);
            
            if ($result['success']) {
                $success = 'Cuenta creada exitosamente. Redirigiendo...';
                
                // Auto-login después del registro
                $userData = $result['data'];
                setUserSession(
                    $userData['localId'],
                    $userData['email'],
                    $userData['idToken']
                );
                
                header('Refresh: 2; URL=dashboard.php');
            } else {
                $errorMessage = $result['error'];
                
                // Traducir errores comunes
                if (strpos($errorMessage, 'EMAIL_EXISTS') !== false) {
                    $error = 'Este email ya está registrado';
                } elseif (strpos($errorMessage, 'INVALID_EMAIL') !== false) {
                    $error = 'Email inválido';
                } elseif (strpos($errorMessage, 'WEAK_PASSWORD') !== false) {
                    $error = 'Contraseña muy débil. Usa al menos 6 caracteres';
                } else {
                    $error = 'Error al registrar: ' . $errorMessage;
                }
            }
        }
    }
    ?>
    
    <div class="login-container">
        <div class="login-header">
            <i class="fas fa-user-plus"></i>
            <h2>Crear Cuenta</h2>
            <p class="mb-0">Regístrate para comenzar</p>
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
                           placeholder="Mínimo 6 caracteres" required minlength="6">
                </div>
                
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">
                        <i class="fas fa-lock"></i> Confirmar Contraseña
                    </label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                           placeholder="Repite tu contraseña" required minlength="6">
                </div>
                
                <button type="submit" class="btn btn-primary btn-register w-100">
                    <i class="fas fa-user-check"></i> Crear Cuenta
                </button>
            </form>
            
            <div class="text-center mt-4">
                <small class="text-muted">
                    ¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>
                </small>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
