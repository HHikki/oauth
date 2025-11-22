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
$message = '';
$messageType = '';

// Manejar acciones CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'create':
            $result = $clientsManager->createClient($_POST);
            if ($result) {
                $message = 'Cliente creado exitosamente';
                $messageType = 'success';
            } else {
                $message = 'Error al crear cliente';
                $messageType = 'danger';
            }
            break;
            
        case 'update':
            $clientId = $_POST['client_id'] ?? '';
            $result = $clientsManager->updateClient($clientId, $_POST);
            if ($result) {
                $message = 'Cliente actualizado exitosamente';
                $messageType = 'success';
            } else {
                $message = 'Error al actualizar cliente';
                $messageType = 'danger';
            }
            break;
            
        case 'delete':
            $clientId = $_POST['client_id'] ?? '';
            $result = $clientsManager->deleteClient($clientId);
            if ($result !== false) {
                $message = 'Cliente eliminado exitosamente';
                $messageType = 'success';
            } else {
                $message = 'Error al eliminar cliente';
                $messageType = 'danger';
            }
            break;
    }
}

// Obtener todos los clientes
$clients = $clientsManager->getAllClients();
$totalClients = count($clients);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - Admin Panel</title>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .content-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        
        .table-actions .btn {
            margin: 0 2px;
            padding: 5px 10px;
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
        <div class="nav-item active">
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
                <h2 style="margin: 0; color: #2c3e50;">Gestión de Clientes</h2>
                <p style="margin: 0; color: #6c757d;">Total: <?php echo $totalClients; ?> clientes</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createClientModal">
                <i class="fas fa-plus"></i> Nuevo Cliente
            </button>
        </div>
        
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <!-- Tabla de Clientes -->
        <div class="content-card">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Empresa</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($clients)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No hay clientes registrados</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $counter = 1; ?>
                            <?php foreach ($clients as $client): ?>
                                <tr>
                                    <td><?php echo $counter++; ?></td>
                                    <td><?php echo htmlspecialchars($client['nombre'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($client['email'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($client['telefono'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($client['empresa'] ?? '-'); ?></td>
                                    <td class="table-actions">
                                        <button class="btn btn-sm btn-info" onclick='viewClient(<?php echo json_encode($client); ?>)'>
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning" onclick='editClient(<?php echo json_encode($client); ?>)'>
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteClient('<?php echo htmlspecialchars($client['id']); ?>')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Modal Crear Cliente -->
    <div class="modal fade" id="createClientModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="create">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" name="telefono" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Empresa</label>
                            <input type="text" class="form-control" name="empresa" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Dirección</label>
                            <textarea class="form-control" name="direccion" rows="2" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Modal Ver Cliente -->
    <div class="modal fade" id="viewClientModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h5 class="modal-title"><i class="fas fa-eye"></i> Información del Cliente</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong><i class="fas fa-user"></i> Nombre:</strong>
                        <p id="view_nombre" class="ms-4"></p>
                    </div>
                    <div class="mb-3">
                        <strong><i class="fas fa-envelope"></i> Email:</strong>
                        <p id="view_email" class="ms-4"></p>
                    </div>
                    <div class="mb-3">
                        <strong><i class="fas fa-phone"></i> Teléfono:</strong>
                        <p id="view_telefono" class="ms-4"></p>
                    </div>
                    <div class="mb-3">
                        <strong><i class="fas fa-building"></i> Empresa:</strong>
                        <p id="view_empresa" class="ms-4"></p>
                    </div>
                    <div class="mb-3">
                        <strong><i class="fas fa-map-marker-alt"></i> Dirección:</strong>
                        <p id="view_direccion" class="ms-4"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Editar Cliente -->
    <div class="modal fade" id="editClientModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Editar Cliente</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="client_id" id="edit_client_id">
                        
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" id="edit_nombre" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="edit_email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" name="telefono" id="edit_telefono" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Empresa</label>
                            <input type="text" class="form-control" name="empresa" id="edit_empresa" placeholder="Opcional">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Dirección</label>
                            <textarea class="form-control" name="direccion" id="edit_direccion" rows="2" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function viewClient(client) {
            document.getElementById('view_nombre').textContent = client.nombre || 'N/A';
            document.getElementById('view_email').textContent = client.email || 'N/A';
            document.getElementById('view_telefono').textContent = client.telefono || 'N/A';
            document.getElementById('view_empresa').textContent = client.empresa || 'No especificada';
            document.getElementById('view_direccion').textContent = client.direccion || 'N/A';
            
            const modal = new bootstrap.Modal(document.getElementById('viewClientModal'));
            modal.show();
        }
        
        function editClient(client) {
            document.getElementById('edit_client_id').value = client.id;
            document.getElementById('edit_nombre').value = client.nombre || '';
            document.getElementById('edit_email').value = client.email || '';
            document.getElementById('edit_telefono').value = client.telefono || '';
            document.getElementById('edit_empresa').value = client.empresa || '';
            document.getElementById('edit_direccion').value = client.direccion || '';
            
            const modal = new bootstrap.Modal(document.getElementById('editClientModal'));
            modal.show();
        }
        
        function deleteClient(clientId) {
            if (confirm('¿Estás seguro de eliminar este cliente?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="client_id" value="${clientId}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>
