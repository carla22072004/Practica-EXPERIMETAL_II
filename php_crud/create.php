<?php
// create.php - Crear nuevo producto
require 'auth.php';
require 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. CONTROL OWASP: Validación estricta del Token CSRF antes de procesar datos
    $token_recibido = $_POST['csrf_token'] ?? '';
    if (empty($token_recibido) || $token_recibido !== ($_SESSION['csrf_token'] ?? '')) {
        die("Error de seguridad: Solicitud CSRF detectada e invalidada.");
    }

    $nombre = trim($_POST['nombre'] ?? '');
    $precio = $_POST['precio'] ?? '';

    // Validaciones de integridad en el Servidor
    if (empty($nombre) || $precio === '') {
        $error = "Todos los campos son obligatorios.";
    } elseif (!is_numeric($precio) || $precio < 0) {
        $error = "El precio debe ser un número válido mayor o igual a 0.";
    } else {
        $precio = floatval($precio);

        // 2. CONTROL OWASP: Sentencia preparada para mitigar inyección SQL
        $stmt = $conn->prepare("INSERT INTO productos (nombre, precio) VALUES (?, ?)");
        $stmt->bind_param("sd", $nombre, $precio);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: dashboard.php?msg=created");
            exit();
        } else {
            $error = "Error al guardar el producto.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Producto</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f0f2f5; }
        header { background: #2c3e50; color: #fff; padding: 14px 30px; }
        header h1 { font-size: 20px; }
        .container { max-width: 500px; margin: 40px auto; padding: 0 20px; }
        .card { background: #fff; padding: 30px; border-radius: 8px;
                box-shadow: 0 1px 8px rgba(0,0,0,0.08); }
        h2 { margin-bottom: 20px; color: #333; }
        label { display: block; margin-bottom: 6px; font-size: 14px; color: #555; }
        input[type="text"], input[type="number"] {
            width: 100%; padding: 10px 12px; border: 1px solid #ccc;
            border-radius: 4px; font-size: 15px; margin-bottom: 18px; }
        .btns { display: flex; gap: 10px; }
        button { padding: 10px 20px; background: #27ae60; color: #fff;
                 border: none; border-radius: 4px; font-size: 15px; cursor: pointer; }
        button:hover { background: #1e8449; }
        a.back { padding: 10px 20px; background: #95a5a6; color: #fff;
                 border-radius: 4px; text-decoration: none; font-size: 15px; }
        a.back:hover { background: #7f8c8d; }
        .error { background: #fdecea; color: #c0392b; padding: 10px;
                 border-radius: 4px; margin-bottom: 16px; font-size: 14px; }
    </style>
</head>
<body>
<header><h1>📦 Gestión de Productos</h1></header>
    <div class="container">
        <div class="card">
            <h2>➕ Nuevo Producto</h2>
            <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

                <label>Nombre del Producto</label>
                <input type="text" name="nombre"
                    value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>"
                    placeholder="Ej: Camiseta azul" required autofocus>

                <label>Precio ($)</label>
                <input type="number" name="precio" step="0.01" min="0"
                    value="<?= htmlspecialchars($_POST['precio'] ?? '') ?>"
                    placeholder="Ej: 29.99" required>

                <div class="btns">
                    <button type="submit">Guardar</button>
                    <a href="dashboard.php" class="back">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>