<?php
// Iniciamos sesión por si acaso
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Variables para controlar los mensajes informativos en pantalla
$alerta_exito = false;
$alerta_error = false;

// 1. Revisamos si viene de cambiar la contraseña con éxito (restablecer.php)
if (isset($_GET['status']) && $_GET['status'] == 'cambiada') {
    $alerta_exito = true;
}

// 2. Revisamos si auth.php lo rebotó por datos incorrectos
if (isset($_GET['error']) && $_GET['error'] == 'datos_incorrectos') {
    $alerta_error = true;
}

// Verificación de sesión activa para dar acceso directo
$sesion_activa = isset($_SESSION['admin']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Administrativo - Diseña y Fabrica</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #f4f7f6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: #ffffff;
            width: 100%;
            max-width: 420px;
            padding: 40px 35px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 123, 255, 0.1);
            border: 1px solid #e9ecef;
            box-sizing: border-box;
            position: relative;
        }

        .login-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .badge-brand {
            display: inline-block;
            background: #eef6ff;
            color: #007bff;
            font-weight: 700;
            font-size: 0.75rem;
            padding: 5px 12px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
            border: 1px solid #cce5ff;
        }

        .login-icon {
            width: 65px;
            height: 65px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px auto;
            font-size: 1.8rem;
            box-shadow: 0 6px 15px rgba(0, 123, 255, 0.25);
        }

        .login-title {
            margin: 0;
            color: #1a1a1a;
            font-size: 1.6rem;
            font-weight: 700;
        }

        .login-subtitle {
            margin: 6px 0 0 0;
            color: #6c757d;
            font-size: 0.88rem;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            color: #007bff;
            font-size: 1.1rem;
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px 12px 42px;
            border: 1.5px solid #dde2e5;
            border-radius: 10px;
            font-size: 0.92rem;
            box-sizing: border-box;
            outline: none;
            transition: all 0.2s ease;
            background: #fafafa;
        }

        .form-control:focus {
            border-color: #007bff;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.12);
        }

        .btn-submit {
            width: 100%;
            background: #007bff;
            color: #ffffff;
            border: none;
            padding: 13px;
            border-radius: 10px;
            font-size: 0.98rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2);
            margin-top: 8px;
        }

        .btn-submit:hover {
            background: #0056b3;
            box-shadow: 0 6px 16px rgba(0, 123, 255, 0.3);
            transform: translateY(-1px);
        }

        .login-footer {
            margin-top: 22px;
            text-align: center;
            border-top: 1px solid #f0f0f0;
            padding-top: 16px;
        }

        .login-footer a {
            color: #007bff;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        .back-link {
            display: inline-block;
            margin-top: 12px;
            color: #6c757d !important;
            font-weight: normal !important;
            font-size: 0.82rem !important;
        }

        .active-session-box {
            background: #eef6ff;
            border: 1px solid #b6d4fe;
            padding: 18px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <div class="login-card">
        
        <div class="login-header">
            <span class="badge-brand">Diseña y Fabrica 3D</span>
            <div class="login-icon">🔒</div>
            <h1 class="login-title">Inicia Sesión</h1>
            <p class="login-subtitle">Acceso al Panel de Administración</p>
        </div>

        <?php if ($alerta_exito): ?>
            <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 0.85rem; text-align: center; border: 1px solid #c3e6cb;">
                ¡Contraseña cambiada con éxito! Ya puedes ingresar con tu nueva clave.
            </div>
        <?php endif; ?>

        <?php if ($alerta_error): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 0.85rem; text-align: center; border: 1px solid #f5c6cb;">
                El usuario o la contraseña ingresada no son correctos.
            </div>
        <?php endif; ?>

        <?php if ($sesion_activa): ?>
            <!-- SI YA TIENES SESIÓN, TE PERMITE IR AL PANEL O CERRAR SESIÓN -->
            <div class="active-session-box">
                <p style="margin: 0 0 10px 0; color: #004085; font-size: 0.88rem; font-weight: bold;">
                    👋 Ya estás logueado como: <u><?php echo htmlspecialchars($_SESSION['admin']); ?></u>
                </p>
                <a href="panel.php" style="display: block; background: #007bff; color: white; text-decoration: none; padding: 9px; border-radius: 6px; font-weight: bold; font-size: 0.85rem; margin-bottom: 8px;">
                    Ir al Panel de Control →
                </a>
                <a href="logout.php" style="display: block; background: #dc3545; color: white; text-decoration: none; padding: 7px; border-radius: 6px; font-weight: bold; font-size: 0.8rem;">
                    🚪 Cerrar Sesión
                </a>
            </div>
        <?php else: ?>

            <!-- FORMULARIO CONECTADO A TU AUTH.PHP ORIGINAL -->
            <form action="auth.php" method="POST">
                
                <div class="form-group">
                    <label for="usuario">Usuario de Acceso</label>
                    <div class="input-wrapper">
                        <span class="input-icon">👤</span>
                        <input type="text" id="usuario" name="usuario" class="form-control" placeholder="Ej: Mecheve_21" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="input-wrapper">
                        <span class="input-icon">🔑</span>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Ingresa tu contraseña" required>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Entrar al Sistema</button>
                
                <div style="margin-top: 15px; text-align: center;">
                    <a href="recuperar.php" style="color: #007bff; text-decoration: none; font-size: 0.85rem; font-weight: 500;">¿Olvidaste tu contraseña?</a>
                </div>

            </form>

        <?php endif; ?>

        <div class="login-footer">
            <a href="../index.php" class="back-link">← Volver al inicio de la web</a>
        </div>

    </div>

</body>
</html>