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
    <title>Reportes - Admin Panel</title>
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
        
        .report-card {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .report-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        
        .report-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-right: 20px;
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
        <div class="nav-item active">
            <i class="fas fa-chart-bar"></i> Reportes
        </div>
        <div class="nav-item" onclick="window.location.href='admin-config.php'">
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
            <h2 style="margin: 0; color: #2c3e50;">Reportes y Análisis</h2>
            <p style="margin: 0; color: #6c757d;">Genera y descarga reportes del sistema</p>
        </div>
        
        <!-- Reportes Disponibles -->
        <div class="content-card">
            <h5 class="mb-4">Reportes Disponibles</h5>
            
            <div class="report-card" onclick="generateReport('clients')">
                <div class="d-flex align-items-center">
                    <div class="report-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Reporte de Clientes</h6>
                        <p class="text-muted mb-0">Listado completo de clientes registrados en el sistema</p>
                    </div>
                </div>
            </div>
            
            <div class="report-card" onclick="generateReport('activity')">
                <div class="d-flex align-items-center">
                    <div class="report-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Reporte de Actividad</h6>
                        <p class="text-muted mb-0">Estadísticas de uso y actividad del sistema</p>
                    </div>
                </div>
            </div>
            
            <div class="report-card" onclick="generateReport('users')">
                <div class="d-flex align-items-center">
                    <div class="report-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Reporte de Usuarios</h6>
                        <p class="text-muted mb-0">Información de usuarios registrados y administradores</p>
                    </div>
                </div>
            </div>
            
            <div class="report-card" onclick="generateReport('summary')">
                <div class="d-flex align-items-center">
                    <div class="report-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Resumen General</h6>
                        <p class="text-muted mb-0">Resumen ejecutivo con todas las métricas importantes</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Reportes Generados Recientemente -->
        <div class="content-card">
            <h5 class="mb-4">Reportes Generados Recientemente</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo de Reporte</th>
                            <th>Usuario</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No hay reportes generados aún</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function generateReport(type) {
            let reportName = '';
            switch(type) {
                case 'clients':
                    reportName = 'Clientes';
                    window.open('generate_pdf.php', '_blank');
                    break;
                case 'activity':
                    reportName = 'Actividad';
                    alert('Reporte de Actividad - Próximamente disponible');
                    break;
                case 'users':
                    reportName = 'Usuarios';
                    alert('Reporte de Usuarios - Próximamente disponible');
                    break;
                case 'summary':
                    reportName = 'Resumen General';
                    alert('Resumen General - Próximamente disponible');
                    break;
            }
        }
    </script>
</body>
</html>
