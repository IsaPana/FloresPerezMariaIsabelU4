<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            height: 100vh;
            background: linear-gradient(135deg, #B8E6FE, #9DD9FF);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .glass-card {
            width: 450px;
            padding: 35px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.30);
            backdrop-filter: blur(14px);
        }
    </style>
</head>

<body>

<div class="glass-card">
    <h2 class="text-center mb-4">Iniciar Sesión</h2>

    <form method="POST" action="../controllers/UserController.php?action=login">

        <div class="mb-3">
            <label>Correo:</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Contraseña:</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button class="btn btn-primary w-100 mt-3">Ingresar</button>

        <p class="text-center mt-3">
            ¿No tienes cuenta?
            <a href="register.php">Crear cuenta</a>
        </p>
    </form>
</div>

</body>
</html>
