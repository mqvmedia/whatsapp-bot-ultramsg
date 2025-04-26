<?php
// Recibir datos del webhook
$data = json_decode(file_get_contents('php://input'), true);

// Verificar si recibimos algo
if (!empty($data['data']['from']) && !empty($data['data']['body'])) {
    $telefono = $data['data']['from'];
    $mensaje_recibido = strtolower(trim($data['data']['body']));

    // Aquí defines respuestas automáticas
    $respuesta = '';

    if (strpos($mensaje_recibido, 'hola') !== false) {
        $respuesta = "¡Hola! Bienvenido, ¿en qué puedo ayudarte?";
    } else if (strpos($mensaje_recibido, 'precio') !== false) {
        $respuesta = "Nuestros precios son los siguientes: Producto A \$10, Producto B \$20.";
    } else if (strpos($mensaje_recibido, 'horario') !== false) {
        $respuesta = "Nuestro horario de atención es de lunes a viernes de 9 am a 6 pm.";
    } else {
        $respuesta = "No entendí tu mensaje, ¿podrías reformularlo?";
    }

    // Enviar respuesta usando UltraMsg API
    $token = "mcf13g9vuljvqn08";
    $instance_id = "instance116205";

    $url = "https://api.ultramsg.com/{$instance_id}/messages/chat";

    $payload = json_encode([
        "token" => $token,
        "to" => $telefono,
        "body" => $respuesta,
        "priority" => 10
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
}
?>