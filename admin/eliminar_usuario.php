<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Verificar si hay sesión activa de administrador
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

// 2. Verificar que se reciba un ID válido por GET
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_usuario = intval($_GET['id']);

    // 3. BLINDAJE DE SEGURIDAD: No permitir borrar al Admin Principal (ID 1 o usuario 'admin')
    $consulta_verificar = "SELECT usuario FROM usuarios WHERE id = $id_usuario";
    $resultado_verificar = mysqli_query($conexion, $consulta_verificar);
    
    if ($resultado_verificar && mysqli_num_rows($resultado_verificar) > 0) {
        $usuario_obj = mysqli_fetch_assoc($resultado_verificar);
        
        // Si no es el admin principal, procedemos a borrar
        if ($id_usuario !== 1 && strtolower($usuario_obj['usuario']) !== 'admin') {
            $query_delete = "DELETE FROM usuarios WHERE id = $id_usuario";
            mysqli_query($conexion, $query_delete);
        }
    }
}

// 4. Redirigir inmediatamente de vuelta al panel para ver los cambios
header("Location: panel.php");
exit();
?>