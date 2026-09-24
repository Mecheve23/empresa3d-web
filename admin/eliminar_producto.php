<?php
include '../includes/db.php';

// Iniciamos sesión para verificar que solo el admin pueda borrar
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // 1. Buscamos el nombre de la imagen para borrarla de la carpeta img/productos/
    $query_img = "SELECT imagen FROM productos WHERE id = $id";
    $res_img = mysqli_query($conexion, $query_img);
    if ($row = mysqli_fetch_assoc($res_img)) {
        $ruta_imagen = "../img/productos/" . $row['imagen'];
        if (file_exists($ruta_imagen) && !empty($row['imagen'])) {
            unlink($ruta_imagen); // Borra el archivo físico
        }
    }

    // 2. Borramos el registro de la tabla
    $query_delete = "DELETE FROM productos WHERE id = $id";
    if (mysqli_query($conexion, $query_delete)) {
        echo "<script>alert('¡Producto eliminado con éxito!'); window.location.href='panel.php';</script>";
    } else {
        echo "<script>alert('Error al intentar eliminar el producto.'); window.location.href='panel.php';</script>";
    }
} else {
    header("Location: panel.php");
    exit();
}
?>