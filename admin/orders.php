<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
    header('Location: login.php');
    exit;
}
require_once '../includes/db.php';

$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$_POST['status'], (int)$_POST['order_id']]);
    $success = 'Estado del pedido #' . (int)$_POST['order_id'] . ' actualizado a: ' . $_POST['status'];
}

$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';
$sql = "SELECT o.*, c.name AS customer_name, c.email, c.phone, c.address
        FROM orders o
        LEFT JOIN customers c ON c.id = o.customer_id";
$params = [];
if ($statusFilter) {
    $sql .= " WHERE o.status = ?";
    $params[] = $statusFilter;
}
$sql .= " ORDER BY o.created_at DESC";
$orders = $pdo->prepare($sql);
$orders->execute($params);
$orders = $orders->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin · Pedidos</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: system-ui; background: #fafafa; padding: 2rem; }
        .container { max-width: 1100px; margin: 0 auto; }
        h1 { font-weight: 400; margin-bottom: 1.5rem; }
        .nav-admin { display: flex; gap: 1.5rem; margin-bottom: 2rem; }
        .nav-admin a { color: #555; text-decoration: none; font-size: 0.9rem; }
        .nav-admin a:hover { color: #000; }
        .success { color: #16a34a; margin-bottom: 1rem; }
        .filters { display: flex; gap: 0.5rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
        .filters a { padding: 0.4rem 1rem; border: 1px solid #ddd; border-radius: 2rem; text-decoration: none; color: #555; font-size: 0.85rem; }
        .filters a:hover, .filters a.active { background: #000; color: white; border-color: #000; }
        .order-card { background: white; border-radius: 1rem; padding: 1.5rem; margin-bottom: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
        .order-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.8rem; flex-wrap: wrap; gap: 0.5rem; }
        .order-id { font-weight: 600; font-size: 1.1rem; }
        .order-date { color: #888; font-size: 0.85rem; }
        .status-badge { padding: 0.25rem 0.8rem; border-radius: 2rem; font-size: 0.8rem; font-weight: 500; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-confirmed { background: #dbeafe; color: #1e40af; }
        .status-shipped { background: #e0e7ff; color: #3730a3; }
        .status-delivered { background: #dcfce7; color: #166534; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        .order-body { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem; }
        .order-info { font-size: 0.85rem; }
        .order-info strong { display: block; color: #555; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.2rem; }
        .order-info p { margin-bottom: 0.3rem; }
        .order-total { font-size: 1.2rem; font-weight: 600; text-align: right; }
        .status-form { display: flex; gap: 0.5rem; align-items: center; }
        .status-form select { padding: 0.3rem 0.5rem; border: 1px solid #ddd; border-radius: 0.5rem; font-size: 0.85rem; }
        .status-form button { background: #000; color: white; border: none; padding: 0.3rem 1rem; border-radius: 2rem; cursor: pointer; font-size: 0.8rem; }
        .status-form button:hover { opacity: 0.85; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 0.5rem; font-size: 0.85rem; }
        .items-table th { text-align: left; padding: 0.4rem 0.3rem; border-bottom: 1px solid #eee; color: #888; font-weight: 500; }
        .items-table td { padding: 0.3rem; border-bottom: 1px solid #f5f5f5; }
        .empty { text-align: center; padding: 3rem; color: #aaa; }
        @media (max-width: 768px) { .order-body { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<div class="container">
    <h1>Pedidos</h1>
    <div class="nav-admin">
        <a href="dashboard.php">Personalización</a>
        <a href="products.php">Productos</a>
        <a href="orders.php">Pedidos</a>
        <a href="logout.php" style="margin-left:auto;color:#aaa;">Cerrar sesión</a>
    </div>

    <?php if ($success): ?><div class="success"><?= $success ?></div><?php endif; ?>

    <div class="filters">
        <a href="orders.php" class="<?= !$statusFilter ? 'active' : '' ?>">Todos</a>
        <a href="?status=pending" class="<?= $statusFilter === 'pending' ? 'active' : '' ?>">Pendientes</a>
        <a href="?status=confirmed" class="<?= $statusFilter === 'confirmed' ? 'active' : '' ?>">Confirmados</a>
        <a href="?status=shipped" class="<?= $statusFilter === 'shipped' ? 'active' : '' ?>">Enviados</a>
        <a href="?status=delivered" class="<?= $statusFilter === 'delivered' ? 'active' : '' ?>">Entregados</a>
        <a href="?status=cancelled" class="<?= $statusFilter === 'cancelled' ? 'active' : '' ?>">Cancelados</a>
    </div>

    <?php if (count($orders) === 0): ?>
        <div class="empty">No hay pedidos <?= $statusFilter ? 'con este estado' : '' ?></div>
    <?php endif; ?>

    <?php foreach ($orders as $o):
        $itemsStmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $itemsStmt->execute([$o['id']]);
        $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
        <div class="order-card">
            <div class="order-header">
                <div>
                    <span class="order-id">Pedido #<?= $o['id'] ?></span>
                    <span class="order-date"><?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></span>
                </div>
                <span class="status-badge status-<?= $o['status'] ?>"><?= ucfirst($o['status']) ?></span>
            </div>

            <div class="order-body">
                <div class="order-info">
                    <strong>Cliente</strong>
                    <p><?= $o['customer_name'] ?></p>
                    <p><?= $o['email'] ?></p>
                    <?php if ($o['phone']): ?><p>📞 <?= $o['phone'] ?></p><?php endif; ?>
                    <?php if ($o['address']): ?><p>📍 <?= $o['address'] ?></p><?php endif; ?>
                </div>
                <div class="order-info">
                    <strong>Productos</strong>
                    <table class="items-table">
                        <thead>
                            <tr><th>Producto</th><th>Precio</th><th>Cant.</th><th>Subtotal</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td><?= $item['product_name'] ?></td>
                                    <td>$<?= number_format($item['price'], 2) ?></td>
                                    <td><?= $item['quantity'] ?></td>
                                    <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php if ($o['notes']): ?>
                        <p style="margin-top:0.5rem;color:#888;"><strong>Notas:</strong> <?= $o['notes'] ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:0.5rem;border-top:1px solid #f0f0f0;padding-top:1rem;">
                <div class="order-total">Total: $<?= number_format($o['total'], 2) ?></div>
                <form method="POST" class="status-form">
                    <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                    <select name="status">
                        <option value="pending" <?= $o['status'] === 'pending' ? 'selected' : '' ?>>Pendiente</option>
                        <option value="confirmed" <?= $o['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmado</option>
                        <option value="shipped" <?= $o['status'] === 'shipped' ? 'selected' : '' ?>>Enviado</option>
                        <option value="delivered" <?= $o['status'] === 'delivered' ? 'selected' : '' ?>>Entregado</option>
                        <option value="cancelled" <?= $o['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelado</option>
                    </select>
                    <button type="submit">Actualizar</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
