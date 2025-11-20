<?php
// Clase para manejar la autenticación con Firebase Authentication

class FirebaseAuth {
    private $apiKey;
    private $authUrl = 'https://identitytoolkit.googleapis.com/v1/accounts:';
    
    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }
    
    // Registrar nuevo usuario con email y contraseña
    public function signUp($email, $password) {
        $endpoint = $this->authUrl . 'signUp?key=' . $this->apiKey;
        
        $data = [
            'email' => $email,
            'password' => $password,
            'returnSecureToken' => true
        ];
        
        return $this->makeAuthRequest($endpoint, $data);
    }
    
    // Iniciar sesión con email y contraseña
    public function signIn($email, $password) {
        $endpoint = $this->authUrl . 'signInWithPassword?key=' . $this->apiKey;
        
        $data = [
            'email' => $email,
            'password' => $password,
            'returnSecureToken' => true
        ];
        
        return $this->makeAuthRequest($endpoint, $data);
    }
    
    // Verificar token de usuario
    public function verifyIdToken($idToken) {
        $endpoint = $this->authUrl . 'lookup?key=' . $this->apiKey;
        
        $data = [
            'idToken' => $idToken
        ];
        
        return $this->makeAuthRequest($endpoint, $data);
    }
    
    // Método para hacer peticiones de autenticación
    private function makeAuthRequest($url, $data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        $result = json_decode($response, true);
        
        if ($httpCode !== 200) {
            return [
                'success' => false,
                'error' => isset($result['error']['message']) ? $result['error']['message'] : 'Error desconocido'
            ];
        }
        
        return [
            'success' => true,
            'data' => $result
        ];
    }
}
