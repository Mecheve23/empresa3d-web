<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Datos del cliente recibidos del formulario
    $nombre    = isset($_POST['nombre_cliente']) ? $_POST['nombre_cliente'] : (isset($_POST['nombre']) ? $_POST['nombre'] : 'Cliente');
    $telefono  = isset($_POST['celular_cliente']) ? $_POST['celular_cliente'] : (isset($_POST['telefono_entrega']) ? $_POST['telefono_entrega'] : '');
    $ciudad    = isset($_POST['ciudad_cliente']) ? $_POST['ciudad_cliente'] : (isset($_POST['ciudad_entrega']) ? $_POST['ciudad_entrega'] : '');
    $direccion = isset($_POST['direccion_cliente']) ? $_POST['direccion_cliente'] : (isset($_POST['direccion_entrega']) ? $_POST['direccion_entrega'] : '');
    $metodo    = isset($_POST['metodo_pago']) ? $_POST['metodo_pago'] : 'Transferencia Bancaria / Depósito';

    // 2. Construir lista de artículos desde la sesión o desde los datos POST ocultos
    $texto_articulos = "";
    $total_real = 0;
    $contador = 1;

    // Primero intentar leer directamente los datos ocultos
    if (!empty($_POST['detalle_productos']) && floatval($_POST['total_pago']) > 0) {
        $texto_articulos = trim($_POST['detalle_productos']);
        $total_real      = floatval($_POST['total_pago']);
    } 
    // Si venían vacíos los campos ocultos, leer de la sesión del carrito
    elseif (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
        foreach ($_SESSION['carrito'] as $id => $item) {
            $p_nombre   = 'Producto';
            $p_precio   = 0;
            $p_cantidad = 1;

            if (is_array($item)) {
                $p_nombre   = isset($item['nombre']) ? $item['nombre'] : '';
                $p_precio   = isset($item['precio']) ? floatval($item['precio']) : 0;
                $p_cantidad = isset($item['cantidad']) ? intval($item['cantidad']) : 1;
            } else {
                $p_cantidad = intval($item);
            }

            // Si falta el nombre o el precio, consultamos directo a la base de datos
            $id_buscar = is_array($item) ? (isset($item['id']) ? intval($item['id']) : intval($id)) : intval($id);
            if ($id_buscar > 0 && ($p_precio == 0 || empty($p_nombre))) {
                $q_prod = "SELECT * FROM productos WHERE id = $id_buscar";
                $r_prod = mysqli_query($conexion, $q_prod);
                if ($r_prod && mysqli_num_rows($r_prod) > 0) {
                    $row_p = mysqli_fetch_assoc($r_prod);
                    $p_nombre = $row_p['nombre'];
                    $p_precio = floatval($row_p['precio']);
                }
            }

            if (!empty($p_nombre)) {
                $subtotal_item = $p_precio * $p_cantidad;
                $total_real += $subtotal_item;
                $texto_articulos .= "  $contador. $p_nombre - $" . number_format($subtotal_item, 2) . "\n";
                $contador++;
            }
        }
    }

    // Si aún así no hay artículos detectados (por sesión expirada), colocar mensaje por defecto
    if (empty(trim($texto_articulos))) {
        $texto_articulos = "  1. Producto seleccionado del catálogo";
        $total_real = isset($_POST['total_pago']) ? floatval($_POST['total_pago']) : 0;
    }

    // 3. Guardar en Base de Datos
    $nombre_db    = mysqli_real_escape_string($conexion, trim($nombre));
    $telefono_db  = mysqli_real_escape_string($conexion, trim($telefono));
    $ciudad_db    = mysqli_real_escape_string($conexion, trim($ciudad));
    $direccion_db = mysqli_real_escape_string($conexion, trim($direccion));
    $metodo_db    = mysqli_real_escape_string($conexion, trim($metodo));

    $query_col = "SHOW TABLES LIKE 'pedidos'";
    $res_col = mysqli_query($conexion, $query_col);
    if ($res_col && mysqli_num_rows($res_col) > 0) {
        $sql = "INSERT INTO pedidos (nombre_cliente, telefono, ciudad, direccion, metodo_pago, total, fecha) 
                VALUES ('$nombre_db', '$telefono_db', '$ciudad_db', '$direccion_db', '$metodo_db', '$total_real', NOW())";
        mysqli_query($conexion, $sql);
    }

    // 4. Limpiar carrito de la sesión
    unset($_SESSION['carrito']);

    // 5. Armar el mensaje para WhatsApp
    $mensaje_ws = "¡Hola Diseña y Fabrica!\n\n" .
                  "He realizado un pedido desde la tienda virtual. Aquí tienes los detalles:\n\n" .
                  "ARTICULOS SOLICITADOS:\n" . $texto_articulos . "\n" .
                  "TOTAL A PAGAR: $" . number_format($total_real, 2) . "\n" .
                  "METODO DE PAGO: " . $metodo . "\n\n" .
                  "DATOS DE ENTREGA:\n" .
                  "  • Destinatario: " . $nombre . "\n" .
                  "  • Celular: " . $telefono . "\n" .
                  "  • Ciudad: " . $ciudad . "\n" .
                  "  • Dirección: " . $direccion . "\n\n" .
                  "Quedo atento para coordinar el método de pago y el despacho de mi paquete. ¡Gracias!";

    $numero_whatsapp = "593963884281"; 
    $url_whatsapp = "https://wa.me/" . $numero_whatsapp . "?text=" . urlencode($mensaje_ws);
    ?>

    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>¡Pedido Confirmado!</title>
        <link rel="stylesheet" href="css/estilos.css">
    </head>
    <body style="background-color: #f8f9fa; font-family: sans-serif; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; padding: 20px;">

        <div style="background: white; padding: 40px 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); max-width: 500px; width: 100%; text-align: center; border: 1px solid #eee;">
            <div style="font-size: 50px; margin-bottom: 10px;">🎉</div>
            <h1 style="color: #28a745; margin: 0 0 10px 0; font-size: 1.6rem;">¡Pedido Registrado con Éxito!</h1>
            <p style="color: #555; line-height: 1.5; font-size: 0.95rem; margin-bottom: 20px;">
                Gracias <strong><?php echo htmlspecialchars($nombre); ?></strong>. Tu solicitud ha sido procesada.
            </p>

            <div style="background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 8px; padding: 15px; text-align: left; margin-bottom: 20px; font-size: 0.88rem; color: #444; line-height: 1.5;">
                <strong style="color: #007bff; display: block; margin-bottom: 5px;">Resumen del Pedido:</strong>
                <?php echo nl2br(htmlspecialchars($texto_articulos)); ?>
                <hr style="border: 0; border-top: 1px solid #ddd; margin: 8px 0;">
                <p style="margin: 3px 0;"><strong>Total a Pagar:</strong> <span style="color: #28a745; font-weight: bold;">$<?php echo number_format($total_real, 2); ?></span></p>
                <p style="margin: 3px 0;"><strong>Método de Pago:</strong> <?php echo htmlspecialchars($metodo); ?></p>
            </div>

            <a href="<?php echo $url_whatsapp; ?>" target="_blank" style="display: block; background: #25d366; color: white; text-decoration: none; padding: 12px 20px; border-radius: 6px; font-weight: bold; font-size: 1rem; margin-bottom: 15px;">
                📱 Enviar Detalle a WhatsApp
            </a>

            <a href="tienda.php" style="color: #007bff; text-decoration: none; font-size: 0.85rem; font-weight: bold;">Volver a la Tienda</a>
        </div>

    </body>
    </html>

    <?php
} else {
    header("Location: carrito.php");
    exit();
}
?>