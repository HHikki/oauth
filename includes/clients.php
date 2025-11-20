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
        $clientData = [
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'telefono' => $data['telefono'],
            'direccion' => $data['direccion'],
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->create('/clients', $clientData);
    }
    
    // Actualizar cliente
    public function updateClient($id, $data) {
        $clientData = [
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'telefono' => $data['telefono'],
            'direccion' => $data['direccion'],
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
