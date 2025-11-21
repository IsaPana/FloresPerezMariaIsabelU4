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
            backdrop-filter: blur(14px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }

        .toggle-pass {
            cursor: pointer;
            position: absolute;
            right: 20px;
            top: 45px;
            z-index: 10;
        }
        /* Ojo normal (contraseña oculta) */
.eye-closed {
    background-image: url('../img/invisible.png');
}

/* Ojo "abierto" usando rotación */
.eye-open {
    background-image: url('../img/invisible.png');
    transform: translateY(-50%) rotate(180deg);
}
    </style>
</head>

<body>

<div class="glass-card">
    <h2 class="text-center mb-4">Crear Cuenta</h2>

    <form id="regForm" method="POST" action="../controllers/UserController.php?action=register">

        <div class="mb-3">
            <label>Usuario:</label>
            <input type="text" name="username" class="form-control">
        </div>

        <div class="mb-3">
            <label>Correo:</label>
            <input type="email" name="email" class="form-control">
        </div>

        <div class="mb-3 position-relative">
            <label>Contraseña:</label>
            <input type="password" name="password" id="password" class="form-control">
            <span class="toggle-pass" onclick="togglePass('password')">👁️</span>
        </div>

        <div class="mb-3 position-relative">
            <label>Confirmar contraseña:</label>
            <input type="password" name="confirm" id="confirm" class="form-control">
            <span class="toggle-pass" onclick="togglePass('confirm')">👁️</span>
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
// Mostrar / Ocultar passwords
function togglePass(id){
    let field = document.getElementById(id);
    field.type = field.type === "password" ? "text" : "password";
}

// Validación antes de enviar
document.getElementById("regForm").addEventListener("submit", function(e){

    let username = document.querySelector("input[name='username']").value.trim();
    let email    = document.querySelector("input[name='email']").value.trim();
    let pass     = document.getElementById("password").value.trim();
    let confirm  = document.getElementById("confirm").value.trim();

    if (!username || !email || !pass || !confirm) {
        e.preventDefault();
        Swal.fire({ icon: "warning", title: "Campos vacíos", text: "Completa todos los campos." });
        return;
    }

    if (pass !== confirm) {
        e.preventDefault();
        Swal.fire({ icon: "error", title: "Las contraseñas no coinciden" });
        return;
    }
});
</script>

<!-- ALERTAS DESDE EL CONTROLADOR -->
<?php if (isset($_GET["pass_error"])): ?>
<script>
Swal.fire({
    icon: "error",
    title: "Contraseñas no coinciden",
    text: "Debes escribir la misma contraseña en ambos campos.",
});
</script>
<?php endif; ?>

<?php if (isset($_GET["weak"])): ?>
<script>
Swal.fire({
    icon: "warning",
    title: "Contraseña débil",
    html: "La contraseña debe incluir:<br><br>✔ 8 caracteres<br>✔ 1 mayúscula<br>✔ 1 número<br>✔ 1 símbolo (.-*_@!)<br>❌ Sin espacios<br>❌ Sin números en secuencia (123, 456...)",
});
</script>
<?php endif; ?>

<?php if (isset($_GET["error"])): ?>
<script>
Swal.fire({
    icon: "error",
    title: "Error en el registro",
    text: "Ocurrió un error. Intenta nuevamente.",
});
</script>
<?php endif; ?>

</body>
</html>
