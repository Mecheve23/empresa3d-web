<?php
session_start();
session_destroy(); // Borra todas las variables de sesión
header("Location: ../index.php"); // Lo manda de regreso a la web pública
exit();
?>