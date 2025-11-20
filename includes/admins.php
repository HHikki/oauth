<?php
// Gestión de administradores

require_once __DIR__ . '/../config/firebase-config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/auth.php';

class AdminsManager {
    private $db;
    private $auth;
    
    public function __construct() {
        $config = require __DIR__ . '/../config/firebase-config.php';
        $this->db = new FirebaseDB($config);
        $this->auth = new FirebaseAuth($config['apiKey']);
    }
    
    // Obtener todos los administradores
    public function getAllAdmins() {
        $result = $this->db->get('/admins');
        
        if (!$result) {
            // Si no existe la colección, crear con el admin inicial
            $config = require __DIR__ . '/../config/firebase-config.php';
            $initialAdmins = [];
            foreach ($config['adminEmails'] as $email) {
                $initialAdmins[] = [
                    'email' => $email,
                    'created_at' => date('Y-m-d H:i:s'),
                    'status' => 'active'
                ];
            }
            return $initialAdmins;
        }
        
        $admins = [];
        foreach ($result as $id => $admin) {
            $admin['id'] = $id;
            $admins[] = $admin;
        }
        
        return $admins;
    }
    
    // Verificar si un email es administrador
    public function isAdmin($email) {
        $admins = $this->getAllAdmins();
        foreach ($admins as $admin) {
            if ($admin['email'] === $email && $admin['status'] === 'active') {
                return true;
            }
        }
        return false;
    }
    
    // Agregar nuevo administrador
    public function addAdmin($email, $password) {
        // Validar email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'error' => 'Email inválido'];
        }
        
        // Validar contraseña (mínimo 6 caracteres)
        if (strlen($password) < 6) {
            return ['success' => false, 'error' => 'La contraseña debe tener al menos 6 caracteres'];
        }
        
        // Verificar si ya existe
        $admins = $this->getAllAdmins();
        foreach ($admins as $admin) {
            if ($admin['email'] === $email) {
                return ['success' => false, 'error' => 'El email ya está registrado como administrador'];
            }
        }
        
        // Crear usuario en Firebase Authentication
        $result = $this->auth->signUp($email, $password);
        
        if (!$result['success']) {
            $errorMsg = $result['error'];
            
            // Traducir errores comunes
            if (strpos($errorMsg, 'EMAIL_EXISTS') !== false) {
                // Si el usuario ya existe en Firebase Auth, solo agregarlo a la lista
                $adminData = [
                    'email' => $email,
                    'created_at' => date('Y-m-d H:i:s'),
                    'status' => 'active'
                ];
                
                $saveResult = $this->db->create('/admins', $adminData);
                
                if ($saveResult) {
                    return ['success' => true, 'message' => 'Administrador agregado exitosamente'];
                } else {
                    return ['success' => false, 'error' => 'Error al guardar administrador'];
                }
            } elseif (strpos($errorMsg, 'WEAK_PASSWORD') !== false) {
                return ['success' => false, 'error' => 'La contraseña es muy débil'];
            } elseif (strpos($errorMsg, 'INVALID_EMAIL') !== false) {
                return ['success' => false, 'error' => 'Email inválido'];
            } else {
                return ['success' => false, 'error' => 'Error al crear usuario: ' . $errorMsg];
            }
        }
        
        // Guardar en la base de datos
        $adminData = [
            'email' => $email,
            'firebase_uid' => $result['data']['localId'],
            'created_at' => date('Y-m-d H:i:s'),
            'status' => 'active'
        ];
        
        $saveResult = $this->db->create('/admins', $adminData);
        
        if ($saveResult) {
            return ['success' => true, 'message' => 'Administrador creado exitosamente'];
        } else {
            return ['success' => false, 'error' => 'Error al guardar administrador'];
        }
    }
    
    // Eliminar administrador
    public function removeAdmin($adminId, $currentUserEmail) {
        // No permitir que se elimine a sí mismo
        $admin = $this->getAdminById($adminId);
        
        if (!$admin) {
            return ['success' => false, 'error' => 'Administrador no encontrado'];
        }
        
        if ($admin['email'] === $currentUserEmail) {
            return ['success' => false, 'error' => 'No puedes eliminarte a ti mismo'];
        }
        
        // Verificar que quede al menos un admin
        $admins = $this->getAllAdmins();
        $activeAdmins = array_filter($admins, function($a) {
            return $a['status'] === 'active';
        });
        
        if (count($activeAdmins) <= 1) {
            return ['success' => false, 'error' => 'Debe haber al menos un administrador activo'];
        }
        
        $result = $this->db->delete('/admins/' . $adminId);
        
        if ($result !== false) {
            return ['success' => true, 'message' => 'Administrador eliminado'];
        } else {
            return ['success' => false, 'error' => 'Error al eliminar'];
        }
    }
    
    // Obtener admin por ID
    private function getAdminById($id) {
        $result = $this->db->get('/admins/' . $id);
        if ($result) {
            $result['id'] = $id;
        }
        return $result;
    }
}
