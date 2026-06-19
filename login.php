<?php
// login.php - Procesa el formulario de login
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.html");
    exit();
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    header("Location: login.html?error=empty");
    exit();
}

// Buscar usuario con prepared statement
$stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Verificar contraseña con password_verify
    if (password_verify($password, $user['password'])) {
        // Regenerar ID de sesión para prevenir session fixation (Excelente control OWASP)
        session_regenerate_id(true);

        // Guardamos los datos del usuario en la sesión
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['username']  = $user['username'];

        // CONTROL OWASP: Generamos el Token CSRF Maestro aquí, justo al iniciar sesión con éxito
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        header("Location: dashboard.php");
        exit();
    }
}

$stmt->close();
header("Location: login.html?error=invalid");
exit();
?>