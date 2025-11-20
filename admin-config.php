<?php
require_once 'includes/session.php';

requireLogin();

// Verificar si es admin
if (!isAdmin()) {
    header('Location: dashboard.php');
    exit();
}
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
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>hikkidev27@gmail.com</td>
                            <td><span class="badge bg-success">Activo</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-danger" disabled>Remover</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <button class="btn btn-primary mt-3">
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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
