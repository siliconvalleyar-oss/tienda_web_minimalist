<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

$data = json_decode(file_get_contents('php://input'), true);
$message = trim($data['message']);
$visitor_name = trim($data['name']);

if (empty($message)) {
    echo json_encode(['error' => 'Mensaje vacío']);
    exit;
}

$stmt = $pdo->prepare("INSERT INTO chat_messages (visitor_name, message) VALUES (?, ?)");
$stmt->execute([$visitor_name, $message]);

$response = '';
$lowerMsg = strtolower($message);
if (strpos($lowerMsg, 'hola') !== false || strpos($lowerMsg, 'buenas') !== false) {
    $response = "¡Hola! Gracias por escribir. ¿En qué puedo ayudarte? Puedes preguntar por horarios, productos o envíos.";
} elseif (strpos($lowerMsg, 'precio') !== false || strpos($lowerMsg, 'costo') !== false) {
    $response = "Los precios están en la tienda, pero escríbenos por WhatsApp para ofertas especiales.";
} elseif (strpos($lowerMsg, 'envío') !== false || strpos($lowerMsg, 'entrega') !== false) {
    $response = "Hacemos envíos a todo el país. El costo depende de tu ubicación. ¿Te ayudo por WhatsApp?";
} elseif (strpos($lowerMsg, 'horario') !== false) {
    $response = "Atención de lunes a viernes de 9 a 18 hs. Los fines de semana consultas por WhatsApp.";
} else {
    $response = "Gracias por tu mensaje. Te responderemos a la brevedad por WhatsApp. Mientras, revisa nuestros productos.";
}

$update = $pdo->prepare("UPDATE chat_messages SET response = ? WHERE id = (SELECT LAST_INSERT_ID())");
$update->execute([$response]);

$whatsapp_number = getSetting('whatsapp_number', $pdo);
$forwarded = false;

if ($whatsapp_number) {
    $clean_number = preg_replace('/[^0-9]/', '', $whatsapp_number);
    $forwarded = true;
}

$mark = $pdo->prepare("UPDATE chat_messages SET forwarded_to_whatsapp = ? WHERE id = (SELECT LAST_INSERT_ID())");
$mark->execute([$forwarded ? 1 : 0]);

echo json_encode([
    'response' => $response,
    'whatsapp_link' => $whatsapp_number ? "https://wa.me/$whatsapp_number?text=" . urlencode("Mensaje de $visitor_name: $message") : null
]);
?>
