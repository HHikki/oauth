<?php
// Configuración de Firebase
// En producción (Railway), usa variables de entorno
// En desarrollo local, usa los valores directos

// Lista de admins (emails)
define('ADMIN_EMAILS', [
    'hikkidev27@gmail.com' 
]);

return [
    'apiKey' => getenv('FIREBASE_API_KEY') ?: "AIzaSyD0A2GlfjhYQNR1EbYnQU6zGmL1ULtsyvE",
    'authDomain' => getenv('FIREBASE_AUTH_DOMAIN') ?: "auth-4edc2.firebaseapp.com",
    'databaseURL' => getenv('FIREBASE_DATABASE_URL') ?: "https://auth-4edc2-default-rtdb.firebaseio.com",
    'projectId' => getenv('FIREBASE_PROJECT_ID') ?: "auth-4edc2",
    'storageBucket' => getenv('FIREBASE_STORAGE_BUCKET') ?: "auth-4edc2.firebasestorage.app",
    'messagingSenderId' => getenv('FIREBASE_MESSAGING_SENDER_ID') ?: "754144923326",
    'appId' => getenv('FIREBASE_APP_ID') ?: "1:754144923326:web:5e156ff6c391c74a5f98ef"
];
