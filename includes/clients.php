<?php
// Funciones para manejar clientes

require_once __DIR__ . '/../config/firebase-config.php';
require_once __DIR__ . '/../config/database.php';

class ClientsManager {
    private $db;
    
    public function __construct() {
        $config = require __DIR__ . '/../config/firebase-config.php';
        $this->db = new FirebaseDB($config);
    }
    
    // Obtener todos los clientes
    public function getAllClients() {
        $result = $this->db->get('/clients');
        
        if (!$result) {
            return [];
        }
        
        $clients = [];
        foreach ($result as $id => $client) {
            $client['id'] = $id;
            $clients[] = $client;
        }
        
        return $clients;
    }
    
    // Obtener un cliente por ID
    public function getClientById($id) {
        $result = $this->db->get('/clients/' . $id);
        if ($result) {
            $result['id'] = $id;
        }
        return $result;
    }
    
    // Crear nuevo cliente
    public function createClient($data) {
        // Validar y sanitizar datos
        if (empty($data['nombre']) || empty($data['email']) || empty($data['telefono'])) {
            return false;
        }
        
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        
        $clientData = [
            'nombre' => htmlspecialchars(trim($data['nombre']), ENT_QUOTES, 'UTF-8'),
            'email' => filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL),
            'telefono' => htmlspecialchars(trim($data['telefono']), ENT_QUOTES, 'UTF-8'),
            'direccion' => htmlspecialchars(trim($data['direccion'] ?? ''), ENT_QUOTES, 'UTF-8'),
            'empresa' => htmlspecialchars(trim($data['empresa'] ?? ''), ENT_QUOTES, 'UTF-8'),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->create('/clients', $clientData);
    }
    
    // Actualizar cliente
    public function updateClient($id, $data) {
        // Validar ID
        if (empty($id)) {
            return false;
        }
        
        // Validar y sanitizar datos
        if (empty($data['nombre']) || empty($data['email']) || empty($data['telefono'])) {
            return false;
        }
        
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        
        $clientData = [
            'nombre' => htmlspecialchars(trim($data['nombre']), ENT_QUOTES, 'UTF-8'),
            'email' => filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL),
            'telefono' => htmlspecialchars(trim($data['telefono']), ENT_QUOTES, 'UTF-8'),
            'direccion' => htmlspecialchars(trim($data['direccion'] ?? ''), ENT_QUOTES, 'UTF-8'),
            'empresa' => htmlspecialchars(trim($data['empresa'] ?? ''), ENT_QUOTES, 'UTF-8'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->patch('/clients/' . $id, $clientData);
    }
    
    // Eliminar cliente
    public function deleteClient($id) {
        return $this->db->delete('/clients/' . $id);
    }
    
    // Contar clientes
    public function countClients() {
        $clients = $this->getAllClients();
        return count($clients);
    }
}
