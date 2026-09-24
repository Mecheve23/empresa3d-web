<?php
if (!isset($conexion)) {
    include 'db.php';
}

// Consultamos la base de datos para obtener el logo activo
$query_header = "SELECT logo FROM empresa_info WHERE id = 1";
$res_header = mysqli_query($conexion, $query_header);
$info_header = mysqli_fetch_assoc($res_header);

// Si no hay logo guardado, usa el por defecto
$logo_actual = (!empty($info_header['logo'])) ? $info_header['logo'] : 'LogoDYF.png';
?>

<header class="navbar">
    <div class="logo-container">
        <a href="index.php" style="display: flex; align-items: center; text-decoration: none;">
            <!-- Muestra el logo dinámico traído desde la base de datos -->
            <img src="img/<?php echo $logo_actual; ?>" alt="Logo Diseña y Fabrica" class="logo-img" style="height: 75px; width: auto; object-fit: contain; display: block;">
        </a>
    </div>
    
    <nav class="nav-links">
        <ul>
            <li><a href="index.php">Inicio</a></li>
            <li><a href="quienes-somos.php">Nosotros</a></li>
            <li><a href="proyectos.php">Proyectos</a></li>
            <li><a href="cotizacion.php">Cotización</a></li>
            <li><a href="tienda.php">Tienda</a></li>
            <li><a href="asistencia.php" style="color: #28a745; font-weight: bold;">Asistencia</a></li>
        </ul>
    </nav>

    <div class="nav-button" style="display: flex; align-items: center; gap: 15px;">
        <a href="admin/login.php" class="btn-login" style="color: #007bff; text-decoration: none; border: 1px solid #007bff; padding: 10px 18px; border-radius: 5px; font-weight: bold; font-size: 0.9rem; transition: all 0.3s; font-family: sans-serif;">
            Iniciar Sesión
        </a>
        <a href="cotizacion.php" class="btn-cotizar">¡Cotiza tu Proyecto 3D!</a>
    </div>
</header>