<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>

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
            backdrop-filter: blur(12px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }
    </style>
</head>

<body>

<div class="glass-card">
    <h2 class="text-center mb-4">Crear Cuenta</h2>

    <form method="POST" action="../controllers/UserController.php?action=register">

        <div class="mb-3">
            <label>Usuario:</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Correo:</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Contraseña:</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Confirmar contraseña:</label>
            <input type="password" name="confirm" class="form-control" required>
        </div>

        <button class="btn btn-primary w-100 mt-3">Registrarme</button>

        <p class="text-center mt-3">
            ¿Ya tienes cuenta?
            <a href="login.php">Iniciar sesión</a>
        </p>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.querySelector("form").addEventListener("submit", function(e) {

    let username = document.querySelector("input[name='username']").value.trim();
    let email = document.querySelector("input[name='email']").value.trim();
    let pass = document.querySelector("input[name='password']").value.trim();
    let confirm = document.querySelector("input[name='confirm']").value.trim();

    if (!username || !email || !pass || !confirm) {
        e.preventDefault();
        Swal.fire({
            icon: "warning",
            title: "Campos incompletos",
            text: "Por favor llena todos los campos antes de continuar",
        });
        return;
    }

    if (pass !== confirm) {
        e.preventDefault();
        Swal.fire({
            icon: "error",
            title: "Contraseñas no coinciden",
            text: "Verifica que ambas contraseñas sean iguales",
        });
        return;
    }
});
</script>

<?php if (isset($_GET["nomatch"])): ?>
<script>
Swal.fire({
    icon: "error",
    title: "Las contraseñas no coinciden",
    text: "Debes escribir la misma contraseña en ambos campos.",
});
</script>
<?php endif; ?>

<?php if (isset($_GET["error"])): ?>
<script>
Swal.fire({
    icon: "error",
    title: "Error al registrar",
    text: "Ocurrió un problema. Intenta nuevamente.",
});
</script>
<?php endif; ?>

<?php if (isset($_GET["registered"])): ?>
<script>
Swal.fire({
    icon: "success",
    title: "¡Registro exitoso!",
    text: "Ahora puedes iniciar sesión.",
});
</script>
<?php endif; ?>

</body>
</html>
