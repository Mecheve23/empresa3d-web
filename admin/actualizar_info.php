<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $banner_titulo    = mysqli_real_escape_string($conexion, $_POST['banner_titulo']);
    $banner_subtitulo = mysqli_real_escape_string($conexion, $_POST['banner_subtitulo']);
    $quienes_somos     = mysqli_real_escape_string($conexion, $_POST['quienes_somos']);
    $historia          = mysqli_real_escape_string($conexion, $_POST['historia']);
    $politica_calidad  = mysqli_real_escape_string($conexion, $_POST['politica_calidad']);
    $mision            = mysqli_real_escape_string($conexion, $_POST['mision']);
    $vision            = mysqli_real_escape_string($conexion, $_POST['vision']);
    $google_maps       = mysqli_real_escape_string($conexion, $_POST['google_maps']);

    // Procesar nuevo logo si fue subido
    $sql_logo = "";
    if (isset($_FILES['nuevo_logo']) && $_FILES['nuevo_logo']['error'] === 0) {
        $nombre_foto = time() . '_' . $_FILES['nuevo_logo']['name'];
        $ruta_destino = '../img/' . $nombre_foto;
        if (move_uploaded_file($_FILES['nuevo_logo']['tmp_name'], $ruta_destino)) {
            $sql_logo = ", logo = '$nombre_foto'";
        }
    }

    $query = "UPDATE empresa_info SET 
                banner_titulo = '$banner_titulo',
                banner_subtitulo = '$banner_subtitulo',
                quienes_somos = '$quienes_somos',
                historia = '$historia',
                politica_calidad = '$politica_calidad',
                mision = '$mision',
                vision = '$vision',
                google_maps = '$google_maps'
                $sql_logo
              WHERE id = 1";

    mysqli_query($conexion, $query);
    header("Location: panel.php");
    exit();
}
?>