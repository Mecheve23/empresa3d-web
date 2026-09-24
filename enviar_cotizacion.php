<?php
include 'includes/db.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $mensaje = $_POST['mensaje'];

    $sql = "INSERT INTO cotizaciones (nombre, email, mensaje) VALUES ('$nombre', '$email', '$mensaje')";
    
    if(mysqli_query($conexion, $sql)){
        echo "<script>alert('Tu solicitud ha sido enviada. Te contactaremos pronto.'); window.location='index.php';</script>";
    } else {
        echo "Error al enviar: " . mysqli_error($conexion);
    }
}
?>