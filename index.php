<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// IMPORTANTE: Incluir la base de datos PRIMERO
include 'includes/db.php';
include 'includes/header.php';

// CONSULTAR INFORMACIÓN DE LA EMPRESA
$q_inf = "SELECT * FROM empresa_info WHERE id = 1";
$r_inf = mysqli_query($conexion, $q_inf);
$d_inf = ($r_inf) ? mysqli_fetch_assoc($r_inf) : null;

$txt_titulo    = (!empty($d_inf) && !empty($d_inf['banner_titulo'])) ? $d_inf['banner_titulo'] : 'Impresión 3D de Calidad para tus Ideas';
$txt_subtitulo = (!empty($d_inf) && !empty($d_inf['banner_subtitulo'])) ? $d_inf['banner_subtitulo'] : 'Fabricamos e imprimimos productos personalizados para clientes industriales y particulares.';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Diseña y Fabrica</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body style="background-color: #f8f9fa; font-family: sans-serif;">

<main>
    <!-- BANNER PRINCIPAL EDITABLE -->
    <section style="background: white; padding: 60px 20px; text-align: center; border-bottom: 1px solid #e0e0e0;">
        <div style="max-width: 800px; margin: 0 auto;">
            <h1 style="color: #007bff; font-size: 2.3rem; font-weight: bold; margin-bottom: 15px; line-height: 1.2;">
                <?php echo htmlspecialchars($txt_titulo); ?>
            </h1>
            <p style="color: #666; font-size: 1.1rem; margin-bottom: 25px; line-height: 1.5;">
                <?php echo htmlspecialchars($txt_subtitulo); ?>
            </p>
            <a href="proyectos.php" style="background: #007bff; color: white; padding: 12px 25px; border-radius: 5px; text-decoration: none; font-weight: bold; display: inline-block;">
                Ver Proyectos Recientes
            </a>
        </div>
    </section>

    <!-- SECCIÓN DE PROYECTOS DESTACADOS -->
    <section style="max-width: 1000px; margin: 40px auto; padding: 0 20px;">
        <h2 style="text-align: center; color: #333; margin-bottom: 30px;">Últimos Trabajos Realizados</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px;">
            <?php
            $query_p = "SELECT * FROM proyectos ORDER BY id DESC LIMIT 4";
            $res_p = mysqli_query($conexion, $query_p);
            if ($res_p && mysqli_num_rows($res_p) > 0) {
                while ($p = mysqli_fetch_assoc($res_p)) {
                    ?>
                    <div style="background: white; border-radius: 8px; border: 1px solid #ddd; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.05); text-align: center; padding-bottom: 15px;">
                        <img src="img/proyectos/<?php echo htmlspecialchars($p['imagen']); ?>" style="width: 100%; height: 140px; object-fit: cover;">
                        <h4 style="margin: 10px 0 5px 0; color: #333;"><?php echo htmlspecialchars($p['titulo']); ?></h4>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>

</body>
</html>