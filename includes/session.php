<?php
// Manejo de sesiones
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['user_email']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

function setUserSession($userId, $email, $token, $displayName = null, $photoURL = null) {
    $_SESSION['user_id'] = $userId;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_token'] = $token;
    
    if ($displayName) {
        $_SESSION['user_display_name'] = $displayName;
    }
    
    if ($photoURL) {
        $_SESSION['user_photo_url'] = $photoURL;
    }
}

function destroyUserSession() {
    session_unset();
    session_destroy();
}

function getUserEmail() {
    return isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';
}

function getUserId() {
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
}

function getUserDisplayName() {
    return isset($_SESSION['user_display_name']) ? $_SESSION['user_display_name'] : getUserEmail();
}

function getUserPhotoURL() {
    return isset($_SESSION['user_photo_url']) ? $_SESSION['user_photo_url'] : null;
}
