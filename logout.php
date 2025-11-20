<?php
require_once 'includes/session.php';

// Destruir sesión
destroyUserSession();

// Redirigir al login
header('Location: login.php');
exit();
