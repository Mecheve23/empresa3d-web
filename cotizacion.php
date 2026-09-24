<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'includes/db.php';
include 'includes/header.php';

$asunto_predeterminado = isset($_GET['asunto']) ? $_GET['asunto'] : '';
$mensaje_exito = "";
$mensaje_error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo_doc    = mysqli_real_escape_string($conexion, $_POST['tipo_documento']);
    $num_cedula  = ($tipo_doc === 'cedula') ? mysqli_real_escape_string($conexion, $_POST['num_cedula']) : '';
    $num_ruc     = ($tipo_doc === 'ruc') ? mysqli_real_escape_string($conexion, $_POST['num_ruc']) : '';
    $nombre      = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $celular     = mysqli_real_escape_string($conexion, $_POST['celular']);
    $ubicacion   = mysqli_real_escape_string($conexion, $_POST['ubicacion']);
    $direccion   = mysqli_real_escape_string($conexion, $_POST['direccion']);
    $asunto      = mysqli_real_escape_string($conexion, $_POST['asunto']);
    $mensaje     = mysqli_real_escape_string($conexion, $_POST['mensaje']);

    // Procesamiento de archivo técnico (.STL, .STEP, .PDF, etc.)
    $nombre_archivo_subido = "";
    if (isset($_FILES['archivo_tecnico']) && $_FILES['archivo_tecnico']['error'] === UPLOAD_ERR_OK) {
        $directorio_destino = 'archivos_cotizaciones/';
        
        if (!file_exists($directorio_destino)) {
            mkdir($directorio_destino, 0777, true);
        }

        $extension = pathinfo($_FILES['archivo_tecnico']['name'], PATHINFO_EXTENSION);
        $nombre_archivo_subido = time() . '_' . uniqid() . '.' . $extension;
        $ruta_final = $directorio_destino . $nombre_archivo_subido;

        move_uploaded_file($_FILES['archivo_tecnico']['tmp_name'], $ruta_final);
    }

    // Insertar cotización en la base de datos
    $query_insert = "INSERT INTO cotizaciones (tipo_documento, num_cedula, num_ruc, nombre_apellido, celular, ubicacion, direccion, mensaje, archivo_tecnico, fecha) 
                    VALUES ('$tipo_doc', '$num_cedula', '$num_ruc', '$nombre', '$celular', '$ubicacion', '$direccion', '$asunto - $mensaje', '$nombre_archivo_subido', NOW())";

    if (mysqli_query($conexion, $query_insert)) {
        $mensaje_exito = "¡Tu solicitud de cotización ha sido enviada con éxito! Nos pondremos en contacto contigo a la brevedad.";
    } else {
        $mensaje_error = "Hubo un problema al procesar tu solicitud. Por favor, inténtalo de nuevo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Cotización - Diseña y Fabrica</title>
    <link rel="stylesheet" href="css/estilos.css">
    <style>
        .campo-doc { display: none; }
    </style>
</head>
<body style="background-color: #f8f9fa; font-family: sans-serif;">

<main style="max-width: 1100px; margin: 30px auto; padding: 0 20px;">

    <!-- CONTENEDOR PRINCIPAL DEL FORMULARIO -->
    <div style="max-width: 650px; margin: 0 auto; background: #ffffff; padding: 35px 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #e0e0e0;">

        <!-- BOTÓN CON FLECHA PARA REGRESAR AL INICIO -->
        <div style="margin-bottom: 20px;">
            <a href="index.php" style="display: inline-flex; align-items: center; gap: 6px; text-decoration: none; color: #007bff; font-weight: 600; font-size: 0.9rem; transition: color 0.2s;">
                <span style="font-size: 1.2rem;">←</span> Volver al Inicio
            </a>
        </div>

        <!-- TÍTULO -->
        <h2 style="color: #007bff; text-align: center; margin-top: 0; margin-bottom: 25px; font-weight: bold; font-size: 1.8rem;">
            Solicitud de Cotización
        </h2>

        <?php if (!empty($mensaje_exito)): ?>
            <div style="background: #d4edda; color: #155724; padding: 12px 15px; border-radius: 6px; border: 1px solid #c3e6cb; margin-bottom: 20px; font-size: 0.9rem; text-align: center;">
                ✔ <?php echo $mensaje_exito; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($mensaje_error)): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 12px 15px; border-radius: 6px; border: 1px solid #f5c6cb; margin-bottom: 20px; font-size: 0.9rem; text-align: center;">
                ⚠️ <?php echo $mensaje_error; ?>
            </div>
        <?php endif; ?>

        <form action="cotizacion.php" method="POST" enctype="multipart/form-data">

            <!-- TIPO DE DOCUMENTO -->
            <div style="margin-bottom: 18px;">
                <label style="display: block; font-weight: bold; color: #333; margin-bottom: 6px; font-size: 0.9rem;">
                    Tipo de Documentación obligatoria:
                </label>
                <select name="tipo_documento" id="tipo_documento" onchange="cambiarTipoDoc()" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 0.9rem; outline: none; box-sizing: border-box;">
                    <option value="cedula" selected>Cédula de Identidad</option>
                    <option value="ruc">RUC (Registro Único de Contribuyentes)</option>
                </select>
            </div>

            <!-- NÚMERO DE CÉDULA -->
            <div id="box_cedula" class="campo-doc" style="display: block; margin-bottom: 18px;">
                <label style="display: block; font-weight: bold; color: #333; margin-bottom: 6px; font-size: 0.9rem;">
                    Número de Cédula:
                </label>
                <input type="text" name="num_cedula" placeholder="0953657855" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 0.9rem; box-sizing: border-box;">
            </div>

            <!-- NÚMERO DE RUC -->
            <div id="box_ruc" class="campo-doc" style="display: none; margin-bottom: 18px;">
                <label style="display: block; font-weight: bold; color: #333; margin-bottom: 6px; font-size: 0.9rem;">
                    Número de RUC:
                </label>
                <input type="text" name="num_ruc" placeholder="0953657855001" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 0.9rem; box-sizing: border-box;">
            </div>

            <!-- NOMBRE Y APELLIDO / RAZÓN SOCIAL -->
            <div style="margin-bottom: 18px;">
                <label id="lbl_nombre" style="display: block; font-weight: bold; color: #333; margin-bottom: 6px; font-size: 0.9rem;">
                    Nombre y Apellido:
                </label>
                <input type="text" name="nombre" placeholder="Ingresa tu nombre completo..." required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 0.9rem; box-sizing: border-box;">
            </div>

            <!-- CELULAR Y UBICACIÓN EN 2 COLUMNAS -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 18px;">
                <div>
                    <label style="display: block; font-weight: bold; color: #333; margin-bottom: 6px; font-size: 0.9rem;">
                        Celular de Contacto:
                    </label>
                    <input type="text" name="celular" placeholder="0991366695" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 0.9rem; box-sizing: border-box;">
                </div>
                <div>
                    <label style="display: block; font-weight: bold; color: #333; margin-bottom: 6px; font-size: 0.9rem;">
                        Ciudad / Provincia / País:
                    </label>
                    <input type="text" name="ubicacion" value="Guayas/Guayaquil/Ecuador" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 0.9rem; box-sizing: border-box;">
                </div>
            </div>

            <!-- DIRECCIÓN DE ENTREGA -->
            <div style="margin-bottom: 18px;">
                <label style="display: block; font-weight: bold; color: #333; margin-bottom: 6px; font-size: 0.9rem;">
                    Dirección de Entrega:
                </label>
                <input type="text" name="direccion" placeholder="Ej: Urbanización Santa Leonor Mz 4 V12" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 0.9rem; box-sizing: border-box;">
            </div>

            <!-- ASUNTO / SERVICIO -->
            <div style="margin-bottom: 18px;">
                <label style="display: block; font-weight: bold; color: #333; margin-bottom: 6px; font-size: 0.9rem;">
                    Asunto / Servicio:
                </label>
                <select name="asunto" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 0.9rem; outline: none; box-sizing: border-box;">
                    <option value="Servicio de Diseño de piezas" <?php echo ($asunto_predeterminado === 'Servicio de Diseño de piezas') ? 'selected' : ''; ?>>Servicio de Diseño de piezas</option>
                    <option value="Servicio de Impresión 3D" <?php echo ($asunto_predeterminado === 'Servicio de Impresión 3D') ? 'selected' : ''; ?>>Servicio de Impresión 3D</option>
                    <option value="Prototipado e Ingeniería Inversa" <?php echo ($asunto_predeterminado === 'Prototipado e Ingeniería Inversa') ? 'selected' : ''; ?>>Prototipado e Ingeniería Inversa</option>
                    <option value="Venta de Repuestos Personalizados" <?php echo ($asunto_predeterminado === 'Venta de Repuestos Personalizados') ? 'selected' : ''; ?>>Venta de Repuestos Personalizados</option>
                </select>
            </div>

            <!-- DESCRIPCIÓN DE LA SOLICITUD -->
            <div style="margin-bottom: 18px;">
                <label style="display: block; font-weight: bold; color: #333; margin-bottom: 6px; font-size: 0.9rem;">
                    Descripción de la solicitud:
                </label>
                <textarea name="mensaje" rows="4" placeholder="Detalla los materiales, tolerancias, medidas o especificaciones de tu proyecto..." required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 0.9rem; font-family: sans-serif; box-sizing: border-box;"></textarea>
            </div>

            <!-- CARGAR ARCHIVOS TÉCNICOS -->
            <div style="margin-bottom: 25px; background: #eef6ff; padding: 15px; border-radius: 8px; border: 1px dashed #007bff;">
                <label style="display: block; font-weight: bold; color: #007bff; margin-bottom: 4px; font-size: 0.9rem;">
                    Cargar Archivos Técnicos:
                </label>
                <small style="color: #666; display: block; margin-bottom: 10px; font-size: 0.8rem;">
                    Formatos permitidos: PDF, STL, STEP, Imágenes y Videos.
                </small>
                
                <input type="file" name="archivo_tecnico" id="inputArchivo" onchange="mostrarNombreArchivo(this)" style="display: none;">
                
                <button type="button" onclick="document.getElementById('inputArchivo').click();" style="background: #ffffff; color: #333; border: 1px solid #ccc; padding: 8px 15px; border-radius: 4px; font-size: 0.85rem; cursor: pointer; font-weight: 500;">
                    Seleccionar archivo
                </button>
                <span id="nombre_archivo_seleccionado" style="margin-left: 10px; color: #555; font-size: 0.85rem; font-style: italic;">Ningún archivo seleccionado</span>
            </div>

            <!-- BOTÓN DE ENVIAR -->
            <button type="submit" style="width: 100%; background: #007bff; color: white; border: none; padding: 13px; border-radius: 6px; font-size: 1rem; font-weight: bold; cursor: pointer; box-shadow: 0 2px 6px rgba(0, 123, 255, 0.2);">
                Enviar Solicitud de Cotización
            </button>

        </form>

    </div>

</main>

<script>
    function cambiarTipoDoc() {
        const tipo = document.getElementById('tipo_documento').value;
        const boxCedula = document.getElementById('box_cedula');
        const boxRuc = document.getElementById('box_ruc');
        const lblNombre = document.getElementById('lbl_nombre');

        if (tipo === 'cedula') {
            boxCedula.style.display = 'block';
            boxRuc.style.display = 'none';
            lblNombre.textContent = 'Nombre y Apellido:';
        } else {
            boxCedula.style.display = 'none';
            boxRuc.style.display = 'block';
            lblNombre.textContent = 'Razón Social / Nombre Comercial:';
        }
    }

    function mostrarNombreArchivo(input) {
        const span = document.getElementById('nombre_archivo_seleccionado');
        if (input.files && input.files[0]) {
            span.textContent = input.files[0].name;
            span.style.color = '#007bff';
            span.style.fontWeight = 'bold';
        } else {
            span.textContent = 'Ningún archivo seleccionado';
            span.style.color = '#555';
            span.style.fontWeight = 'normal';
        }
    }
</script>

<?php include 'includes/footer.php'; ?>

</body>
</html>