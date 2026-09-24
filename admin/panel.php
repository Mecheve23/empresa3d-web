<?php
// Mantenemos la seguridad: si no está logueado, vuelve al login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// OBTENER DATOS REALES DEL USUARIO POR SU NOMBRE DE USUARIO LOGUEADO
$usuario_logueado = $_SESSION['admin'];
$consulta_u = "SELECT * FROM usuarios WHERE usuario = '$usuario_logueado'";
$resultado_u = mysqli_query($conexion, $consulta_u);
$datos_u = mysqli_fetch_assoc($resultado_u);

// Si la base de datos no tiene foto, usamos una por defecto
$foto_perfil = (!empty($datos_u) && !empty($datos_u['foto_perfil'])) ? $datos_u['foto_perfil'] : 'usuario.png';

// Obtener el nombre visible del usuario
$nombre_mostrar = !empty($datos_u['nombre_completo']) ? $datos_u['nombre_completo'] : (!empty($datos_u['nombre']) ? $datos_u['nombre'] : ucfirst($usuario_logueado));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración - Imprenta 3D</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body style="background-color: #f4f7f6; font-family: sans-serif; padding: 20px;">

    <div style="max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        
        <!-- HEADER DEL PANEL -->
        <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 30px;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <div style="position: relative; width: 65px; height: 65px;">
                    <img src="../img/perfiles/<?php echo $foto_perfil; ?>" 
                         style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 3px solid #007bff; background: #eee;">
                    <div style="width: 16px; height: 16px; background: #28a745; border-radius: 50%; position: absolute; bottom: 2px; right: 2px; border: 2px solid white;"></div>
                </div>
                <div>
                    <h1 style="color: #333; margin: 0; font-size: 1.3rem;">
                        <?php echo htmlspecialchars($nombre_mostrar); ?>
                    </h1>
                    
                    <div style="margin-top: 5px; display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                        <form action="actualizar_perfil.php" method="POST" enctype="multipart/form-data" style="margin:0;">
                            <label style="font-size: 0.7rem; background: #f0f0f0; padding: 3px 8px; border-radius: 4px; cursor: pointer; border: 1px solid #ccc; color: #555;">
                                Cambiar foto
                                <input type="file" name="foto" hidden onchange="this.form.submit()">
                            </label>
                        </form>
                        
                        <a href="borrar_foto.php" 
                           onclick="return confirm('¿Seguro que quieres quitar tu foto de perfil?')" 
                           style="font-size: 0.7rem; color: #dc3545; text-decoration: none; border: 1px solid #dc3545; padding: 2px 8px; border-radius: 4px; background: #fff;">
                             Quitar foto
                        </a>

                        <button onclick="document.getElementById('modalNombre').style.display='flex'" 
                                style="font-size: 0.7rem; color: #007bff; border: 1px solid #007bff; padding: 2px 8px; border-radius: 4px; background: #fff; cursor: pointer; font-weight: bold;">
                             ✏️ Editar nombre
                        </button>
                    </div>
                    
                    <span style="color: #28a745; font-size: 0.8rem; font-weight: bold; display: block; margin-top: 4px;">● Activo ahora</span>
                </div>
            </div>
            <a href="logout.php" style="text-decoration: none; color: #dc3545; font-weight: bold; font-size: 0.9rem; border: 1px solid #dc3545; padding: 5px 15px; border-radius: 5px;">Cerrar Sesión</a>
        </div>

        <!-- MODAL VENTANA FLOTANTE PARA EDITAR NOMBRE -->
        <div id="modalNombre" style="display: none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; z-index: 1000;">
            <div style="background: white; padding: 25px; border-radius: 8px; width: 90%; max-width: 400px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                <h3 style="margin-top: 0; color: #333; font-size: 1.1rem;">Cambiar Nombre de Perfil</h3>
                <form action="actualizar_nombre.php" method="POST">
                    <label style="display: block; margin-bottom: 8px; font-size: 0.85rem; color: #555;">Ingresa tu nombre o razón comercial:</label>
                    <input type="text" name="nuevo_nombre" value="<?php echo htmlspecialchars($nombre_mostrar); ?>" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 15px; box-sizing: border-box;">
                    <div style="text-align: right; display: flex; gap: 10px; justify-content: flex-end;">
                        <button type="button" onclick="document.getElementById('modalNombre').style.display='none'" style="background: #6c757d; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; font-size: 0.85rem;">Cancelar</button>
                        <button type="submit" style="background: #007bff; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; font-size: 0.85rem; font-weight: bold;">Guardar Nombre</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- TÍTULO DE SECCIÓN ADMINISTRATIVA -->
        <div style="margin-top: 10px; margin-bottom: 15px; border-left: 4px solid #fd7e14; padding-left: 10px;">
            <h3 style="color: #333; margin: 0; font-size: 1.1rem;">🛒 Módulo de Ventas y Tienda Virtual</h3>
            <small style="color: #777;">Utiliza este acceso para añadir o dar de baja el stock de artículos con precio al catálogo.</small>
        </div>

        <!-- MÓDULO INTEGRADO DE INVENTARIO COMERCIAL (CON BUSCADOR Y SCROLL INTERNO) -->
        <div style="background: #fffcf9; border: 1px solid #ffe8d6; border-radius: 8px; padding: 20px; margin-bottom: 35px; box-shadow: 0 2px 8px rgba(253, 126, 20, 0.03);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 10px;">
                <h4 style="margin: 0; color: #fd7e14; font-size: 1rem; font-weight: bold;">Artículos Publicados en el Catálogo:</h4>
                
                <div style="display: flex; gap: 10px; align-items: center;">
                    <!-- BUSCADOR DE PRODUCTOS -->
                    <input type="text" id="buscarProducto" onkeyup="filtrarTabla('buscarProducto', 'tablaProductos')" placeholder="🔍 Buscar producto..." style="padding: 6px 12px; border: 1px solid #ccc; border-radius: 5px; font-size: 0.85rem; outline: none; width: 180px;">
                    
                    <a href="subir_producto.php" style="background: #fd7e14; color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; font-weight: bold; font-size: 0.85rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        📦 Añadir Nuevo Producto
                    </a>
                </div>
            </div>

            <!-- CONTENEDOR CON ALTURA MÁXIMA Y SCROLL -->
            <div style="max-height: 320px; overflow-y: auto; border: 1px solid #eee; border-radius: 6px;">
                <table id="tablaProductos" style="width: 100%; border-collapse: collapse; font-size: 0.88rem; background: white;">
                    <thead style="position: sticky; top: 0; background: #f8f9fa; z-index: 1;">
                        <tr style="color: #555; border-bottom: 2px solid #ddd; text-align: left;">
                            <th style="padding: 10px;">Vista</th>
                            <th style="padding: 10px;">Nombre del Producto</th>
                            <th style="padding: 10px;">Precio Público</th>
                            <th style="padding: 10px; text-align: center;">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query_prod = "SELECT * FROM productos ORDER BY id DESC";
                        $res_prod = mysqli_query($conexion, $query_prod);
                        
                        if ($res_prod && mysqli_num_rows($res_prod) > 0) {
                            while ($prod = mysqli_fetch_assoc($res_prod)) {
                                echo "<tr style='border-bottom: 1px solid #eee;'>";
                                echo "<td style='padding: 8px;'><img src='../img/productos/".$prod['imagen']."' style='width: 45px; height: 45px; object-fit: cover; border-radius: 4px; background: #fafafa; border: 1px solid #ddd;'></td>";
                                echo "<td style='padding: 8px; font-weight: bold; color: #333;'>".htmlspecialchars($prod['nombre'])."</td>";
                                echo "<td style='padding: 8px; color: #28a745; font-weight: bold; font-size: 0.95rem;'>$".number_format($prod['precio'], 2)."</td>";
                                echo "<td style='padding: 8px; text-align: center;'>";
                                echo "<a href='eliminar_producto.php?id=".$prod['id']."' onclick=\"return confirm('¿Seguro que deseas eliminar este producto por completo de la tienda comercial?');\" style='background: #dc3545; color: white; text-decoration: none; padding: 5px 12px; border-radius: 4px; font-size: 0.78rem; font-weight: bold; display: inline-block;'>Eliminar</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' style='padding: 20px; text-align: center; color: #999; font-style: italic;'>No hay productos registrados en el inventario.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- SECCIÓN: SUBIR NUEVO PROYECTO 3D -->
        <section style="margin-bottom: 50px; background: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #eee;">
            <h2 style="color: #007bff; margin-top: 0;">Subir Nuevo Proyecto 3D</h2>
            <form action="guardar_proyecto.php" method="POST" enctype="multipart/form-data" id="formProyecto">
                <input type="text" name="titulo" placeholder="Nombre del proyecto (Ej: PS5 Slim)" required style="width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 5px;">
                <textarea name="descripcion" placeholder="Descripción breve..." style="width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 5px; height: 80px;"></textarea>
                
                <input type="file" id="inputOcultoFotos" name="imagenes[]" multiple required style="display: none;">

                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    <button type="button" onclick="document.getElementById('inputSelectorTemp').click();" style="background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold;">
                        📷 Seleccionar / Añadir Fotos
                    </button>
                    <input type="file" id="inputSelectorTemp" multiple accept="image/*" style="display: none;" onchange="agregarFotos(this)">
                    <span id="cant-fotos" style="color: #666; font-size: 0.85rem; font-style: italic;">0 foto(s) seleccionada(s)</span>
                </div>

                <div id="vistaPreviaFotos" style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;"></div>

                <div style="text-align: right;">
                    <button type="submit" style="background: #28a745; color: white; border: none; padding: 10px 25px; border-radius: 5px; font-weight: bold; cursor: pointer;">Publicar en la Web</button>
                </div>
            </form>
        </section>

        <script>
            const contenedorArchivos = new DataTransfer();

            function agregarFotos(input) {
                const archivosNuevos = Array.from(input.files);
                archivosNuevos.forEach(file => { contenedorArchivos.items.add(file); });
                document.getElementById('inputOcultoFotos').files = contenedorArchivos.files;
                input.value = '';
                renderizarVistasPrevias();
            }

            function eliminarFoto(index) {
                contenedorArchivos.items.remove(index);
                document.getElementById('inputOcultoFotos').files = contenedorArchivos.files;
                renderizarVistasPrevias();
            }

            function renderizarVistasPrevias() {
                const contenedor = document.getElementById('vistaPreviaFotos');
                const textoCantidad = document.getElementById('cant-fotos');
                contenedor.innerHTML = '';
                const archivos = contenedorArchivos.files;
                textoCantidad.textContent = `${archivos.length} foto(s) seleccionada(s)`;

                Array.from(archivos).forEach((file, idx) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.style.cssText = 'position: relative; width: 70px; height: 70px; border-radius: 6px; overflow: hidden; border: 1px solid #ccc;';
                        div.innerHTML = `
                            <img src="${e.target.result}" style="width:100%; height:100%; object-fit:cover;">
                            <span onclick="eliminarFoto(${idx})" style="position: absolute; top: 2px; right: 2px; background: rgba(220, 53, 69, 0.9); color: white; border-radius: 50%; width: 18px; height: 18px; font-size: 11px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-weight: bold;">✕</span>
                        `;
                        contenedor.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                });
            }

            // FUNCIÓN REUTILIZABLE PARA FILTRAR TABLAS
            function filtrarTabla(idInput, idTabla) {
                const input = document.getElementById(idInput);
                const filter = input.value.toLowerCase();
                const tabla = document.getElementById(idTabla);
                const tr = tabla.getElementsByTagName("tr");

                for (let i = 1; i < tr.length; i++) {
                    let textoFila = tr[i].textContent || tr[i].innerText;
                    if (textoFila.toLowerCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        </script>

        <!-- EDITAR INFORMACIÓN CORPORATIVA Y BANNER -->
        <?php
        $consulta_info = "SELECT * FROM empresa_info WHERE id = 1";
        $resultado_info = mysqli_query($conexion, $consulta_info);
        $info = mysqli_fetch_assoc($resultado_info);
        ?>
        <section style="margin-bottom: 50px; background: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #eee;">
            <h2 style="color: #007bff; margin-top: 0; margin-bottom: 20px;">Editar Información Corporativa y Banner</h2>
            <form action="actualizar_info.php" method="POST" enctype="multipart/form-data">
                
                <div style="margin-bottom: 20px; background: #eef6ff; padding: 15px; border-radius: 6px; border: 1px solid #b6d4fe;">
                    <h4 style="margin: 0 0 10px 0; color: #007bff;">✏️ Banner Principal de Inicio:</h4>
                    <div style="margin-bottom: 10px;">
                        <label style="font-weight: bold; display: block; margin-bottom: 5px; font-size: 0.85rem;">Título Grande (Texto Azul):</label>
                        <input type="text" name="banner_titulo" value="<?php echo isset($info['banner_titulo']) ? htmlspecialchars($info['banner_titulo']) : 'Impresión 3D de Calidad para tus Ideas'; ?>" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                    </div>
                    <div>
                        <label style="font-weight: bold; display: block; margin-bottom: 5px; font-size: 0.85rem;">Subtítulo o Descripción:</label>
                        <input type="text" name="banner_subtitulo" value="<?php echo isset($info['banner_subtitulo']) ? htmlspecialchars($info['banner_subtitulo']) : 'Fabricamos e imprimimos productos personalizados para clientes industriales y particulares.'; ?>" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                    </div>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="font-weight: bold; display: block; margin-bottom: 5px;">¿Quiénes Somos?:</label>
                    <textarea name="quienes_somos" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-family: sans-serif;" required><?php echo isset($info['quienes_somos']) ? $info['quienes_somos'] : ''; ?></textarea>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="font-weight: bold; display: block; margin-bottom: 5px;">Nuestra Historia:</label>
                    <textarea name="historia" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-family: sans-serif;" required><?php echo isset($info['historia']) ? $info['historia'] : ''; ?></textarea>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="font-weight: bold; display: block; margin-bottom: 5px;">Política de Calidad:</label>
                    <textarea name="politica_calidad" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-family: sans-serif;" required><?php echo isset($info['politica_calidad']) ? $info['politica_calidad'] : ''; ?></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                    <div>
                        <label style="font-weight: bold; display: block; margin-bottom: 5px;">Misión:</label>
                        <textarea name="mision" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-family: sans-serif;" required><?php echo isset($info['mision']) ? $info['mision'] : ''; ?></textarea>
                    </div>
                    <div>
                        <label style="font-weight: bold; display: block; margin-bottom: 5px;">Visión:</label>
                        <textarea name="vision" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-family: sans-serif;" required><?php echo isset($info['vision']) ? $info['vision'] : ''; ?></textarea>
                    </div>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="font-weight: bold; display: block; margin-bottom: 5px;">Enlace de Ubicación (Iframe de Google Maps):</label>
                    <input type="text" name="google_maps" value="<?php echo isset($info['google_maps']) ? htmlspecialchars($info['google_maps']) : ''; ?>" placeholder="Pegar el atributo src de Google Maps..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
                </div>

                <div style="margin-bottom: 25px; background: #fff; padding: 15px; border-radius: 6px; border: 1px solid #ddd;">
                    <label style="font-weight: bold; display: block; margin-bottom: 8px; color: #333;">Logo Actual de la Empresa:</label>
                    <?php if(!empty($info['logo'])): ?>
                        <div style="margin-bottom: 10px;">
                            <img src="../img/<?php echo $info['logo']; ?>" style="height: 60px; max-width: 200px; object-fit: contain; background: #f0f0f0; padding: 5px; border-radius: 4px; border: 1px solid #ccc;">
                        </div>
                    <?php endif; ?>

                    <label style="display: inline-block; background: #fd7e14; color: white; padding: 8px 15px; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 0.85rem;">
                        ⚙️ Seleccionar Nuevo Logo Rectangular
                        <input type="file" name="nuevo_logo" accept="image/*" hidden id="inputLogo" onchange="document.getElementById('file-chosen').textContent = this.files[0].name">
                    </label>
                    <span id="file-chosen" style="margin-left: 10px; color: #666; font-size: 0.85rem; font-style: italic;">Ningún archivo seleccionado</span>
                </div>

                <button type="submit" style="background: #007bff; color: white; border: none; padding: 10px 25px; border-radius: 5px; font-weight: bold; cursor: pointer;">
                    💾 Guardar Cambios Corporativos
                </button>
            </form>
        </section>

        <!-- ADMINISTRAR COLABORADORES CON SCROLL -->
        <section style="margin-bottom: 50px; background: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #eee;">
            <h2 style="color: #007bff; margin-top: 0; margin-bottom: 15px;">Registrar Nuevo Usuario Colaborador</h2>
            <form action="guardar_usuario.php" method="POST">
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; align-items: end;">
                    <div>
                        <label style="font-weight: bold; display: block; margin-bottom: 5px; font-size: 0.9rem;">Nombre del Colaborador:</label>
                        <input type="text" name="nombre_completo" placeholder="Ej: Juan Perez" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                    <div>
                        <label style="font-weight: bold; display: block; margin-bottom: 5px; font-size: 0.9rem;">Nombre de Usuario (Login):</label>
                        <input type="text" name="nuevo_usuario" placeholder="Ej: carlos_diseno" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                    <div>
                        <label style="font-weight: bold; display: block; margin-bottom: 5px; font-size: 0.9rem;">Contraseña de Acceso:</label>
                        <input type="password" name="nueva_clave" placeholder="••••••••" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                </div>
                <div style="margin-top: 15px; text-align: right;">
                    <button type="submit" style="background: #28a745; color: white; border: none; padding: 10px 25px; border-radius: 5px; font-weight: bold; cursor: pointer;">
                        ➕ Crear Usuario Colaborador
                    </button>
                </div>
            </form>

            <div style="margin-top: 25px; border-top: 1px solid #e0e0e0; padding-top: 15px;">
                <h4 style="margin: 0 0 10px 0; color: #555;">Colaboradores con Acceso Activo al Panel:</h4>
                <div style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; border-radius: 6px;">
                    <table style="width: 100%; border-collapse: collapse; background: white; font-size: 0.88rem;">
                        <thead style="position: sticky; top: 0; background: #007bff; color: white;">
                            <tr style="text-align: left;">
                                <th style="padding: 10px;">ID</th>
                                <th style="padding: 10px;">Usuario (Login)</th>
                                <th style="padding: 10px; text-align: center;">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query_u = "SELECT * FROM usuarios ORDER BY id DESC";
                            $res_u = mysqli_query($conexion, $query_u);
                            if ($res_u && mysqli_num_rows($res_u) > 0) {
                                while($u = mysqli_fetch_assoc($res_u)) {
                                    echo "<tr style='border-bottom: 1px solid #eee;'>";
                                    echo "<td style='padding: 8px;'>".$u['id']."</td>";
                                    echo "<td style='padding: 8px; font-weight: bold; color: #333;'>".htmlspecialchars($u['usuario'])."</td>";
                                    echo "<td style='padding: 8px; text-align: center;'>";
                                    
                                    if ($u['usuario'] === $_SESSION['admin']) {
                                        echo "<span style='color: #28a745; font-weight: bold; font-size: 0.8rem;'>● Sesión Actual</span>";
                                    } elseif ($u['id'] == 1 || strtolower($u['usuario']) === 'admin') {
                                        echo "<span style='color: #6c757d; font-weight: bold; font-size: 0.8rem; background: #e9ecef; padding: 3px 8px; border-radius: 4px;'>🔒 Admin Principal</span>";
                                    } else {
                                        echo "<a href='eliminar_usuario.php?id=".$u['id']."' onclick=\"return confirm('¿Seguro que deseas eliminar a este colaborador?');\" style='background: #dc3545; color: white; text-decoration: none; padding: 5px 12px; border-radius: 4px; font-size: 0.78rem; font-weight: bold;'>Eliminar</a>";
                                    }
                                    
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- BANDEJA DE COTIZACIONES CON BUSCADOR Y SCROLL -->
        <section style="margin-bottom: 50px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 10px;">
                <h2 style="color: #007bff; margin: 0; font-size: 1.3rem;">Bandeja de Cotizaciones Recibidas</h2>
                <input type="text" id="buscarCotizacion" onkeyup="filtrarTabla('buscarCotizacion', 'tablaCotizaciones')" placeholder="🔍 Buscar cliente, documento..." style="padding: 6px 12px; border: 1px solid #ccc; border-radius: 5px; font-size: 0.85rem; outline: none; width: 220px;">
            </div>

            <div style="max-height: 380px; overflow-y: auto; border: 1px solid #ddd; border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                <table id="tablaCotizaciones" style="width: 100%; border-collapse: collapse; font-size: 0.88rem; background: white;">
                    <thead style="position: sticky; top: 0; background-color: #007bff; color: white; z-index: 1;">
                        <tr>
                            <th style="padding: 10px; text-align: left;">Fecha</th>
                            <th style="padding: 10px; text-align: left;">Identificación</th>
                            <th style="padding: 10px; text-align: left;">Cliente / Razón Social</th>
                            <th style="padding: 10px; text-align: left;">Contacto / Dirección</th>
                            <th style="padding: 10px; text-align: left;">Servicio / Descripción</th>
                            <th style="padding: 10px; text-align: center;">Plano Técnico</th>
                            <th style="padding: 10px; text-align: center;">Acción</th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM cotizaciones ORDER BY fecha DESC";
                        $res = mysqli_query($conexion, $query);
                        
                        if ($res && mysqli_num_rows($res) > 0) {
                            while($row = mysqli_fetch_array($res)) {
                                echo "<tr style='border-bottom: 1px solid #eee;'>";
                                echo "<td style='padding: 10px; white-space: nowrap;'>".date('d/m/Y H:i', strtotime($row['fecha']))."</td>";
                                echo "<td style='padding: 10px;'>";
                                if(isset($row['tipo_documento']) && $row['tipo_documento'] === 'cedula') {
                                    echo "<span style='background: #e3f2fd; color: #0d47a1; padding: 2px 5px; border-radius: 3px; font-size: 0.7rem; font-weight: bold;'>CÉDULA</span><br>".$row['num_cedula'];
                                } elseif (isset($row['tipo_documento']) && $row['tipo_documento'] === 'ruc') {
                                    echo "<span style='background: #e8f5e9; color: #1b5e20; padding: 2px 5px; border-radius: 3px; font-size: 0.7rem; font-weight: bold;'>RUC</span><br>".$row['num_ruc'];
                                } else {
                                    echo "<span style='color: #aaa;'>Sin doc</span>";
                                }
                                echo "</td>";
                                echo "<td style='padding: 10px;'><strong>".htmlspecialchars(isset($row['nombre_apellido']) ? $row['nombre_apellido'] : (isset($row['razon_social']) ? $row['razon_social'] : $row['nombre']))."</strong></td>";
                                echo "<td style='padding: 10px; max-width: 180px;'>📱 ".$row['celular']."<br><span style='font-size: 0.75rem; color: #666;'>".$row['direccion']."</span></td>";
                                echo "<td style='padding: 10px; max-width: 220px;'><span style='font-size: 0.8rem; color: #333;'>".htmlspecialchars($row['mensaje'])."</span></td>";
                                echo "<td style='padding: 10px; text-align: center;'>";
                                if(!empty($row['archivo_tecnico'])) {
                                    $ext = strtolower(pathinfo($row['archivo_tecnico'], PATHINFO_EXTENSION));
                                    echo "<a href='../archivos_cotizaciones/".$row['archivo_tecnico']."' download style='background: #fd7e14; color: white; padding: 4px 8px; text-decoration: none; border-radius: 4px; font-size: 0.72rem; font-weight: bold;'>💾 .".strtoupper($ext)."</a>";
                                } else {
                                    echo "<span style='color: #aaa; font-style: italic; font-size: 0.75rem;'>Sin archivo</span>";
                                }
                                echo "</td>";
                                echo "<td style='padding: 10px; text-align: center;'>
                                        <a href='borrar_cotizacion.php?id=".$row['id']."' onclick='return confirm(\"¿Deseas eliminar permanentemente esta solicitud?\")' style='color: white; background: #dc3545; padding: 4px 8px; text-decoration: none; border-radius: 4px; font-size: 0.72rem; font-weight: bold;'>Eliminar</a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' style='padding: 20px; text-align: center; color: #999;'>No hay cotizaciones recibidas.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- PROYECTOS PUBLICADOS CON CONTENEDOR DE SCROLL DE TRES FILAS -->
        <h2 style="color: #333; text-align: center; margin-bottom: 15px; font-size: 1.3rem;">Proyectos Publicados Actuales</h2>
        <div style="max-height: 280px; overflow-y: auto; padding: 10px; border: 1px solid #eee; border-radius: 8px; background: #fafafa;">
            <div style="display: flex; flex-wrap: wrap; gap: 15px; justify-content: center;">
                <?php
                $query_p = "SELECT * FROM proyectos ORDER BY id DESC";
                $res_p = mysqli_query($conexion, $query_p);
                while($reg = mysqli_fetch_array($res_p)) {
                    echo "<div style='border: 1px solid #ddd; padding: 10px; border-radius: 8px; text-align: center; background: #fff; width: 150px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);'>";
                    echo "<img src='../img/proyectos/".$reg['imagen']."' style='width: 100%; height: 85px; object-fit: cover; border-radius: 4px;'>";
                    echo "<p style='font-weight: bold; margin: 6px 0; font-size: 0.8rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'>".htmlspecialchars($reg['titulo'])."</p>";
                    echo "<a href='borrar_proyecto.php?id=".$reg['id']."' onclick='return confirm(\"¿Borrar proyecto?\")' style='color: #dc3545; text-decoration: none; font-size: 0.75rem; font-weight: bold;'>[Eliminar]</a>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>

    </div>
</body>
</html>