<?php
// Clase para manejar la conexión con Firebase Realtime Database

class FirebaseDB {
    private $databaseURL;
    private $apiKey;
    
    public function __construct($config) {
        $this->databaseURL = $config['databaseURL'];
        $this->apiKey = $config['apiKey'];
    }
    
    // Método genérico para hacer peticiones a Firebase
    private function makeRequest($endpoint, $method = 'GET', $data = null) {
        $url = $this->databaseURL . $endpoint . '.json';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        } elseif ($method === 'PUT') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        } elseif ($method === 'PATCH') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        } elseif ($method === 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($response, true);
    }
    
    // Crear un nuevo registro
    public function create($path, $data) {
        return $this->makeRequest($path, 'POST', $data);
    }
    
    // Obtener datos
    public function get($path) {
        return $this->makeRequest($path, 'GET');
    }
    
    // Actualizar registro completo
    public function update($path, $data) {
        return $this->makeRequest($path, 'PUT', $data);
    }
    
    // Actualizar campos específicos
    public function patch($path, $data) {
        return $this->makeRequest($path, 'PATCH', $data);
    }
    
    // Eliminar registro
    public function delete($path) {
        return $this->makeRequest($path, 'DELETE');
    }
}
