<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gestión de Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 10px;
            margin-bottom: 30px;
            padding: 15px 25px;
        }
        .navbar-custom .navbar-brand {
            color: #667eea;
            font-weight: 700;
            font-size: 1.5rem;
        }
        .navbar-custom .navbar-brand i {
            margin-right: 10px;
        }
        .card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 20px;
            font-weight: 600;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }
        .table-actions .btn {
            margin: 0 2px;
            padding: 5px 10px;
        }
        .badge-total {
            background: white;
            color: #667eea;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 1rem;
        }
        .btn-logout {
            color: #dc3545;
        }
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .user-info {
            color: #666;
            margin-right: 20px;
        }
    </style>
</head>
<body>
    <?php
    require_once 'includes/session.php';
    require_once 'includes/clients.php';
    
    requireLogin();
    
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
    
    <div class="container">
        <!-- Navbar -->
        <nav class="navbar navbar-custom">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h1">
                    <i class="fas fa-users"></i>
                    Gestión de Clientes
                </span>
                <div class="d-flex align-items-center">
                    <span class="user-info">
                        <i class="fas fa-user-circle"></i>
                        <?php echo htmlspecialchars(getUserEmail()); ?>
                    </span>
                    <a href="logout.php" class="btn btn-link btn-logout">
                        <i class="fas fa-sign-out-alt"></i> Salir
                    </a>
                </div>
            </div>
        </nav>
        
        <!-- Mensajes -->
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <!-- Sistema CRUD completo con generación de PDF -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span style="font-size: 1.2rem;">
                    Sistema CRUD completo con generación de PDF
                </span>
            </div>
            <div class="card-body" style="background: rgba(255,255,255,0.95);">
                <!-- Botones de acción -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoCliente">
                        <i class="fas fa-plus-circle"></i> Nuevo Cliente
                    </button>
                    
                    <div class="d-flex gap-2">
                        <a href="generate_pdf.php" class="btn btn-danger" target="_blank">
                            <i class="fas fa-file-pdf"></i> Generar PDF
                        </a>
                        <span class="badge-total">
                            <i class="fas fa-list"></i> Total: <?php echo $totalClients; ?> clientes
                        </span>
                    </div>
                </div>
                
                <!-- Tabla de clientes -->
                <div class="card">
                    <div class="card-header" style="background: white; color: #333; font-weight: 600;">
                        <i class="fas fa-address-book"></i> Lista de Clientes
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: #f8f9fa;">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Teléfono</th>
                                        <th>Dirección</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($clients)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                                No hay clientes registrados. ¡Crea el primero!
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php $counter = 1; ?>
                                        <?php foreach ($clients as $client): ?>
                                            <tr>
                                                <td><?php echo $counter++; ?></td>
                                                <td><?php echo htmlspecialchars($client['nombre']); ?></td>
                                                <td><?php echo htmlspecialchars($client['email']); ?></td>
                                                <td><?php echo htmlspecialchars($client['telefono']); ?></td>
                                                <td><?php echo htmlspecialchars($client['direccion']); ?></td>
                                                <td class="text-center table-actions">
                                                    <button class="btn btn-sm btn-warning" 
                                                            onclick="showViewModal('<?php echo htmlspecialchars(json_encode($client)); ?>')">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-primary" 
                                                            onclick="showEditModal('<?php echo htmlspecialchars(json_encode($client)); ?>')">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" 
                                                            onclick="confirmDelete('<?php echo $client['id']; ?>', '<?php echo htmlspecialchars($client['nombre']); ?>')">
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
            </div>
        </div>
    </div>
    
    <!-- Modal Nuevo Cliente -->
    <div class="modal fade" id="modalNuevoCliente" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user-plus"></i> Nuevo Cliente</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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
                            <input type="text" class="form-control" name="telefono" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Dirección</label>
                            <textarea class="form-control" name="direccion" rows="2" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Modal Editar Cliente -->
    <div class="modal fade" id="modalEditarCliente" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Editar Cliente</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="formEditarCliente">
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
                            <input type="text" class="form-control" name="telefono" id="edit_telefono" required>
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
    
    <!-- Modal Ver Cliente -->
    <div class="modal fade" id="modalVerCliente" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
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
    
    <!-- Form para eliminar (oculto) -->
    <form id="formEliminar" method="POST" style="display: none;">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="client_id" id="delete_client_id">
    </form>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showEditModal(clientJson) {
            const client = JSON.parse(clientJson);
            document.getElementById('edit_client_id').value = client.id;
            document.getElementById('edit_nombre').value = client.nombre;
            document.getElementById('edit_email').value = client.email;
            document.getElementById('edit_telefono').value = client.telefono;
            document.getElementById('edit_direccion').value = client.direccion;
            
            const modal = new bootstrap.Modal(document.getElementById('modalEditarCliente'));
            modal.show();
        }
        
        function showViewModal(clientJson) {
            const client = JSON.parse(clientJson);
            document.getElementById('view_nombre').textContent = client.nombre;
            document.getElementById('view_email').textContent = client.email;
            document.getElementById('view_telefono').textContent = client.telefono;
            document.getElementById('view_direccion').textContent = client.direccion;
            
            const modal = new bootstrap.Modal(document.getElementById('modalVerCliente'));
            modal.show();
        }
        
        function confirmDelete(clientId, clientName) {
            if (confirm(`¿Estás seguro de eliminar al cliente "${clientName}"?`)) {
                document.getElementById('delete_client_id').value = clientId;
                document.getElementById('formEliminar').submit();
            }
        }
    </script>
</body>
</html>
