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
    <title>Catálogo de Impresiones 3D</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body style="background-color: #f8f9fa; font-family: sans-serif;">

<main style="max-width: 1100px; margin: 30px auto; padding: 0 20px;">

    <!-- TÍTULO PRINCIPAL Y BARRA DE BÚSQUEDA -->
    <div style="text-align: center; margin-bottom: 30px;">
        <h1 style="color: #007bff; font-size: 2.2rem; font-weight: bold; margin-bottom: 15px;">Catálogo de Impresiones 3D</h1>
        
        <div style="max-width: 450px; margin: 0 auto;">
            <input type="text" id="inputBuscarProyecto" onkeyup="buscarProyectos()" placeholder="🔎 Buscar proyecto por nombre..." style="width: 100%; padding: 12px 20px; border: 2px solid #007bff; border-radius: 25px; font-size: 0.95rem; outline: none; box-shadow: 0 2px 8px rgba(0, 123, 255, 0.15); box-sizing: border-box;">
        </div>
    </div>

    <!-- CONTENEDOR CON ALTURA MÁXIMA Y SCROLL INTERNO -->
    <div style="max-height: 650px; overflow-y: auto; padding: 20px; border-radius: 12px; background: #ffffff; border: 1px solid #e0e0e0; box-shadow: inset 0 2px 6px rgba(0,0,0,0.03);">
        
        <!-- GRID DE PROYECTOS -->
        <div id="contenedorProyectos" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px;">
            <?php
            $query_proyectos = "SELECT * FROM proyectos ORDER BY id DESC";
            $resultado = mysqli_query($conexion, $query_proyectos);

            if ($resultado && mysqli_num_rows($resultado) > 0) {
                while ($proyecto = mysqli_fetch_assoc($resultado)) {
                    ?>
                    <div class="tarjeta-proyecto" style="background: #fff; border-radius: 12px; border: 1px solid #e0e0e0; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05); display: flex; flex-direction: column; justify-content: space-between; transition: transform 0.2s;">
                        
                        <div>
                            <!-- IMAGEN COMPLETA CON OBJECT-FIT CONTAIN -->
                            <div style="height: 190px; overflow: hidden; background: #fafafa; display: flex; align-items: center; justify-content: center; padding: 10px; border-bottom: 1px solid #f0f0f0;">
                                <img src="img/proyectos/<?php echo htmlspecialchars($proyecto['imagen']); ?>" alt="<?php echo htmlspecialchars($proyecto['titulo']); ?>" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                            </div>

                            <div style="padding: 15px; text-align: center;">
                                <h3 class="titulo-proyecto" style="margin: 0 0 8px 0; color: #222; font-size: 1.1rem; font-weight: bold;">
                                    <?php echo htmlspecialchars($proyecto['titulo']); ?>
                                </h3>
                                <p style="color: #666; font-size: 0.85rem; margin: 0; line-height: 1.4;">
                                    <?php echo htmlspecialchars($proyecto['descripcion']); ?>
                                </p>
                            </div>
                        </div>

                        <div style="padding: 0 15px 15px 15px;">
                            <a href="cotizacion.php?asunto=<?php echo urlencode('Cotizar: ' . $proyecto['titulo']); ?>" style="display: block; background: #007bff; color: white; text-align: center; padding: 10px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 0.9rem;">
                                Cotizar
                            </a>
                        </div>

                    </div>
                    <?php
                }
            } else {
                echo "<p style='text-align: center; color: #888; grid-column: 1/-1; padding: 30px;'>No hay proyectos publicados actualmente.</p>";
            }
            ?>
        </div>

        <p id="sinResultados" style="display: none; text-align: center; color: #777; font-size: 1rem; margin: 40px 0;">
            ❌ No se encontraron proyectos que coincidan con tu búsqueda.
        </p>

    </div>

</main>

<script>
    function buscarProyectos() {
        const input = document.getElementById('inputBuscarProyecto');
        const filtro = input.value.toLowerCase();
        const tarjetas = document.getElementsByClassName('tarjeta-proyecto');
        const mensajeSinResultados = document.getElementById('sinResultados');
        let encontrados = 0;

        for (let i = 0; i < tarjetas.length; i++) {
            const titulo = tarjetas[i].getElementsByClassName('titulo-proyecto')[0];
            if (titulo) {
                const textoTitulo = titulo.textContent || titulo.innerText;
                if (textoTitulo.toLowerCase().indexOf(filtro) > -1) {
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