<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'includes/header.php';
include 'includes/db.php';

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = array();
}

// 1. CAPTURAR EL PRODUCTO DESDE TIENDA.PHP
if (isset($_GET['id']) && intval($_GET['id']) > 0) {
    $id_prod = intval($_GET['id']);
    
    $q_add = "SELECT * FROM productos WHERE id = $id_prod";
    $r_add = mysqli_query($conexion, $q_add);
    
    if ($r_add && mysqli_num_rows($r_add) > 0) {
        $p_data = mysqli_fetch_assoc($r_add);
        
        // Si el producto ya está en el carrito, sumamos 1 a la cantidad existente
        if (isset($_SESSION['carrito'][$id_prod]) && is_array($_SESSION['carrito'][$id_prod])) {
            $_SESSION['carrito'][$id_prod]['cantidad'] += 1;
        } else {
            $_SESSION['carrito'][$id_prod] = array(
                'id'       => $p_data['id'],
                'nombre'   => $p_data['nombre'],
                'precio'   => floatval($p_data['precio']),
                'imagen'   => $p_data['imagen'],
                'cantidad' => 1
            );
        }
    }
}

// 2. MODIFICAR CANTIDAD (+ / -)
if (isset($_GET['accion']) && isset($_GET['item_id'])) {
    $item_id = intval($_GET['item_id']);
    if (isset($_SESSION['carrito'][$item_id])) {
        if ($_GET['accion'] === 'sumar') {
            $_SESSION['carrito'][$item_id]['cantidad'] += 1;
        } elseif ($_GET['accion'] === 'restar') {
            $_SESSION['carrito'][$item_id]['cantidad'] -= 1;
            if ($_SESSION['carrito'][$item_id]['cantidad'] <= 0) {
                unset($_SESSION['carrito'][$item_id]);
            }
        }
    }
    header("Location: carrito.php");
    exit();
}

// 3. VACIAR CARRITO COMPLETO
if (isset($_GET['vaciar'])) {
    unset($_SESSION['carrito']);
    header("Location: carrito.php");
    exit();
}

