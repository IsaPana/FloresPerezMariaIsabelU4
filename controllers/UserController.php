<?php
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/User.php';

$controller = new UserController();

if (isset($_GET['action'])) {

    if ($_GET['action'] === 'register') {
        $controller->register();
        exit;
    }

    if ($_GET['action'] === 'login') {
        $controller->login();
        exit;
    }

    if ($_GET['action'] === 'logout') {
        $controller->logout();
        exit;
    }
}

class UserController {

    // REGISTRO
    public function register() {

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $username = trim($_POST["username"]);
            $email = trim($_POST["email"]);
            $password = trim($_POST["password"]);
            $confirm = trim($_POST["confirm"]);

            // Validación de contraseña
            if ($password !== $confirm) {
                header("Location: ../views/register.php?nomatch=1");
                exit;
            }

            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $db = new Database();
            $conn = $db->connect();

            $query = "INSERT INTO users (username, email, password)
                      VALUES (:username, :email, :password)";

            $stmt = $conn->prepare($query);
            $stmt->bindValue(":username", $username);
            $stmt->bindValue(":email", $email);
            $stmt->bindValue(":password", $hashed);

            if ($stmt->execute()) {
                header("Location: ../views/login.php?registered=1");
                exit;
            } else {
                header("Location: ../views/register.php?error=1");
                exit;
            }
        }
    }

    // LOGIN
    public function login() {

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $email = trim($_POST["email"]);
            $password = trim($_POST["password"]);

            $db = new Database();
            $conn = $db->connect();

            $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->bindValue(":email", $email);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Validación de credenciales
            if ($user && password_verify($password, $user["password"])) {

                session_start();
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["username"] = $user["username"];

                header("Location: ../views/dashboard.php");
                exit;
            }

            
            header("Location: ../views/login.php?error=1");
            exit;
        }
    }

    // LOGOUT
    public function logout() {
        session_start();
        session_unset();
        session_destroy();

        // Evita volver con el botón de retroceder
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Location: ../views/login.php");
        exit;
    }
}
