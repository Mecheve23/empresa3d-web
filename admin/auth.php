<?php
include '../includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login_input = trim(mysqli_real_escape_string($conexion, $_POST['usuario']));
    $password    = trim($_POST['password']);

    // Encriptamos la clave ingresada en MD5 para comparar con la BD
    $password_md5 = md5($password);

    // Consulta que busca por usuario O por correo, comparando la clave encriptada o en texto plano
    $query = "SELECT * FROM usuarios WHERE (usuario = '$login_input' OR email = '$login_input') AND (password = '$password_md5' OR password = '$password')";
    $resultado = mysqli_query($conexion, $query);

    if (mysqli_num_rows($resultado) == 1) {
        $usuario_data = mysqli_fetch_assoc($resultado);
        
        $_SESSION['usuario'] = $usuario_data['usuario']; 
        $_SESSION['admin']   = $usuario_data['usuario']; 
        
        header("Location: panel.php"); 
        exit();
    } else {
        header("Location: login.php?error=datos_incorrectos");
        exit();
    }
}
?>