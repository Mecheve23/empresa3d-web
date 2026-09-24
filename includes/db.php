<?php
$host = "localhost";
$usuario = "root";
$clave = "";
$bd = "db_empresa3d"; // Se corrigió el nombre exacto según tu phpMyAdmin

$conexion = mysqli_connect($host, $usuario, $clave, $bd);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

mysqli_set_charset($conexion, "utf8");
?>