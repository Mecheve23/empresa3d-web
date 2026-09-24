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
    $usuario_logueado = $_SESSION['admin'];
    $nuevo_nombre = mysqli_real_escape_string($conexion, trim($_POST['nuevo_nombre']));

    if (!empty($nuevo_nombre)) {
        // Revisamos qué columnas existen en la tabla 'usuarios'
        $columnas = [];
        $res_cols = mysqli_query($conexion, "SHOW COLUMNS FROM usuarios");
        if ($res_cols) {
            while ($col = mysqli_fetch_assoc($res_cols)) {
                $columnas[] = $col['Field'];
            }
        }

        // Determinamos la columna correcta a actualizar
        if (in_array('nombre_completo', $columnas)) {
            $campo_actualizar = 'nombre_completo';
        } elseif (in_array('nombre', $columnas)) {
            $campo_actualizar = 'nombre';
        } else {
            // Si ninguna existe, la agregamos automáticamente a la tabla
            mysqli_query($conexion, "ALTER TABLE usuarios ADD nombre_completo VARCHAR(255) NULL");
            $campo_actualizar = 'nombre_completo';
        }

        $query = "UPDATE usuarios SET $campo_actualizar = '$nuevo_nombre' WHERE usuario = '$usuario_logueado'";
        
        if (mysqli_query($conexion, $query)) {
            echo "<script>alert('¡Nombre actualizado con éxito!'); window.location.href='panel.php';</script>";
        } else {
            echo "Error al actualizar: " . mysqli_error($conexion);
        }
    } else {
        echo "<script>alert('El nombre no puede estar vacío.'); window.location.href='panel.php';</script>";
    }
}
?>