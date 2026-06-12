<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
    header('Location: login.php');
    exit;
}
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    updateSetting('primary_color', $_POST['primary_color'], $pdo);
    updateSetting('bg_color', $_POST['bg_color'], $pdo);
    updateSetting('text_color', $_POST['text_color'], $pdo);
    updateSetting('whatsapp_number', $_POST['whatsapp_number'], $pdo);
    $success = true;
}

$primary = getSetting('primary_color', $pdo);
$bg = getSetting('bg_color', $pdo);
$text = getSetting('text_color', $pdo);
$whatsapp = getSetting('whatsapp_number', $pdo);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin · Tienda Minimalista</title>
    <style>
        body { font-family: system-ui; background: #fafafa; margin: 0; padding: 2rem; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 2rem; border-radius: 1.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        h1 { font-weight: 400; }
        label { display: block; margin-top: 1rem; font-weight: 500; }
        input, button { width: 100%; padding: 0.6rem; margin-top: 0.3rem; border-radius: 0.5rem; border: 1px solid #ddd; }
        button { background: black; color: white; border: none; cursor: pointer; margin-top: 1.5rem; }
        .color-preview { width: 50px; height: 50px; border-radius: 0.5rem; margin-top: 0.5rem; border: 1px solid #ccc; }
        .logout { display: inline-block; margin-top: 2rem; color: #888; text-decoration: none; }
    </style>
</head>
<body>
<div class="container">
    <h1>Personalización</h1>
    <?php if (isset($success)) echo '<p style="color:green;">Configuración guardada</p>'; ?>
    <form method="POST">
        <label>Color principal (botones, bordes)</label>
        <input type="color" name="primary_color" value="<?= $primary ?>" style="height: 40px;">
        <div class="color-preview" style="background: <?= $primary ?>;"></div>

        <label>Color de fondo</label>
        <input type="color" name="bg_color" value="<?= $bg ?>">

        <label>Color de texto</label>
        <input type="color" name="text_color" value="<?= $text ?>">

        <label>Número de WhatsApp (código país + número, sin espacios ni +)</label>
        <input type="text" name="whatsapp_number" value="<?= $whatsapp ?>" placeholder="56912345678">

        <button type="submit">Guardar cambios</button>
    </form>
    <a href="logout.php" class="logout">Cerrar sesión</a>
</div>
</body>
</html>
