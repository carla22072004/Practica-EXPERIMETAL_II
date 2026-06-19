<?php
// auth.php - Filtro de protección de rutas
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificamos usando estrictamente 'user_id' que configuramos en el login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
?>