<?php
// Recibir datos enviados por Whapi
$data = json_decode(file_get_contents('php://input'), true);

// Verificar que recibimos un mensaje
if (!empty($data['messages']) && isset($data['messages'][0]['from']) && isset($data['messages'][0]['text']['body'])) {
    $telefono = $data['messages'][0]['from']; // Número del cliente
    $mensaje_recibido = strtolower(trim($data['messages'][0]['text']['body'])); // Mensaje recibido

    // Respuestas automáticas
    $respuesta = '';

    if (strpos($mensaje_recibido, 'hola') !== false) {
        $respuesta = "¡Hola! Bienvenido, ¿en qué puedo ayudarte?";
    } else if (strpos($mensaje_recibido, 'precio') !== false) {
        $respuesta = "Nuestros precios son: Producto A \$10, Producto B \$20.";
    } else if (strpos($mensaje_recibido, 'horario') !== false) {
        $respuesta = "Nuestro horario de atención es de lunes a viernes de 9 am a 6 pm.";
    } else {
        $respuesta = "No entendí tu mensaje, ¿podrías reformularlo?";
    }

    // Enviar respuesta usando Whapi
    $token = "n4XmdejEpTbDtNAqY36yCEG3soRNXBMn"; // <-- tu token
    $url_api = "https://gate.whapi.cloud"; // <-- URL de API base de Whapi

    $payload = json_encode([
        "to" => $telefono,
        "type" => "text",
        "text" => [
            "body" => $respuesta
        ]
    ]);

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "$url_api/message/text?token=$token",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json"
        ],
    ]);

    $response = curl_exec($curl);
    curl_close($curl);
}
?>
