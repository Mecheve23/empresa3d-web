<?php
include '../includes/db.php';
session_start();

// Seguridad: Solo si es admin
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 1. Buscamos el nombre de la imagen para borrar el archivo físico
    $consulta = "SELECT imagen FROM proyectos WHERE id = $id";
    $resultado = mysqli_query($conexion, $consulta);
    $proyecto = mysqli_fetch_assoc($resultado);
    $ruta_imagen = "../img/proyectos/" . $proyecto['imagen'];

    if (file_exists($ruta_imagen)) {
        unlink($ruta_imagen); // Borra la foto de la carpeta
    }

    // 2. Borramos el registro de la base de datos
    $query = "DELETE FROM proyectos WHERE id = $id";
    if (mysqli_query($conexion, $query)) {
        echo "<script>alert('Proyecto eliminado correctamente'); window.location='panel.php';</script>";
    }
}
?>
