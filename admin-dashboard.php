<?php
require_once 'includes/session.php';
require_once 'includes/clients.php';

requireLogin();

// Verificar si es admin
if (!isAdmin()) {
    header('Location: dashboard.php');
    exit();
}

$clientsManager = new ClientsManager();
$clients = $clientsManager->getAllClients();

// Calcular estadísticas
$totalClients = count($clients);
$activeClients = 0;
$pendingClients = 0;
$clientsByMonth = array_fill(0, 12, 0);

foreach ($clients as $client) {
    // Simular clientes activos (puedes ajustar esto según tu lógica)
    if (rand(0, 1)) {
        $activeClients++;
    } else {
        $pendingClients++;
    }
    
    // Contar clientes por mes (si tienes fecha de creación)
    $month = rand(0, 11); // Simular distribución por mes
    $clientsByMonth[$month]++;
}

$newThisMonth = $clientsByMonth[date('n') - 1];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Gestión de Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #17a2b8;
        }
        
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
            border-left: 3px solid var(--primary-color);
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            opacity: 0.1;
        }
        
        .stat-card.blue::before { background: #667eea; }
        .stat-card.green::before { background: #28a745; }
        .stat-card.yellow::before { background: #ffc107; }
        .stat-card.red::before { background: #dc3545; }
        
        .stat-card h3 {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 10px 0;
        }
        
        .stat-card p {
            color: #6c757d;
            margin: 0;
            font-size: 0.9rem;
        }
        
        .stat-card .icon {
            position: absolute;
            right: 20px;
            top: 20px;
            font-size: 3rem;
            opacity: 0.2;
        }
        
        .stat-card.blue h3 { color: #667eea; }
        .stat-card.green h3 { color: #28a745; }
        .stat-card.yellow h3 { color: #ffc107; }
        .stat-card.red h3 { color: #dc3545; }
        
        .chart-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        
        .chart-card h5 {
            margin-bottom: 20px;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        .btn-action {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
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
        
        <div class="nav-item active">
            <i class="fas fa-th-large"></i> Dashboard
        </div>
        <div class="nav-item" onclick="window.location.href='admin-clients.php'">
            <i class="fas fa-users"></i> Clientes
        </div>
        <div class="nav-item" onclick="window.location.href='admin-reports.php'">
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
            <div>
                <h2 style="margin: 0; color: #2c3e50;">Dashboard</h2>
                <p style="margin: 0; color: #6c757d;">Bienvenido al panel de administración</p>
            </div>
            <div class="user-info">
                <span style="color: #6c757d;">
                    <i class="fas fa-user"></i> <?php echo htmlspecialchars(getUserEmail()); ?>
                </span>
                <?php if (getUserPhotoURL()): ?>
                    <img src="<?php echo htmlspecialchars(getUserPhotoURL()); ?>" alt="User" class="user-avatar">
                <?php else: ?>
                    <div class="user-avatar">
                        <?php echo strtoupper(substr(getUserEmail(), 0, 1)); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="stat-card blue">
                    <i class="fas fa-users icon"></i>
                    <p>Total Clientes</p>
                    <h3><?php echo $totalClients; ?></h3>
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> Más info
                    </small>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="stat-card green">
                    <i class="fas fa-calendar icon"></i>
                    <p>Clientes del Mes</p>
                    <h3><?php echo $newThisMonth; ?></h3>
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> Más info
                    </small>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="stat-card yellow">
                    <i class="fas fa-exclamation-triangle icon"></i>
                    <p>Clientes Activos</p>
                    <h3><?php echo $activeClients; ?></h3>
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> Más info
                    </small>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="stat-card red">
                    <i class="fas fa-clock icon"></i>
                    <p>Clientes Pendientes</p>
                    <h3><?php echo $pendingClients; ?></h3>
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> Más info
                    </small>
                </div>
            </div>
        </div>
        
        <!-- Charts -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="chart-card">
                    <h5><i class="fas fa-chart-bar"></i> Clientes por Mes</h5>
                    <canvas id="clientsChart"></canvas>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="chart-card">
                    <h5><i class="fas fa-calendar-alt"></i> Calendario</h5>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> Sistema funcionando correctamente
                    </div>
                    <div class="d-grid gap-2 mt-3">
                        <button class="btn-action" onclick="window.location.href='admin-clients.php'">
                            <i class="fas fa-users"></i> Ver Todos los Clientes
                        </button>
                        <button class="btn btn-outline-primary" onclick="window.location.href='generate_pdf.php'">
                            <i class="fas fa-file-pdf"></i> Generar Reporte PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Gráfico de clientes por mes
        const ctx = document.getElementById('clientsChart').getContext('2d');
        const clientsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                datasets: [{
                    label: 'Clientes',
                    data: <?php echo json_encode($clientsByMonth); ?>,
                    backgroundColor: 'rgba(102, 126, 234, 0.8)',
                    borderColor: 'rgba(102, 126, 234, 1)',
                    borderWidth: 2,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
