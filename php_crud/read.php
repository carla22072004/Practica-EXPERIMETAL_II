<?php
// read.php - Ver detalle de un producto
require 'auth.php';
require 'db.php';

$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: dashboard.php");
    exit();
}

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
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Producto</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f0f2f5; }
        header { background: #2c3e50; color: #fff; padding: 14px 30px; }
        header h1 { font-size: 20px; }
        .container { max-width: 500px; margin: 40px auto; padding: 0 20px; }
        .card { background: #fff; padding: 30px; border-radius: 8px;
                box-shadow: 0 1px 8px rgba(0,0,0,0.08); }
        h2 { margin-bottom: 20px; color: #333; }
        .field { margin-bottom: 16px; }
        .field label { font-size: 12px; text-transform: uppercase; color: #888; display: block; }
        .field span { font-size: 18px; color: #222; }
        .btns { display: flex; gap: 10px; margin-top: 24px; }
        a.btn { padding: 9px 18px; border-radius: 4px; text-decoration: none; font-size: 14px; }
        .btn-edit  { background: #f39c12; color: #fff; }
        .btn-back  { background: #95a5a6; color: #fff; }
    </style>
</head>
<body>
<header><h1>📦 Gestión de Productos</h1></header>
<div class="container">
    <div class="card">
        <h2>🔍 Detalle del Producto</h2>

        <div class="field">
            <label>ID</label>
            <span><?= htmlspecialchars($producto['id']) ?></span>
        </div>
        <div class="field">
            <label>Nombre</label>
            <span><?= htmlspecialchars($producto['nombre']) ?></span>
        </div>
        <div class="field">
            <label>Precio</label>
            <span>$<?= htmlspecialchars(number_format($producto['precio'], 2)) ?></span>
        </div>

        <div class="btns">
            <a href="update.php?id=<?= $producto['id'] ?>" class="btn btn-edit">✏️ Editar</a>
            <a href="dashboard.php" class="btn btn-back">← Volver</a>
        </div>
    </div>
</div>
</body>
</html>
