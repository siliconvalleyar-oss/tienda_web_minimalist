<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';

$search = trim($_GET['search'] ?? '');
$category = intval($_GET['category'] ?? 0);
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = min(20, max(1, intval($_GET['per_page'] ?? 8)));
$offset = ($page - 1) * $perPage;

$where = [];
$params = [];

if ($search) {
    $where[] = '(p.name LIKE ? OR p.description LIKE ?)';
    $params[] = "%$search%";
    $params[] = "%$search%";
}
if ($category > 0) {
    $where[] = 'p.category_id = ?';
    $params[] = $category;
}

$whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$countStmt = $pdo->prepare("SELECT COUNT(*) FROM products p $whereClause");
$countStmt->execute($params);
$total = (int)$countStmt->fetchColumn();

$stmt = $pdo->prepare("
    SELECT p.id, p.name, p.slug, p.description, p.price, p.image, p.stock, p.featured,
           c.id AS category_id, c.name AS category_name
    FROM products p
    JOIN categories c ON c.id = p.category_id
    $whereClause
    ORDER BY p.featured DESC, p.created_at DESC
    LIMIT $perPage OFFSET $offset
");
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$catStmt = $pdo->query("SELECT id, name, slug FROM categories ORDER BY name");
$categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'products' => $products,
    'categories' => $categories,
    'page' => $page,
    'per_page' => $perPage,
    'total' => $total,
    'total_pages' => ceil($total / $perPage)
]);
