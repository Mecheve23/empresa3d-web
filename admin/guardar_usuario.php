<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db.php';

// Seguridad: Solo administradores logueados pueden crear usuarios
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitizamos los inputs (omitimos nombre_completo ya que la tabla no tiene esa columna)
    $nuevo_usuario   = mysqli_real_escape_string($conexion, $_POST['nuevo_usuario']);
    $nueva_clave     = $_POST['nueva_clave']; // La clave en bruto para encriptar

    // 1. Validar que el nombre de usuario no exista ya en la base de datos
    $verificar = "SELECT * FROM usuarios WHERE usuario = '$nuevo_usuario'";
    $res_verificar = mysqli_query($conexion, $verificar);

    if (mysqli_num_rows($res_verificar) > 0) {
        echo "<script>alert('Error: El nombre de usuario ya está registrado por otro colaborador. Intenta con otro.'); window.history.back();</script>";
        exit();
    }

    // 2. Encriptación segura por MD5
    $clave_encriptada = md5($nueva_clave);

    // 3. Insertar el nuevo colaborador en la base de datos
    // Quitamos 'nombre' de la consulta para evitar el error de columna inexistente
    $query = "INSERT INTO usuarios (usuario, password, foto_perfil)
              VALUES ('$nuevo_usuario', '$clave_encriptada', 'usuario.png')";

    $resultado = mysqli_query($conexion, $query);

    if ($resultado) {
        echo "<script>alert('¡Usuario colaborador creado con éxito! Ya puede iniciar sesión para ayudarte.'); window.location.href='panel.php';</script>";
    } else {
        echo "Error al registrar el usuario: " . mysqli_error($conexion);
    }
}
?>