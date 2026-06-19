<?php
// update.php - Editar producto existente
require 'auth.php';
require 'db.php';

$id    = intval($_GET['id'] ?? $_POST['id'] ?? 0);
$error = '';

if ($id <= 0) {
    header("Location: dashboard.php");
    exit();
}

// Obtener datos actuales
$stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    header("Location: dashboard.php");
    exit();
}

$producto = $result->fetch_assoc();
$stmt->close();

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $precio = $_POST['precio'] ?? '';

    if (empty($nombre) || $precio === '') {
        $error = "Todos los campos son obligatorios.";
    } elseif (!is_numeric($precio) || $precio < 0) {
        $error = "El precio debe ser un número válido.";
    } else {
        $precio = floatval($precio);

        $upd = $conn->prepare("UPDATE productos SET nombre = ?, precio = ? WHERE id = ?");
        $upd->bind_param("sdi", $nombre, $precio, $id);

        if ($upd->execute()) {
            $upd->close();
            header("Location: dashboard.php?msg=updated");
            exit();
        } else {
            $error = "Error al actualizar el producto.";
        }
        $upd->close();
    }

    // Reflejar valores ingresados en caso de error
    $producto['nombre'] = $nombre;
    $producto['precio'] = $precio;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
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
        button { padding: 10px 20px; background: #f39c12; color: #fff;
                 border: none; border-radius: 4px; font-size: 15px; cursor: pointer; }
        button:hover { background: #d68910; }
        a.back { padding: 10px 20px; background: #95a5a6; color: #fff;
                 border-radius: 4px; text-decoration: none; font-size: 15px; }
        .error { background: #fdecea; color: #c0392b; padding: 10px;
                 border-radius: 4px; margin-bottom: 16px; font-size: 14px; }
    </style>
</head>
<body>
<header><h1>📦 Gestión de Productos</h1></header>
<div class="container">
    <div class="card">
        <h2>✏️ Editar Producto #<?= htmlspecialchars($id) ?></h2>
        <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

        <form method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">

            <label>Nombre del Producto</label>
            <input type="text" name="nombre"
                   value="<?= htmlspecialchars($producto['nombre']) ?>" required autofocus>

            <label>Precio ($)</label>
            <input type="number" name="precio" step="0.01" min="0"
                   value="<?= htmlspecialchars($producto['precio']) ?>" required>

            <div class="btns">
                <button type="submit">Actualizar</button>
                <a href="dashboard.php" class="back">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
