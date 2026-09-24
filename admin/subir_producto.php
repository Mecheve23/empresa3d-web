<?php
// Mantenemos la seguridad del panel administrativo
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// PROCESAR EL FORMULARIO CUANDO SE ENVÍA
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    
    // Manejo de la subida de la imagen del producto
    $nombre_imagen = $_FILES['imagen']['name'];
    $ruta_temporal = $_FILES['imagen']['tmp_name'];
    
    // Generamos un nombre único para evitar que se dupliquen archivos
    $extension = pathinfo($nombre_imagen, PATHINFO_EXTENSION);
    $imagen_final = "prod_" . time() . "." . $extension;
    
    // La carpeta destino en la raíz pública del proyecto
    $carpeta_destino = "../img/productos/" . $imagen_final;
    
    if (move_uploaded_file($ruta_temporal, $carpeta_destino)) {
        // Insertar en la tabla de productos que creamos en la base de datos
        $sql = "INSERT INTO productos (nombre, descripcion, precio, imagen) VALUES ('$nombre', '$descripcion', '$precio', '$imagen_final')";
        if (mysqli_query($conexion, $sql)) {
            echo "<script>alert('¡Producto publicado con éxito en el catálogo!'); window.location.href='panel.php';</script>";
        } else {
            echo "<script>alert('Error al guardar en la base de datos: " . mysqli_error($conexion) . "');</script>";
        }
    } else {
        echo "<script>alert('Error al subir la imagen del producto. Verifica los permisos de la carpeta.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subir Producto - Panel Administrativo</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body style="background-color: #f4f7f6; font-family: sans-serif; padding: 20px;">

    <div style="max-width: 600px; margin: 30px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 20px;">
            <h2 style="color: #007bff; margin: 0;">📦 Añadir Nuevo Producto a la Tienda</h2>
            <a href="panel.php" style="text-decoration: none; color: #666; font-weight: bold; font-size: 0.9rem;">← Volver al Panel</a>
        </div>

        <form action="subir_producto.php" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 15px;">
            <div>
                <label style="font-weight: bold; display: block; margin-bottom: 5px; font-size: 0.9rem;">Nombre del Producto:</label>
                <input type="text" name="nombre" placeholder="Ej: Filamento PLA Pro 1kg" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;">
            </div>

            <div>
                <label style="font-weight: bold; display: block; margin-bottom: 5px; font-size: 0.9rem;">Precio (en USD):</label>
                <input type="number" step="0.01" name="precio" placeholder="Ej: 24.99" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;">
            </div>

            <div>
                <label style="font-weight: bold; display: block; margin-bottom: 5px; font-size: 0.9rem;">Descripción del Artículo:</label>
                <textarea name="descripcion" placeholder="Detalla las especificaciones técnicas o características..." required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; height: 100px; box-sizing: border-box; font-family: sans-serif;"></textarea>
            </div>

            <div>
                <label style="font-weight: bold; display: block; margin-bottom: 5px; font-size: 0.9rem;">Imagen de Portada del Producto:</label>
                <input type="file" name="imagen" accept="image/*" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; background: #fafafa;">
                <small style="color: #888; display: block; margin-top: 4px;">Formatos recomendados: JPG, PNG o WEBP.</small>
            </div>

            <button type="submit" style="background: #28a745; color: white; border: none; padding: 12px; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 1rem; margin-top: 10px; transition: background 0.3s;">
                🚀 Publicar Producto en la Tienda
            </button>
        </form>
    </div>

</body>
</html>