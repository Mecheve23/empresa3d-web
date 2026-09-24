<?php include 'includes/header.php'; ?>
<?php include 'includes/db.php'; ?>

<?php
// Consultar la información corporativa actualizada desde la base de datos
$consulta_info = "SELECT * FROM empresa_info WHERE id = 1";
$res_info = mysqli_query($conexion, $consulta_info);
$info = mysqli_fetch_assoc($res_info);

// Valores dinámicos (si están vacíos en la BD, muestra un texto base)
$quienes_somos    = !empty($info['quienes_somos']) ? $info['quienes_somos'] : 'Somos una empresa dedicada a la fabricación digital y soluciones avanzadas en impresión 3D.';
$historia         = !empty($info['historia']) ? $info['historia'] : 'Fundada con la visión de acercar la tecnología industrial a todos.';
$politica_calidad = !empty($info['politica_calidad']) ? $info['politica_calidad'] : 'Cada impresión es sometida a un control de calidad estricto.';
$mision           = !empty($info['mision']) ? $info['mision'] : 'Brindar servicios de impresión y diseño 3D de alta calidad.';
$vision           = !empty($info['vision']) ? $info['vision'] : 'Ser un referente líder en el mercado de la fabricación digital.';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nuestra Empresa</title>
    <link rel="stylesheet" href="css/estilos.css">
    <style>
        .nosotros-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
            font-family: sans-serif;
        }

        .nosotros-titulo {
            text-align: center;
            color: #007bff;
            font-size: 2rem;
            margin-bottom: 30px;
        }

        .seccion-bloque {
            margin-bottom: 30px;
        }

        .seccion-bloque h2 {
            color: #333;
            font-size: 1.3rem;
            margin-bottom: 10px;
        }

        .seccion-bloque p {
            color: #555;
            line-height: 1.6;
            font-size: 0.95rem;
        }

        .caja-destacada {
            background: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 20px;
            border-radius: 4px;
            margin-bottom: 30px;
        }

        .grid-mision-vision {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 30px;
        }

        .card-mv {
            background: #fff;
            border: 1px solid #e0e0e0;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            text-align: center;
        }

        .card-mv h3 {
            color: #007bff;
            margin-top: 0;
            margin-bottom: 10px;
        }

        .card-mv p {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.5;
            margin: 0;
        }

        @media (max-width: 600px) {
            .grid-mision-vision {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<main class="nosotros-container">
    <h1 class="nosotros-titulo">Sobre Nuestra Empresa</h1>

    <div class="seccion-bloque">
        <h2>¿Quiénes Somos?</h2>
        <p><?php echo nl2br(htmlspecialchars($quienes_somos)); ?></p>
    </div>

    <div class="seccion-bloque">
        <h2>Nuestra Historia</h2>
        <p><?php echo nl2br(htmlspecialchars($historia)); ?></p>
    </div>

    <div class="caja-destacada">
        <h2 style="margin-top:0; color:#333; font-size:1.2rem;">Política de Calidad</h2>
        <p style="margin:0; color:#555; line-height:1.6;"><?php echo nl2br(htmlspecialchars($politica_calidad)); ?></p>
    </div>

    <div class="grid-mision-vision">
        <div class="card-mv">
            <h3>Misión</h3>
            <p><?php echo nl2br(htmlspecialchars($mision)); ?></p>
        </div>
        <div class="card-mv">
            <h3>Visión</h3>
            <p><?php echo nl2br(htmlspecialchars($vision)); ?></p>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

</body>
</html>