// 4. ELIMINAR ITEM INDIVIDUAL
if (isset($_GET['eliminar'])) {
    $id_del = intval($_GET['eliminar']);
    unset($_SESSION['carrito'][$id_del]);
    header("Location: carrito.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu Carrito de Compras - Imprenta 3D</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body style="background-color: #f8f9fa; font-family: sans-serif; padding-bottom: 50px;">

<main style="max-width: 850px; margin: 40px auto; padding: 0 20px;">
    
    <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #eee;">
        
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #f0f0f0; padding-bottom: 15px; margin-bottom: 25px; flex-wrap: wrap; gap: 10px;">
            <h1 style="margin: 0; color: #333; font-size: 1.5rem;">🛒 Tu Carrito de Compras</h1>
            <div style="display: flex; gap: 15px; align-items: center;">
                <a href="tienda.php" style="background: #007bff; color: white; padding: 6px 12px; border-radius: 5px; font-size: 0.85rem; font-weight: bold; text-decoration: none;">
                    🛍️ Seguir Comprando
                </a>
                <?php if (!empty($_SESSION['carrito'])): ?>
                    <a href="carrito.php?vaciar=true" onclick="return confirm('¿Vaciar todo el carrito?');" style="color: #dc3545; font-size: 0.85rem; font-weight: bold; text-decoration: none;">Vaciar Carrito</a>
                <?php endif; ?>
            </div>
        </div>

        <?php if (empty($_SESSION['carrito'])): ?>
            <div style="text-align: center; padding: 40px 20px;">
                <p style="color: #777; font-size: 1.1rem; margin-bottom: 20px;">Tu carrito de compras está vacío actualmente.</p>
                <a href="tienda.php" style="background: #007bff; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: bold;">Explorar la Tienda</a>
            </div>
        <?php else: ?>
            <div style="margin-bottom: 25px;">
                <?php
                $total_general = 0;
                $lineas_texto_ws = array();
                $contador = 1;

                foreach ($_SESSION['carrito'] as $id => $item) {
                    if (is_array($item)) {
                        $nombre   = isset($item['nombre']) ? $item['nombre'] : 'Producto';
                        $precio   = isset($item['precio']) ? floatval($item['precio']) : 0;
                        $cantidad = isset($item['cantidad']) ? intval($item['cantidad']) : 1;
                        $imagen   = isset($item['imagen']) ? $item['imagen'] : '';
                    } else {
                        $cantidad = intval($item);
                        $query_p = "SELECT * FROM productos WHERE id = " . intval($id);
                        $res_p = mysqli_query($conexion, $query_p);
                        if ($res_p && mysqli_num_rows($res_p) > 0) {
                            $prod_db = mysqli_fetch_assoc($res_p);
                            $nombre = $prod_db['nombre'];
                            $precio = floatval($prod_db['precio']);
                            $imagen = isset($prod_db['imagen']) ? $prod_db['imagen'] : '';
                        } else {
                            continue;
                        }
                    }

                    $subtotal = $precio * $cantidad;
                    $total_general += $subtotal;
                    
                    // Formato de texto para WhatsApp con cantidad si es mayor a 1
                    $str_cant = ($cantidad > 1) ? " (x$cantidad)" : "";
                    $lineas_texto_ws[] = "  $contador. $nombre$str_cant - $" . number_format($subtotal, 2);
                    $contador++;
                    ?>
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid #eee; gap: 15px;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <?php if(!empty($imagen)): ?>
                                <img src="img/productos/<?php echo $imagen; ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px; border: 1px solid #ddd;">
                            <?php endif; ?>
                            <div>
                                <h3 style="margin: 0; font-size: 1rem; color: #333;"><?php echo htmlspecialchars($nombre); ?></h3>
                                <small style="color: #666;">Precio unid.: $<?php echo number_format($precio, 2); ?></small>
                                
                                <!-- CONTROLES DE CANTIDAD (+ / -) -->
                                <div style="margin-top: 5px; display: flex; align-items: center; gap: 8px;">
                                    <span style="font-size: 0.8rem; color: #555; font-weight: bold;">Cant:</span>
                                    <a href="carrito.php?accion=restar&item_id=<?php echo $id; ?>" style="background: #e9ecef; color: #333; text-decoration: none; width: 22px; height: 22px; display: inline-flex; align-items: center; justify-content: center; border-radius: 4px; font-weight: bold; font-size: 0.9rem;">-</a>
                                    <span style="font-size: 0.85rem; font-weight: bold; padding: 0 4px;"><?php echo $cantidad; ?></span>
                                    <a href="carrito.php?accion=sumar&item_id=<?php echo $id; ?>" style="background: #e9ecef; color: #333; text-decoration: none; width: 22px; height: 22px; display: inline-flex; align-items: center; justify-content: center; border-radius: 4px; font-weight: bold; font-size: 0.9rem;">+</a>
                                </div>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <span style="font-weight: bold; color: #007bff; font-size: 1.1rem;">$<?php echo number_format($subtotal, 2); ?></span><br>
                            <a href="carrito.php?eliminar=<?php echo $id; ?>" style="color: #dc3545; font-size: 0.75rem; text-decoration: none; margin-top: 4px; display: inline-block;">[Quitar]</a>
                        </div>
                    </div>
                    <?php
                }

                $texto_lista_productos = implode("\n", $lineas_texto_ws);
                ?>
                <div style="text-align: right; padding-top: 15px;">
                    <span style="font-size: 1.2rem; font-weight: bold; color: #333;">Total a Pagar: </span>
                    <span style="font-size: 1.4rem; font-weight: bold; color: #28a745;">$<?php echo number_format($total_general, 2); ?></span>
                </div>
            </div>

            <!-- FORMULARIO DE ENVÍO -->
            <form action="procesar_pedido.php" method="POST" style="background: #f9fafb; padding: 20px; border-radius: 8px; border: 1px solid #e5e7eb;">
                <h3 style="margin-top: 0; color: #007bff; font-size: 1.1rem; margin-bottom: 15px;">📋 Información Obligatoria para el Envío</h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                    <div>
                        <label style="font-weight: bold; display: block; margin-bottom: 5px; font-size: 0.85rem;">Nombre Completo del Destinatario:</label>
                        <input type="text" name="nombre_cliente" placeholder="Ej: Marcos Echeverria" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box;">
                    </div>
                    <div>
                        <label style="font-weight: bold; display: block; margin-bottom: 5px; font-size: 0.85rem;">Teléfono Celular de Contacto:</label>
                        <input type="text" name="celular_cliente" placeholder="Ej: 0991366695" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                    <div>
                        <label style="font-weight: bold; display: block; margin-bottom: 5px; font-size: 0.85rem;">Ciudad / Provincia:</label>
                        <input type="text" name="ciudad_cliente" placeholder="Ej: Guayaquil" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box;">
                    </div>
                    <div>
                        <label style="font-weight: bold; display: block; margin-bottom: 5px; font-size: 0.85rem;">Dirección de Entrega Exacta:</label>
                        <input type="text" name="direccion_cliente" placeholder="Ej: Urbanización Sta Leonor Mz4 V12" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box;">
                    </div>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="font-weight: bold; display: block; margin-bottom: 5px; font-size: 0.85rem;">Método de Pago:</label>
                    <select name="metodo_pago" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; background: white; box-sizing: border-box;">
                        <option value="Transferencia Bancaria / Depósito" selected>Transferencia Bancaria / Depósito</option>
                        <option value="Efectivo Contra Entrega">Efectivo (Contra entrega)</option>
                    </select>
                </div>

                <input type="hidden" name="total_pago" value="<?php echo $total_general; ?>">
                <input type="hidden" name="detalle_productos" value="<?php echo htmlspecialchars($texto_lista_productos); ?>">

                <button type="submit" style="width: 100%; background: #28a745; color: white; border: none; padding: 12px; border-radius: 6px; font-weight: bold; font-size: 1rem; cursor: pointer;">
                    ✅ Confirmar Pedido
                </button>
            </form>
        <?php endif; ?>

    </div>

</main>

<?php include 'includes/footer.php'; ?>

</body>
</html>