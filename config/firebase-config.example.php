<?php
// Configuración de Firebase - EJEMPLO
// Copia este archivo a firebase-config.php y configura tus credenciales

return [
    'apiKey' => getenv('FIREBASE_API_KEY') ?: "TU_API_KEY",
    'authDomain' => getenv('FIREBASE_AUTH_DOMAIN') ?: "tu-proyecto.firebaseapp.com",
    'databaseURL' => getenv('FIREBASE_DATABASE_URL') ?: "https://tu-proyecto-default-rtdb.firebaseio.com",
    'projectId' => getenv('FIREBASE_PROJECT_ID') ?: "tu-proyecto",
    'storageBucket' => getenv('FIREBASE_STORAGE_BUCKET') ?: "tu-proyecto.appspot.com",
    'messagingSenderId' => getenv('FIREBASE_MESSAGING_SENDER_ID') ?: "123456789",
    'appId' => getenv('FIREBASE_APP_ID') ?: "1:123456789:web:abc123def456"
];
