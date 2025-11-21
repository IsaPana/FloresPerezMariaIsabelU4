<?php
require_once __DIR__ . '/../models/Contact.php';
session_start();

class ContactController {

    private $contact;

    public function __construct() {
        $this->contact = new Contact();
    }

    // Crear contacto
    public function store() {
        $this->authRequired();

        $this->contact->create(
            $_SESSION["user_id"],
            $_POST["name"],
            $_POST["phone"],
            $_POST["email"],
            $_POST["notes"]
        );

        header("Location: ../views/dashboard.php");
        exit;
    }

    // Editar contacto
    public function update() {
        $this->authRequired();

        $this->contact->update(
            $_POST["id"],
            $_POST["name"],
            $_POST["phone"],
            $_POST["email"],
            $_POST["notes"]
        );

        header("Location: ../views/dashboard.php");
        exit;
    }

    // Eliminar contacto
    public function delete() {
        $this->authRequired();

        $this->contact->delete($_GET["id"]);

        header("Location: ../views/dashboard.php");
        exit;
    }

    private function authRequired() {
        if (!isset($_SESSION["user_id"])) {
            header("Location: login.php");
            exit;
        }
    }
}

// Emulador
$controller = new ContactController();

if (isset($_GET["action"])) {
    $action = $_GET["action"];

    if ($action == "store") $controller->store();
    if ($action == "update") $controller->update();
    if ($action == "delete") $controller->delete();
}
?>
