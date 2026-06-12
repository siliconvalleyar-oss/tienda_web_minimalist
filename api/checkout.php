<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$phone = trim($data['phone'] ?? '');
$address = trim($data['address'] ?? '');
$notes = trim($data['notes'] ?? '');
$items = $data['items'] ?? [];

if (!$name || !$email || !$address || empty($items)) {
    http_response_code(400);
    echo json_encode(['error' => 'Completá todos los campos obligatorios y agregá productos al carrito.']);
    exit;
}

$pdo->beginTransaction();

try {
    // Create or find customer
    $stmt = $pdo->prepare("SELECT id FROM customers WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($customer) {
        $customerId = $customer['id'];
        $stmt = $pdo->prepare("UPDATE customers SET name = ?, phone = ?, address = ? WHERE id = ?");
        $stmt->execute([$name, $phone, $address, $customerId]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO customers (name, email, phone, address) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $phone, $address]);
        $customerId = $pdo->lastInsertId();
    }

    $total = 0;
    foreach ($items as $item) {
        $total += floatval($item['price']) * intval($item['qty']);
    }

    $stmt = $pdo->prepare("INSERT INTO orders (customer_id, total, notes) VALUES (?, ?, ?)");
    $stmt->execute([$customerId, $total, $notes]);
    $orderId = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity) VALUES (?, ?, ?, ?, ?)");
    foreach ($items as $item) {
        $stmt->execute([
            $orderId,
            intval($item['id']),
            $item['name'],
            floatval($item['price']),
            intval($item['qty'])
        ]);
    }

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'order_id' => $orderId,
        'total' => $total
    ]);

} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error' => 'Error al procesar el pedido: ' . $e->getMessage()]);
}
