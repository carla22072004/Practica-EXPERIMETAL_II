<?php
// db.php - Conexión a la base de datos

define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // Cambia si tienes otro usuario en XAMPP
define('DB_PASS', '');           // Vacío por defecto en XAMPP
define('DB_NAME', 'crud_auth');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>
