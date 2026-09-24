<?php
include '../includes/db.php';
session_start();

if (isset($_SESSION['admin'])) {
    $usuario_logueado = $_SESSION['admin'];
    
    // Primero consultamos si tiene una foto actual para no dejar basura en el servidor
    $res = mysqli_query($conexion, "SELECT foto_perfil FROM usuarios WHERE usuario = '$usuario_logueado'");
    $datos = mysqli_fetch_assoc($res);
    $foto_actual = $datos['foto_perfil'];
    
    // Si no es la de por defecto, la eliminamos físicamente de la carpeta
    if ($foto_actual != 'usuario.png' && file_exists("../img/perfiles/" . $foto_actual)) {
        unlink("../img/perfiles/" . $foto_actual);
    }
    
    // Actualizamos la base de datos para poner la silueta por defecto
    $query = "UPDATE usuarios SET foto_perfil = 'usuario.png' WHERE usuario = '$usuario_logueado'";
    
    if (mysqli_query($conexion, $query)) {
        $_SESSION['foto'] = 'usuario.png';
        header("Location: panel.php?success=foto_eliminada");
        exit();
    }
}
header("Location: panel.php");
?>