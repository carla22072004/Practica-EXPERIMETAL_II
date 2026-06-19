<?php
// register.php - Registro de usuarios
session_start();
require 'db.php';

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm']  ?? '';

    // Validaciones básicas
    if (empty($username) || empty($password) || empty($confirm)) {
        $error = "Todos los campos son obligatorios.";
    } elseif (strlen($username) < 3) {
        $error = "El usuario debe tener al menos 3 caracteres.";
    } elseif (strlen($password) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres.";
    } elseif ($password !== $confirm) {
        $error = "Las contraseñas no coinciden.";
    } else {
        // Verificar si el usuario ya existe (prepared statement)
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Ese nombre de usuario ya está en uso.";
            $stmt->close(); // Cerramos aquí si el usuario ya existe
        } else {
            $stmt->close(); // Cerramos aquí para liberar memoria antes del insert

            // Hashear contraseña y guardar (Buenas prácticas OWASP)
            $hash = password_hash($password, PASSWORD_BCRYPT);

            $insert = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $insert->bind_param("ss", $username, $hash);

            if ($insert->execute()) {
                $success = "¡Cuenta creada exitosamente! Ya puedes iniciar sesión.";
            } else {
                $error = "Error al registrar. Intenta de nuevo.";
            }
            $insert->close(); // Cerramos la sentencia de inserción de forma segura
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f0f2f5;
               display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .card { background: #fff; padding: 40px; border-radius: 8px;
                box-shadow: 0 2px 12px rgba(0,0,0,0.1); width: 100%; max-width: 380px; }
        h2 { text-align: center; margin-bottom: 24px; color: #333; }
        label { display: block; margin-bottom: 6px; font-size: 14px; color: #555; }
        input[type="text"], input[type="password"] {
            width: 100%; padding: 10px 12px; border: 1px solid #ccc;
            border-radius: 4px; font-size: 15px; margin-bottom: 16px; }
        button { width: 100%; padding: 11px; background: #27ae60; color: #fff;
                 border: none; border-radius: 4px; font-size: 16px; cursor: pointer; }
        button:hover { background: #1e8449; }
        .links { text-align: center; margin-top: 16px; font-size: 14px; }
        .links a { color: #4a90e2; text-decoration: none; }
        .error   { background: #fdecea; color: #c0392b; padding: 10px;
                   border-radius: 4px; margin-bottom: 16px; font-size: 14px; }
        .success { background: #eafaf1; color: #1e8449; padding: 10px;
                   border-radius: 4px; margin-bottom: 16px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="card">
        <h2>📝 Crear Cuenta</h2>

        <?php if ($error):   ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <?php if ($success): ?><div class="success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

        <form method="POST">
            <label>Usuario</label>
            <input type="text" name="username" placeholder="Mínimo 3 caracteres" required>

            <label>Contraseña</label>
            <input type="password" name="password" placeholder="Mínimo 6 caracteres" required>

            <label>Confirmar Contraseña</label>
            <input type="password" name="confirm" placeholder="Repite la contraseña" required>

            <button type="submit">Registrarse</button>
        </form>

        <div class="links">
            ¿Ya tienes cuenta? <a href="login.html">Inicia sesión</a>
        </div>
    </div>
</body>
</html>
