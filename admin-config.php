<?php
require_once 'includes/session.php';
require_once 'includes/admins.php';

requireLogin();

// Verificar si es admin
if (!isAdmin()) {
    header('Location: dashboard.php');
    exit();
}

$adminsManager = new AdminsManager();
$message = '';
$messageType = '';

// Procesar acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add_admin') {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            $message = 'Email y contraseña son obligatorios';
            $messageType = 'danger';
        } else {
            $result = $adminsManager->addAdmin($email, $password);
            $message = $result['success'] ? $result['message'] : $result['error'];
            $messageType = $result['success'] ? 'success' : 'danger';
        }
    } elseif ($action === 'remove_admin') {
        $adminId = $_POST['admin_id'] ?? '';
        $result = $adminsManager->removeAdmin($adminId, getUserEmail());
        $message = $result['success'] ? $result['message'] : $result['error'];
        $messageType = $result['success'] ? 'success' : 'danger';
    }
}

// Obtener lista de administradores
$admins = $adminsManager->getAllAdmins();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .sidebar {
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            min-height: 100vh;
            width: 250px;
            position: fixed;
            left: 0;
            top: 0;
            padding: 20px 0;
            color: white;
        }
        
        .sidebar .brand {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
        
        .sidebar .brand h4 {
            color: white;
            margin: 0;
            font-weight: 600;
        }
        
        .sidebar .nav-item {
            padding: 12px 25px;
            color: rgba(255,255,255,0.8);
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .sidebar .nav-item:hover,
        .sidebar .nav-item.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left: 3px solid #667eea;
        }
        
        .sidebar .nav-item i {
            margin-right: 10px;
            width: 20px;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 30px;
        }
        
        .top-bar {
            background: white;
            padding: 20px 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .content-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        
        .config-section {
            border-bottom: 1px solid #e0e0e0;
            padding: 20px 0;
        }
        
        .config-section:last-child {
            border-bottom: none;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">
            <i class="fas fa-chart-line fa-2x mb-2"></i>
            <h4>AdminPanel</h4>
        </div>
        
        <div class="nav-item" onclick="window.location.href='admin-dashboard.php'">
            <i class="fas fa-th-large"></i> Dashboard
        </div>
        <div class="nav-item" onclick="window.location.href='admin-clients.php'">
            <i class="fas fa-users"></i> Clientes
        </div>
        <div class="nav-item" onclick="window.location.href='admin-reports.php'">
            <i class="fas fa-chart-bar"></i> Reportes
        </div>
        <div class="nav-item active">
            <i class="fas fa-cog"></i> Configuración
        </div>
        <div class="nav-item" onclick="window.location.href='logout.php'">
            <i class="fas fa-sign-out-alt"></i> Salir
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <h2 style="margin: 0; color: #2c3e50;">Configuración del Sistema</h2>
            <p style="margin: 0; color: #6c757d;">Administra las opciones del sistema</p>
        </div>
        
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <!-- Configuración General -->
        <div class="content-card">
            <h5 class="mb-4"><i class="fas fa-sliders-h"></i> Configuración General</h5>
            
            <div class="config-section">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Nombre de la Aplicación</h6>
                        <p class="text-muted mb-0">OAuth System</p>
                    </div>
                    <button class="btn btn-outline-primary btn-sm">Editar</button>
                </div>
            </div>
            
            <div class="config-section">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Modo de Mantenimiento</h6>
                        <p class="text-muted mb-0">Desactivar temporalmente el acceso al sistema</p>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="maintenanceMode">
                    </div>
                </div>
            </div>
            
            <div class="config-section">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Registro de Usuarios</h6>
                        <p class="text-muted mb-0">Permitir que nuevos usuarios se registren</p>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="allowRegistration" checked>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Configuración de Seguridad -->
        <div class="content-card">
            <h5 class="mb-4"><i class="fas fa-shield-alt"></i> Seguridad</h5>
            
            <div class="config-section">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Autenticación de Dos Factores</h6>
                        <p class="text-muted mb-0">Requerir verificación en dos pasos</p>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="twoFactorAuth">
                    </div>
                </div>
            </div>
            
            <div class="config-section">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Sesión Automática</h6>
                        <p class="text-muted mb-0">Cerrar sesión después de 30 minutos de inactividad</p>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="autoLogout" checked>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Administradores -->
        <div class="content-card">
            <h5 class="mb-4"><i class="fas fa-user-shield"></i> Administradores</h5>
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Email</th>
                            <th>Fecha de Creación</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($admins as $admin): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($admin['email']); ?></td>
                                <td><?php echo isset($admin['created_at']) ? htmlspecialchars($admin['created_at']) : 'N/A'; ?></td>
                                <td>
                                    <span class="badge bg-<?php echo ($admin['status'] ?? 'active') === 'active' ? 'success' : 'secondary'; ?>">
                                        <?php echo ucfirst($admin['status'] ?? 'active'); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($admin['email'] === getUserEmail()): ?>
                                        <span class="text-muted small"><i class="fas fa-user"></i> Tú</span>
                                    <?php elseif (isset($admin['id'])): ?>
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de eliminar este administrador?');">
                                            <input type="hidden" name="action" value="remove_admin">
                                            <input type="hidden" name="admin_id" value="<?php echo htmlspecialchars($admin['id']); ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i> Remover
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted small">Admin principal</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addAdminModal">
                <i class="fas fa-plus"></i> Agregar Administrador
            </button>
        </div>
        
        <!-- Información del Sistema -->
        <div class="content-card">
            <h5 class="mb-4"><i class="fas fa-info-circle"></i> Información del Sistema</h5>
            
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Versión:</strong> 1.0.0</p>
                    <p><strong>PHP:</strong> <?php echo phpversion(); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Base de Datos:</strong> Firebase Realtime Database</p>
                    <p><strong>Autenticación:</strong> Firebase Auth</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Agregar Administrador -->
    <div class="modal fade" id="addAdminModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h5 class="modal-title"><i class="fas fa-user-plus"></i> Agregar Administrador</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add_admin">
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> El nuevo administrador recibirá un email de verificación y podrá acceder al panel.
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" placeholder="admin@ejemplo.com" required>
                            <small class="text-muted">Ejemplo: admin@gmail.com</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Contraseña Inicial <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="password" placeholder="Mínimo 6 caracteres" required minlength="6">
                            <small class="text-muted">Ejemplo: admin123 (mínimo 6 caracteres)</small>
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> <strong>Importante:</strong> Comparte esta contraseña de forma segura con el nuevo administrador.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> Agregar Administrador
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
