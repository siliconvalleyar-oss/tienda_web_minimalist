<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <style>
        body { font-family: system-ui; display: flex; justify-content: center; align-items: center; height: 100vh; background: #f5f5f5; }
        .login-form { background: white; padding: 2rem; border-radius: 1rem; box-shadow: 0 10px 25px rgba(0,0,0,0.05); width: 300px; }
        input { width: 100%; padding: 0.5rem; margin: 0.5rem 0; border: 1px solid #ddd; border-radius: 0.5rem; }
        button { background: black; color: white; border: none; padding: 0.5rem; width: 100%; border-radius: 0.5rem; cursor: pointer; }
    </style>
</head>
<body>
    <div class="login-form">
        <h2>Acceso Administrador</h2>
        <?php if(isset($_GET['error'])) echo '<p style="color:red;">Usuario o contraseña incorrectos</p>'; ?>
        <form method="POST" action="auth.php">
            <input type="text" name="username" placeholder="Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>
