<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = mysqli_real_escape_string($conexion, $_POST['titulo']);
    $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);

    // 1. Guardamos la foto principal (la primera del array)
    $foto_principal = "";
    if (isset($_FILES['imagenes']['name'][0]) && !empty($_FILES['imagenes']['name'][0])) {
        $foto_principal = time() . "_0_" . basename($_FILES['imagenes']['name'][0]);
        move_uploaded_file($_FILES['imagenes']['tmp_name'][0], "../img/proyectos/" . $foto_principal);
    }

    // Insertar proyecto
    $query = "INSERT INTO proyectos (titulo, descripcion, imagen) VALUES ('$titulo', '$descripcion', '$foto_principal')";
    if (mysqli_query($conexion, $query)) {
        $proyecto_id = mysqli_insert_id($conexion);

        // 2. Guardamos TODAS las imágenes en la nueva tabla deslizable
        if (isset($_FILES['imagenes'])) {
            $total_fotos = count($_FILES['imagenes']['name']);
            for ($i = 0; $i < $total_fotos; $i++) {
                if (!empty($_FILES['imagenes']['name'][$i])) {
                    $nombre_foto = time() . "_" . $i . "_" . basename($_FILES['imagenes']['name'][$i]);
                    $ruta_destino = "../img/proyectos/" . $nombre_foto;

                    if ($i === 0 && !empty($foto_principal)) {
                        // La primera ya se subió arriba
                        $nombre_foto = $foto_principal;
                    } else {
                        move_uploaded_file($_FILES['imagenes']['tmp_name'][$i], $ruta_destino);
                    }

                    $query_img = "INSERT INTO imagenes_proyectos (proyecto_id, imagen) VALUES ('$proyecto_id', '$nombre_foto')";
                    mysqli_query($conexion, $query_img);
                }
            }
        }

        echo "<script>alert('¡Proyecto publicado con éxito con su galería de imágenes!'); window.location.href='panel.php';</script>";
    } else {
        echo "Error al guardar: " . mysqli_error($conexion);
    }
}
?>