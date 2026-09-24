<?php
include '../includes/db.php';
session_start();

if (!isset($_GET['token']) && !isset($_SESSION['usuario_recuperando'])) {
    header("Location: recuperar.php");
    exit();
}

$error = "";

if (isset($_GET['token'])) {
    $token_url = mysqli_real_escape_string($conexion, $_GET['token']);
    $buscar_token = "SELECT usuario FROM usuarios WHERE token_recuperacion = '$token_url'";
    $res_token = mysqli_query($conexion, $buscar_token);

    if (mysqli_num_rows($res_token) == 1) {
        $row_user = mysqli_fetch_assoc($res_token);
        $_SESSION['usuario_recuperando'] = $row_user['usuario'];
    } else {
        header("Location: recuperar.php?error=token_invalido");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pass_nueva = trim($_POST['pass_nueva']);
    $pass_conf = trim($_POST['pass_confirmar']);

    if (empty($pass_nueva)) {
        $error = "La contraseña no puede estar vacía.";
    } elseif ($pass_nueva !== $pass_conf) {
        $error = "Las contraseñas no coinciden.";
    } else {
        $usuario = $_SESSION['usuario_recuperando'];
        $pass_final = mysqli_real_escape_string($conexion, $pass_nueva);

        // Guardamos la contraseña y limpiamos el token de la BD
        $query = "UPDATE usuarios SET password = '$pass_final', token_recuperacion = NULL WHERE usuario = '$usuario'";
        
        if (mysqli_query($conexion, $query)) {
            unset($_SESSION['usuario_recuperando']);
            header("Location: login.php?status=cambiada");
            exit();
        } else {
            $error = "Error al actualizar las credenciales en la base de datos.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña - Diseña y Fabrica</title>
</head>
<body style="background-color: #f4f7f6; font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0;">

    <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 100%; max-width: 400px; box-sizing: border-box;">
        <h2 style="color: #28a745; text-align: center; margin-top: 0; margin-bottom: 25px;">Nueva Contraseña</h2>
        
        <?php if(!empty($error)): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px; font-size: 0.85rem; text-align: center;"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="restablecer.php" method="POST">
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-size: 0.9rem; color: #555; margin-bottom: 5px;">Escribe la nueva contraseña</label>
                <input type="password" name="pass_nueva" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 0.9rem; color: #555; margin-bottom: 5px;">Confirma la nueva contraseña</label>
                <input type="password" name="pass_confirmar" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;">
            </div>

            <button type="submit" style="width: 100%; padding: 12px; background-color: #28a745; color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 1rem;">
                Cambiar Contraseña
            </button>
        </form>
    </div>

</body>
</html>