<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password_hash'])) {
        $_SESSION['admin_logged'] = true;
        header('Location: dashboard.php');
    } else {
        header('Location: login.php?error=1');
    }
}
?>
