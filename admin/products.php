<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
    header('Location: login.php');
    exit;
}
require_once '../includes/db.php';

$error = '';
$success = '';
$editProduct = null;

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $success = 'Producto eliminado';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $image = trim($_POST['image'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $stock = (int)($_POST['stock'] ?? 0);
    $featured = isset($_POST['featured']) ? 1 : 0;

    if (!$slug) {
        $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $name)), '-');
    }
    if (!$image) {
        $image = 'https://picsum.photos/seed/' . $slug . '/400/300';
    }

    if ($name && $price > 0 && $category_id > 0) {
        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE products SET category_id=?, name=?, slug=?, description=?, price=?, image=?, stock=?, featured=? WHERE id=?");
            $stmt->execute([$category_id, $name, $slug, $description, $price, $image, $stock, $featured, $id]);
            $success = 'Producto actualizado';
        } else {
            $stmt = $pdo->prepare("INSERT INTO products (category_id, name, slug, description, price, image, stock, featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$category_id, $name, $slug, $description, $price, $image, $stock, $featured]);
            $success = 'Producto creado';
        }
    } else {
        $error = 'Completa los campos obligatorios: nombre, precio, categoría';
    }
}

if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $editProduct = $stmt->fetch(PDO::FETCH_ASSOC);
}

$products = $pdo->query("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON c.id = p.category_id ORDER BY p.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin · Productos</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: system-ui; background: #fafafa; padding: 2rem; }
        .container { max-width: 1100px; margin: 0 auto; }
        h1 { font-weight: 400; margin-bottom: 1.5rem; }
        .nav-admin { display: flex; gap: 1.5rem; margin-bottom: 2rem; }
        .nav-admin a { color: #555; text-decoration: none; font-size: 0.9rem; }
        .nav-admin a:hover { color: #000; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; }
        .card { background: white; padding: 1.5rem; border-radius: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
        .card h2 { font-weight: 400; font-size: 1.1rem; margin-bottom: 1rem; }
        label { display: block; margin-top: 0.8rem; font-weight: 500; font-size: 0.85rem; }
        input, select, textarea { width: 100%; padding: 0.5rem; margin-top: 0.2rem; border: 1px solid #ddd; border-radius: 0.5rem; font-size: 0.9rem; }
        textarea { resize: vertical; min-height: 60px; }
        .btn { background: #000; color: white; border: none; padding: 0.6rem 1.5rem; border-radius: 2rem; cursor: pointer; margin-top: 1rem; font-size: 0.9rem; }
        .btn:hover { opacity: 0.85; }
        .btn-sm { padding: 0.3rem 0.8rem; font-size: 0.8rem; }
        .btn-danger { background: #ef4444; }
        .success { color: #16a34a; margin-bottom: 1rem; }
        .error { color: #ef4444; margin-bottom: 1rem; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { text-align: left; padding: 0.6rem 0.4rem; border-bottom: 1px solid #eee; font-size: 0.85rem; }
        th { font-weight: 600; color: #555; }
        .featured-badge { background: #fef3c7; color: #92400e; padding: 0.15rem 0.5rem; border-radius: 1rem; font-size: 0.75rem; }
        .checkbox-wrap { display: flex; align-items: center; gap: 0.5rem; margin-top: 0.8rem; }
        .checkbox-wrap input { width: auto; margin: 0; }
        .actions { display: flex; gap: 0.3rem; }
        .actions a { color: #555; text-decoration: none; font-size: 0.8rem; }
        .actions a:hover { color: #000; }
        @media (max-width: 768px) { .grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<div class="container">
    <h1>Productos</h1>
    <div class="nav-admin">
        <a href="dashboard.php">Personalización</a>
        <a href="products.php">Productos</a>
        <a href="orders.php">Pedidos</a>
        <a href="logout.php" style="margin-left:auto;color:#aaa;">Cerrar sesión</a>
    </div>

    <?php if ($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
    <?php if ($success): ?><div class="success"><?= $success ?></div><?php endif; ?>

    <div class="grid">
        <div class="card">
            <h2><?= $editProduct ? 'Editar Producto' : 'Nuevo Producto' ?></h2>
            <form method="POST">
                <?php if ($editProduct): ?>
                    <input type="hidden" name="id" value="<?= $editProduct['id'] ?>">
                <?php endif; ?>

                <label>Nombre *</label>
                <input type="text" name="name" value="<?= $editProduct['name'] ?? '' ?>" required>

                <label>Slug (URL)</label>
                <input type="text" name="slug" value="<?= $editProduct['slug'] ?? '' ?>" placeholder="Auto-generado si se deja vacío">

                <label>Categoría *</label>
                <select name="category_id" required>
                    <option value="">Seleccionar...</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= ($editProduct && $editProduct['category_id'] == $c['id']) ? 'selected' : '' ?>><?= $c['name'] ?></option>
                    <?php endforeach; ?>
                </select>

                <label>Precio *</label>
                <input type="number" step="0.01" name="price" value="<?= $editProduct['price'] ?? '' ?>" required>

                <label>Descripción</label>
                <textarea name="description"><?= $editProduct['description'] ?? '' ?></textarea>

                <label>Imagen (URL)</label>
                <input type="url" name="image" value="<?= $editProduct['image'] ?? '' ?>" placeholder="Auto-generada si se deja vacío">

                <label>Stock</label>
                <input type="number" name="stock" value="<?= $editProduct['stock'] ?? 0 ?>">

                <div class="checkbox-wrap">
                    <input type="checkbox" name="featured" id="featured" <?= ($editProduct && $editProduct['featured']) ? 'checked' : '' ?>>
                    <label for="featured" style="margin:0;">Producto destacado</label>
                </div>

                <button class="btn" type="submit"><?= $editProduct ? 'Actualizar' : 'Crear Producto' ?></button>
                <?php if ($editProduct): ?>
                    <a href="products.php" style="display:inline-block;margin-left:0.5rem;color:#888;font-size:0.85rem;">Cancelar</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="card">
            <h2>Todos los productos (<?= count($products) ?>)</h2>
            <?php if (count($products) === 0): ?>
                <p style="color:#aaa;font-size:0.85rem;margin-top:1rem;">No hay productos aún</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th>Categoría</th>
                            <th>Stock</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $p): ?>
                            <tr>
                                <td>
                                    <?= $p['featured'] ? '<span class="featured-badge">Destacado</span>' : '' ?>
                                    <?= $p['name'] ?>
                                </td>
                                <td>$<?= number_format($p['price'], 2) ?></td>
                                <td><?= $p['category_name'] ?? '—' ?></td>
                                <td><?= $p['stock'] ?></td>
                                <td class="actions">
                                    <a href="?edit=<?= $p['id'] ?>">✏️</a>
                                    <a href="?delete=<?= $p['id'] ?>" onclick="return confirm('¿Eliminar <?= $p['name'] ?>?')">🗑️</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
