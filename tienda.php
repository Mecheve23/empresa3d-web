<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'includes/db.php';
include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Virtual - Productos Comerciales</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body style="background-color: #f8f9fa; font-family: sans-serif;">

<main style="max-width: 1100px; margin: 30px auto; padding: 0 20px;">

    <!-- ENCABEZADO DE LA TIENDA CON BUSCADOR -->
    <div style="text-align: center; margin-bottom: 30px;">
        <h1 style="color: #333; font-size: 2.1rem; font-weight: bold; margin-bottom: 15px;">
            🛒 Catálogo de Productos Comerciales
        </h1>
        
        <!-- BARRA DE BÚSQUEDA INTERACTIVA -->
        <div style="max-width: 450px; margin: 0 auto;">
            <input type="text" id="inputBuscarProducto" onkeyup="buscarProductosTienda()" placeholder="🔎 Buscar por producto (Ej: Retrovisor, PS5, Polea)..." style="width: 100%; padding: 12px 20px; border: 2px solid #28a745; border-radius: 25px; font-size: 0.95rem; outline: none; box-shadow: 0 2px 8px rgba(40, 167, 69, 0.15); box-sizing: border-box;">
        </div>
    </div>

    <!-- CONTENEDOR COMPACTO CON SCROLL INTERNO -->
    <div style="max-height: 650px; overflow-y: auto; padding: 15px; border-radius: 12px; background: #ffffff; border: 1px solid #e0e0e0; box-shadow: inset 0 2px 6px rgba(0,0,0,0.03);">
        
        <!-- GRID DE PRODUCTOS -->
        <div id="contenedorProductos" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px;">
            <?php
            $query_productos = "SELECT * FROM productos ORDER BY id DESC";
            $res_prod = mysqli_query($conexion, $query_productos);

            if ($res_prod && mysqli_num_rows($res_prod) > 0) {
                while ($prod = mysqli_fetch_assoc($res_prod)) {
                    ?>
                    <div class="tarjeta-producto" style="background: #fff; border-radius: 10px; border: 1px solid #eee; overflow: hidden; box-shadow: 0 3px 10px rgba(0,0,0,0.05); display: flex; flex-direction: column; justify-content: space-between; padding-bottom: 15px; transition: transform 0.2s;">
                        
                        <div>
                            <!-- IMAGEN COMPLETA CON OBJECT-FIT CONTAIN -->
                            <div style="height: 190px; overflow: hidden; background: #fdfdfd; display: flex; align-items: center; justify-content: center; padding: 10px; border-bottom: 1px solid #f0f0f0;">
                                <img src="img/productos/<?php echo htmlspecialchars($prod['imagen']); ?>" alt="<?php echo htmlspecialchars($prod['nombre']); ?>" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                            </div>

                            <div style="padding: 15px 12px 5px 12px; text-align: center;">
                                <h3 class="nombre-producto" style="margin: 0 0 6px 0; color: #222; font-size: 1.05rem; font-weight: bold;">
                                    <?php echo htmlspecialchars($prod['nombre']); ?>
                                </h3>
                                <p style="color: #777; font-size: 0.8rem; margin: 0 0 10px 0; height: 32px; overflow: hidden; line-height: 1.3;">
                                    <?php echo htmlspecialchars(isset($prod['descripcion']) ? $prod['descripcion'] : 'Producto en stock para fabricación e impresión 3D.'); ?>
                                </p>
                                <div style="font-size: 1.2rem; color: #fd7e14; font-weight: bold;">
                                    $<?php echo number_format($prod['precio'], 2); ?>
                                </div>
                            </div>
                        </div>

                        <div style="padding: 10px 15px 0 15px;">
                            <a href="carrito.php?accion=agregar&id=<?php echo $prod['id']; ?>" style="display: block; background: #28a745; color: white; text-align: center; padding: 10px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 0.88rem; box-shadow: 0 2px 4px rgba(40, 167, 69, 0.2);">
                                Añadir al Carrito
                            </a>
                        </div>

                    </div>
                    <?php
                }
            } else {
                echo "<p style='text-align: center; color: #888; grid-column: 1/-1; padding: 30px;'>No hay productos disponibles en la tienda comercial actualmente.</p>";
            }
            ?>
        </div>

        <p id="sinResultadosTienda" style="display: none; text-align: center; color: #888; font-size: 1rem; margin: 40px 0;">
            ❌ No se encontraron productos con ese nombre.
        </p>

    </div>

</main>

<script>
    function buscarProductosTienda() {
        const input = document.getElementById('inputBuscarProducto');
        const filtro = input.value.toLowerCase();
        const tarjetas = document.getElementsByClassName('tarjeta-producto');
        const mensajeSinResultados = document.getElementById('sinResultadosTienda');
        let encontrados = 0;

        for (let i = 0; i < tarjetas.length; i++) {
            const nombreObj = tarjetas[i].getElementsByClassName('nombre-producto')[0];
            if (nombreObj) {
                const textoNombre = nombreObj.textContent || nombreObj.innerText;
                if (textoNombre.toLowerCase().indexOf(filtro) > -1) {
                    tarjetas[i].style.display = "flex";
                    encontrados++;
                } else {
                    tarjetas[i].style.display = "none";
                }
            }
        }

        if (encontrados === 0) {
            mensajeSinResultados.style.display = "block";
        } else {
            mensajeSinResultados.style.display = "none";
        }
    }
</script>

<?php include 'includes/footer.php'; ?>

</body>
</html>