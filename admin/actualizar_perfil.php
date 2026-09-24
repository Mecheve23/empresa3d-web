<?php
include '../includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['foto'])) {
    $foto = $_FILES['foto'];
    
    // Si no existe la carpeta, la creamos por código por si acaso
    if (!file_exists("../img/perfiles")) {
        mkdir("../img/perfiles", 0777, true);
    }

    $nombre_archivo = time() . "_" . preg_replace("/[^A-Za-z0-9.]/", "", $foto['name']);
    $ruta_destino = "../img/perfiles/" . $nombre_archivo;

    if (move_uploaded_file($foto['tmp_name'], $ruta_destino)) {
        $usuario = $_SESSION['admin'];
        $query = "UPDATE usuarios SET foto_perfil = '$nombre_archivo' WHERE usuario = '$usuario'";
        
        if (mysqli_query($conexion, $query)) {
            $_SESSION['foto'] = $nombre_archivo;
            header("Location: panel.php?success=foto_actualizada");
            exit();
        } else {
            echo "Error en la base de datos: " . mysqli_error($conexion);
        }
    } else {
        echo "Error: No se pudo subir el archivo. Verifica que la carpeta 'img/perfiles' exista y tenga permisos de escritura.";
    }
} else {
    echo "No se recibió ninguna imagen.";
}
?>