<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../models/Contact.php";
$contact = new Contact();
$contacts = $contact->getAll($_SESSION["user_id"]);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda de Contactos</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary px-3">
    <a class="navbar-brand" href="#">DevFlow</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="#">Hola, <?= $_SESSION["username"] ?></a></li>
            <li class="nav-item">
                <a class="nav-link" href="../controllers/UserController.php?action=logout">Cerrar sesión</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <h3>Mis Contactos</h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreate">+ Agregar</button>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Notas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach($contacts as $c): ?>
                    <tr>
                        <td><?= $c["name"] ?></td>
                        <td><?= $c["phone"] ?></td>
                        <td><?= $c["email"] ?></td>
                        <td><?= $c["notes"] ?></td>
                        <td>
                            <button class="btn btn-sm btn-warning"
                                onclick='editContact(<?= json_encode($c) ?>)'
                                data-bs-toggle="modal"
                                data-bs-target="#modalEdit">Editar</button>

                            <a href="#" class="btn btn-sm btn-danger"
                               onclick="confirmDelete(<?= $c['id'] ?>)">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    </div>
</div>

<!-- MODAL CREAR -->
<div class="modal fade" id="modalCreate">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="../controllers/ContactController.php?action=store">

            <div class="modal-header">
                <h5 class="modal-title">Agregar Contacto</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input name="name" class="form-control mb-2" placeholder="Nombre" required>
                <input name="phone" class="form-control mb-2" placeholder="Teléfono" required>
                <input name="email" class="form-control mb-2" placeholder="Correo">
                <textarea name="notes" class="form-control" placeholder="Notas"></textarea>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDITAR -->
<div class="modal fade" id="modalEdit">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="../controllers/ContactController.php?action=update">

            <div class="modal-header">
                <h5 class="modal-title">Editar Contacto</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" name="id" id="edit-id">

                <input name="name" id="edit-name" class="form-control mb-2" required>
                <input name="phone" id="edit-phone" class="form-control mb-2" required>
                <input name="email" id="edit-email" class="form-control mb-2">
                <textarea name="notes" id="edit-notes" class="form-control"></textarea>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary">Guardar cambios</button>
            </div>
        </form>
    </div>
</div>

<script>
function editContact(c) {
    document.getElementById("edit-id").value = c.id;
    document.getElementById("edit-name").value = c.name;
    document.getElementById("edit-phone").value = c.phone;
    document.getElementById("edit-email").value = c.email;
    document.getElementById("edit-notes").value = c.notes;
}

function confirmDelete(id) {
    Swal.fire({
        title: "¿Eliminar contacto?",
        text: "Esta acción no se puede deshacer",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "../controllers/ContactController.php?action=delete&id=" + id;
        }
    });
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (isset($_GET["added"])): ?>
<script>
Swal.fire({
    icon: "success",
    title: "Contacto agregado",
    timer: 1500,
    showConfirmButton: false
});
</script>
<?php endif; ?>

<?php if (isset($_GET["updated"])): ?>
<script>
Swal.fire({
    icon: "success",
    title: "Contacto actualizado",
    timer: 1500,
    showConfirmButton: false
});
</script>
<?php endif; ?>

<?php if (isset($_GET["deleted"])): ?>
<script>
Swal.fire({
    icon: "success",
    title: "Contacto eliminado",
    timer: 1500,
    showConfirmButton: false
});
</script>
<?php endif; ?>

</body>
</html>
