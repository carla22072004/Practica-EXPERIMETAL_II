<?php
// dashboard.php - Panel principal (ruta protegida)
require 'auth.php';
require 'db.php';

$productos = $conn->query("SELECT * FROM productos ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Productos</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f0f2f5; }
        header {
            background: #2c3e50;
            color: #fff;
            padding: 14px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header h1 { font-size: 20px; }
        header .user-info { font-size: 14px; }
        header a { color: #e74c3c; text-decoration: none; font-weight: bold; margin-left: 16px; }
        header a:hover { text-decoration: underline; }
        .container { max-width: 900px; margin: 30px auto; padding: 0 20px; }
        .btn {
            display: inline-block;
            padding: 9px 18px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }
        .btn-primary { background: #4a90e2; color: #fff; }
        .btn-primary:hover { background: #357abd; }
        .btn-edit    { background: #f39c12; color: #fff; font-size: 13px; padding: 5px 12px; }
        .btn-edit:hover { background: #d68910; }
        .btn-delete  { background: #e74c3c; color: #fff; font-size: 13px; padding: 5px 12px; }
        .btn-delete:hover { background: #c0392b; }
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 1px 8px rgba(0,0,0,0.08);
        }
        thead { background: #2c3e50; color: #fff; }
        th, td { padding: 12px 16px; text-align: left; font-size: 14px; }
        tbody tr:nth-child(even) { background: #f9f9f9; }
        tbody tr:hover { background: #eef4fb; }
        .empty { text-align: center; color: #888; padding: 40px; font-size: 15px; }
        .msg-success { background: #eafaf1; color: #1e8449; padding: 10px 16px;
                       border-radius: 4px; margin-bottom: 16px; font-size: 14px; }
    </style>
</head>
<body>

<header>
    <h1>📦 Gestión de Productos</h1>
    <div class="user-info">
        Hola, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>
        <a href="logout.php">Cerrar sesión</a>
    </div>
</header>

<div class="container">

    <?php if (isset($_GET['msg'])): ?>
        <div class="msg-success">
            <?php
            $msg = $_GET['msg'];
            if ($msg === 'created')  echo "✅ Producto creado exitosamente.";
            elseif ($msg === 'updated') echo "✅ Producto actualizado.";
            elseif ($msg === 'deleted') echo "✅ Producto eliminado.";
            ?>
        </div>
    <?php endif; ?>

    <div class="top-bar">
        <h2>Lista de Productos</h2>
        <a href="create.php" class="btn btn-primary">+ Nuevo Producto</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($productos && $productos->num_rows > 0): ?>
                <?php while ($p = $productos->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($p['id']) ?></td>
                    <td><?= htmlspecialchars($p['nombre']) ?></td>
                    <td>$<?= htmlspecialchars(number_format($p['precio'], 2)) ?></td>
                    <td>
                        <a href="update.php?id=<?= $p['id'] ?>" class="btn btn-edit">✏️ Editar</a>
                        <a href="delete.php?id=<?= $p['id'] ?>" class="btn btn-delete"
                           onclick="return confirm('¿Eliminar este producto?')">🗑️ Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4" class="empty">No hay productos registrados aún.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
