<?php
include '../includes/db.php';
session_start();

// Seguridad básica
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM cotizaciones WHERE id = $id";
    
    if (mysqli_query($conexion, $query)) {
        echo "<script>alert('Cotización eliminada'); window.location='panel.php';</script>";
    } else {
        echo "Error al eliminar: " . mysqli_error($conexion);
    }
}
?>