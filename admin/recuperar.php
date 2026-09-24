<?php
include '../includes/db.php';
session_start();

$error = "";
$mostrar_simulador = false;
$enlace_final = "";
$correo_destino = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email_ingresado = mysqli_real_escape_string($conexion, $_POST['email']);

    // Buscamos dinámicamente si existe el correo en la BD sin importar el nombre de usuario
    $query = "SELECT * FROM usuarios WHERE email = '$email_ingresado'";
    $resultado = mysqli_query($conexion, $query);

    if (mysqli_num_rows($resultado) == 1) {
        $usuario_data = mysqli_fetch_assoc($resultado);
        $usuario = $usuario_data['usuario'];

        // Generamos el token de seguridad y lo guardamos en MySQL
        $token = bin2hex(random_bytes(16));
        mysqli_query($conexion, "UPDATE usuarios SET token_recuperacion = '$token' WHERE email = '$email_ingresado'");

        // Guardamos el usuario en la sesión temporal
        $_SESSION['usuario_recuperando'] = $usuario;

        // Construimos el enlace formal
        $enlace_final = "restablecer.php?token=" . $token;
        $correo_destino = $email_ingresado;
        
        // Activamos el simulador en pantalla
        $mostrar_simulador = true;
    } else {
        $error = "El correo electrónico ingresado no coincide con ningún administrador registrado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Cuenta - Diseña y Fabrica</title>
</head>
<body style="background-color: #f4f7f6; font-family: sans-serif; display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 20px; box-sizing: border-box;">

    <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 100%; max-width: 400px; box-sizing: border-box;">
        <h2 style="color: #007bff; text-align: center; margin-top: 0; margin-bottom: 25px;">Recuperar Cuenta</h2>
        
        <?php if(!empty($error)): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px; font-size: 0.85rem; text-align: center;"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="recuperar.php" method="POST">
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 0.9rem; color: #555; margin-bottom: 5px;">Correo Electrónico Corporativo</label>
                <input type="email" name="email" required placeholder="ejemplo@di-fabrica.com" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;">
            </div>

            <button type="submit" style="width: 100%; padding: 12px; background-color: #007bff; color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 1rem;">
                Verificar Correo
            </button>
        </form>

        <div style="text-align: center; margin-top: 20px; border-top: 1px solid #eee; padding-top: 15px;">
            <a href="login.php" style="color: #666; text-decoration: none; font-size: 0.85rem;">Volver al Login</a>
        </div>
    </div>

    <?php if ($mostrar_simulador): ?>
        <div style="margin-top: 30px; background: #fff; width: 100%; max-width: 500px; border: 2px dashed #28a745; border-radius: 8px; padding: 20px; box-sizing: border-box; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
            <span style="color: #28a745; font-weight: bold; font-size: 0.8rem; text-transform: uppercase; display: block; margin-bottom: 15px;">📨 Servidor Local XAMPP - Simulación de Correo Enviado</span>
            
            <div style="background: #f8f9fa; border: 1px solid #e3e6f0; padding: 15px; border-radius: 5px; font-size: 0.85rem; margin-bottom: 15px;">
                <strong>De:</strong> sistema@di-fabrica.com (Diseña y Fabrica)<br>
                <strong>Para:</strong> <?php echo $correo_destino; ?><br>
                <strong>Asunto:</strong> Restablecer Contraseña - Diseña y Fabrica
            </div>

            <div style="border: 1px solid #eee; padding: 20px; border-radius: 5px; background: #fff; text-align: center;">
                <h3 style="color: #007bff; margin-top: 0;">DISEÑA Y FABRICA</h3>
                <p style="text-align: left; color: #333; font-size: 0.9rem;">Se ha solicitado un cambio de contraseña para tu cuenta de administrador corporativa.</p>
                <p style="text-align: left; color: #333; font-size: 0.9rem;">Para restablecer tus credenciales de forma segura desde tu dispositivo, haz clic en el siguiente botón:</p>
                
                <a href="<?php echo $enlace_final; ?>" style="background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block; margin: 15px 0; font-size: 0.9rem;">Restablecer Contraseña</a>
                
                <p style="font-size: 0.75rem; color: #888; text-align: left; margin-bottom: 0; border-top: 1px solid #eee; padding-top: 10px;">Este es un flujo de desarrollo empresarial formal para entornos locales.</p>
            </div>
        </div>
    <?php endif; ?>

</body>
</html>