<?php
// delete.php - Eliminar producto
require 'auth.php';
require 'db.php';

$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: dashboard.php");
    exit();
}

// Verificar que el producto existe antes de eliminar
$check = $conn->prepare("SELECT id FROM productos WHERE id = ?");
$check->bind_param("i", $id);
$check->execute();
$check->store_result();

if ($check->num_rows === 0) {
    $check->close();
    header("Location: dashboard.php");
    exit();
}
$check->close();

// Eliminar con prepared statement
$stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

header("Location: dashboard.php?msg=deleted");
exit();
?>
