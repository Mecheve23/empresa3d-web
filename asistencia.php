<?php 
include 'includes/db.php'; 
include 'includes/header.php'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asistencia IA - Diseña y Fabrica</title>
    <link rel="stylesheet" href="css/estilos.css">
    <style>
        .chat-container { max-width: 600px; margin: 30px auto; background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); overflow: hidden; font-family: sans-serif; }
        .chat-header { background: #075e54; color: white; padding: 15px; display: flex; align-items: center; gap: 10px; }
        .chat-box { padding: 20px; background: #efeae2; min-height: 350px; display: flex; flex-direction: column; gap: 15px; max-height: 450px; overflow-y: auto; }
        .msg { padding: 10px 14px; border-radius: 8px; max-width: 80%; font-size: 0.95rem; line-height: 1.4; box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
        .msg.bot { background: white; align-self: flex-start; color: #333; }
        .msg.user { background: #d9fdd3; align-self: flex-end; color: #111; }
        .chat-input-area { padding: 15px; background: #f0f2f5; border-top: 1px solid #ddd; display: flex; gap: 10px; }
        .chat-input { flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 20px; outline: none; }
        .chat-btn { background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 20px; cursor: pointer; font-weight: bold; }
        .whatsapp-btn { background: #25d366; color: white; border: none; padding: 12px; border-radius: 8px; font-weight: bold; width: 100%; cursor: pointer; display: block; text-align: center; text-decoration: none; margin-top: 10px; }
    </style>
</head>
<body>

    <div class="chat-container">
        <div class="chat-header">
            <div style="font-size: 1.5rem;">🤖</div>
            <div>
                <h3 style="margin: 0; font-size: 1.1rem;">Asistente Virtual IA</h3>
                <span style="font-size: 0.8rem; color: #acf3cd;">● En línea (Filtro de Control)</span>
            </div>
        </div>

        <div class="chat-box" id="chatBox">
            <div class="msg bot">¡Hola! Bienvenido a <strong>Diseña y Fabrica</strong>. Requiero unos datos breves de control antes de comunicarte con el Ing. Christian Rodríguez. ¿Cuál es tu nombre completo?</div>
        </div>

        <div class="chat-input-area" id="inputArea">
            <input type="text" id="userInput" class="chat-input" placeholder="Escribe tu respuesta aquí..." autofocus>
            <button class="chat-btn" onclick="procesarRespuesta()">Enviar</button>
        </div>
    </div>

    <script>
        var paso = 1;
        var datosCliente = { nombre: '', cedula: '', ciudad: '', servicio: '' };

        function procesarRespuesta() {
            var input = document.getElementById('userInput');
            var mensajeUser = input.value.trim();
            
            if (mensajeUser === "") {
                return;
            }

            agregarMensaje(mensajeUser, 'user');
            input.value = "";

            setTimeout(function() {
                if (paso === 1) {
                    datosCliente.nombre = mensajeUser;
                    // Cambio estratégico: Solicitud de Cédula por seguridad y requerimiento del Word
                    agregarMensaje('Mucho gusto, ' + datosCliente.nombre + '. Por seguridad y validación, ¿cuál es tu número de cédula o RUC?', 'bot');
                    paso = 2;
                } else if (paso === 2) {
                    datosCliente.cedula = mensajeUser;
                    agregarMensaje('Perfecto. ¿Desde qué ciudad o provincia nos escribes?', 'bot');
                    paso = 3;
                } else if (paso === 3) {
                    datosCliente.ciudad = mensajeUser;
                    agregarMensaje('Por último, describe brevemente qué proyecto o servicio necesitas (Diseño 3D, Mecanizado, Impresión 3D, etc.):', 'bot');
                    paso = 4;
                } else if (paso === 4) {
                    datosCliente.servicio = mensajeUser;
                    
                    // Armamos la cadena para WhatsApp incluyendo el campo de Cédula/RUC de manera limpia
                    var textoWhatsApp = encodeURIComponent('Hola Ing. Christian, mi nombre es ' + datosCliente.nombre + '. Registré mis datos de control en el Chatbot:\n\n🪪 Cédula/RUC: ' + datosCliente.cedula + '\n📍 Ciudad: ' + datosCliente.ciudad + '\n🛠️ Requerimiento: ' + datosCliente.servicio);
                    var urlWhatsApp = 'https://wa.me/593963884281?text=' + textoWhatsApp;

                    agregarMensaje('¡Excelente! Tu identidad y requerimientos han sido validados con éxito para el control del negocio. Haz clic abajo para iniciar tu atención oficial en WhatsApp.', 'bot');
                    
                    document.getElementById('inputArea').innerHTML = '<a href="' + urlWhatsApp + '" target="_blank" class="whatsapp-btn">💬 Conectar con WhatsApp Business</a>';
                }
            }, 600);
        }

        function agregarMensaje(texto, emisor) {
            var chatBox = document.getElementById('chatBox');
            var div = document.createElement('div');
            div.className = 'msg ' + emisor;
            div.innerHTML = texto;
            chatBox.appendChild(div);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        document.getElementById('userInput').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                procesarRespuesta();
            }
        });
    </script>

    <?php include 'includes/footer.php'; ?>
</body>
</html>