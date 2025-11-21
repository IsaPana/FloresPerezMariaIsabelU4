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
            overflow: hidden;  
        }

        .glass-card {
            width: 450px;
            padding: 35px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.30);
            backdrop-filter: blur(14px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }

        h2 {
            font-weight: 700;
            color: #0B4A6F;
        }
    </style>
</head>

<body>

<div class="glass-card">
    <h2 class="text-center mb-4">Iniciar Sesión</h2>

    <form id="loginForm" method="POST" action="../controllers/UserController.php?action=login">

        <div class="mb-3">
            <label>Correo:</label>
            <input type="email" name="email" id="email" class="form-control">
        </div>

        <div class="mb-3">
            <label>Contraseña:</label>
            <input type="password" name="password" id="password" class="form-control">
        </div>

        <button class="btn btn-primary w-100 mt-3">Ingresar</button>

        <p class="text-center mt-3">
            ¿No tienes cuenta?
            <a href="register.php">Crear cuenta</a>
        </p>
    </form>
</div>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
//Validación para campos vacíos en login
document.getElementById("loginForm").addEventListener("submit", function (e) {

    let email = document.getElementById("email").value.trim();
    let pass  = document.getElementById("password").value.trim();

    if (!email || !pass) {
        e.preventDefault();
        Swal.fire({
            icon: "warning",
            title: "Campos incompletos",
            text: "Por favor llena todos los campos.",
            confirmButtonColor: "#3085d6"
        });
        return;
    }
});
</script>

<!-- 🚨 Mostrar SweetAlert si viene error desde el controlador -->
<?php if (isset($_GET["error"])): ?>
<script>
Swal.fire({
    icon: "error",
    title: "Credenciales incorrectas",
    text: "Tu correo o contraseña no son válidos.",
    confirmButtonColor: "#d33"
});
</script>
<?php endif; ?>

<?php if (isset($_GET["registered"])): ?>
<script>
Swal.fire({
    icon: "success",
    title: "¡Registro exitoso!",
    text: "Ahora inicia sesión.",
    confirmButtonColor: "#0B88C5"
});
</script>
<?php endif; ?>

</body>
</html>
