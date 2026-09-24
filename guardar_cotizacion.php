<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Captura y limpieza de datos compartidos
    $tipo_documento = mysqli_real_escape_string($conexion, $_POST['tipo_documento']);
    $celular        = mysqli_real_escape_string($conexion, $_POST['celular']);
    $ubicacion      = mysqli_real_escape_string($conexion, $_POST['ubicacion']);
    $direccion      = mysqli_real_escape_string($conexion, $_POST['direccion']);
    $asunto         = mysqli_real_escape_string($conexion, $_POST['asunto']);
    $descripcion    = mysqli_real_escape_string($conexion, $_POST['descripcion']);
    
    // Inicializamos las variables dinámicas
    $num_cedula      = 'NULL';
    $nombre_apellido = 'NULL';
    $num_ruc         = 'NULL';
    $razon_social    = 'NULL';

    // Asignamos según el tipo de documento seleccionado
    if ($tipo_documento === 'cedula') {
        $num_cedula      = "'" . mysqli_real_escape_string($conexion, $_POST['num_cedula']) . "'";
        $nombre_apellido = "'" . mysqli_real_escape_string($conexion, $_POST['nombre_apellido']) . "'";
    } else {
        $num_ruc      = "'" . mysqli_real_escape_string($conexion, $_POST['num_ruc']) . "'";
        $razon_social = "'" . mysqli_real_escape_string($conexion, $_POST['razon_social']) . "'";
    }

    // --- PROCESAMIENTO DEL ARCHIVO TÉCNICO (.STL, .STEP, .PDF, etc.) ---
    $nombre_archivo_final = "";
    
    if (isset($_FILES['archivo_tecnico']) && $_FILES['archivo_tecnico']['error'] == 0) {
        $nombre_original = $_FILES['archivo_tecnico']['name'];
        $ruta_temporal   = $_FILES['archivo_tecnico']['tmp_name'];
        
        // Obtenemos la extensión del archivo
        $ext = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));
        
        // Extensiones estrictas solicitadas en el Word
        $extensiones_permitidas = array('pdf', 'stl', 'step', 'stp', 'png', 'jpg', 'jpeg', 'mp4', 'avi');
        
        if (in_array($ext, $extensiones_permitidas)) {
            // Creamos la carpeta si no existe
            $carpeta_destino = 'archivos_cotizaciones/';
            if (!file_exists($carpeta_destino)) {
                mkdir($carpeta_destino, 0777, true);
            }
            
            // Renombramos el archivo para evitar duplicados en el servidor
            $nombre_archivo_final = time() . "_" . preg_replace("/[^a-zA-Z0-9.]/", "_", $nombre_original);
            
            // Movemos el archivo de la memoria temporal a la carpeta real
            move_uploaded_file($ruta_temporal, $carpeta_destino . $nombre_archivo_final);
        } else {
            echo "<script>alert('Error: Formato de archivo no permitido. Solo se aceptan PDF, STL, STEP, Imágenes o Videos.'); window.history.back();</script>";
            exit();
        }
    }

    // Insertar datos en la base de datos respetando los campos dinámicos
    $query = "INSERT INTO cotizaciones (tipo_documento, num_cedula, nombre_apellido, num_ruc, razon_social, celular, ubicacion, direccion, asunto, mensaje, archivo_tecnico, fecha) 
              VALUES ('$tipo_documento', $num_cedula, $nombre_apellido, $num_ruc, $razon_social, '$celular', '$ubicacion', '$direccion', '$asunto', '$descripcion', '$nombre_archivo_final', NOW())";

    $resultado = mysqli_query($conexion, $query);

    if ($resultado) {
        echo "<script>alert('¡Cotización enviada con éxito! Su requerimiento técnico ha sido registrado.'); window.location.href='cotizacion.php';</script>";
    } else {
        echo "Error al registrar la cotización: " . mysqli_error($conexion);
    }
}
?>