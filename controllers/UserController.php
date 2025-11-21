<?php
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/User.php';

session_start();

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

    //REGISTRO 
    public function register() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $username = trim($_POST["username"]);
            $email = trim($_POST["email"]);
            $password = trim($_POST["password"]);
            $confirm = trim($_POST["confirm"]);

            if ($password !== $confirm) {
                header("Location: ../views/register.php?pass_error=1");
                return;
            }

            // Validación de contraseña segura
            if (!$this->validPassword($password)) {
                header("Location: ../views/register.php?weak=1");
                return;
            }

            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $db = new Database();
            $conn = $db->connect();

            $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
            $stmt = $conn->prepare($query);
            $stmt->bindValue(":username", $username);
            $stmt->bindValue(":email", $email);
            $stmt->bindValue(":password", $hashed);

            if ($stmt->execute()) {
                header("Location: ../views/login.php?registered=1");
                exit;
            } else {
                echo "Error al registrar usuario";
            }
        }
    }

    //  LOGIN
    public function login() {

        // Bloqueo por intentos fallidos
        if (isset($_SESSION['lock_time']) && time() < $_SESSION['lock_time']) {
            header("Location: ../views/login.php?blocked=1");
            exit;
        }

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

            if ($user && password_verify($password, $user["password"])) {

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["username"] = $user["username"];

                // Reiniciar los intentos
                unset($_SESSION["attempts"]);

                header("Location: ../views/dashboard.php");
                exit;
            }

            // Manejar intentos fallidos
            if (!isset($_SESSION["attempts"])) {
                $_SESSION["attempts"] = 1;
            } else {
                $_SESSION["attempts"]++;
            }

            if ($_SESSION["attempts"] >= 3) {
                $_SESSION["lock_time"] = time() + 30; // 30 seg de bloqueo
                header("Location: ../views/login.php?blocked=1");
                exit;
            }

            header("Location: ../views/login.php?error=1");
            exit;
        }
    }

    // LOGOUT 
    public function logout() {
        session_unset();
        session_destroy();
        header("Location: ../views/login.php");
        exit;
    }

    //VALIDADOR DE CONTRASEÑA 
    private function validPassword($pass) {

        // NO espacios
        if (preg_match('/\s/', $pass)) return false;

        // Mínimo 8 caracteres
        if (strlen($pass) < 8) return false;

        // Al menos 1 mayúscula
        if (!preg_match('/[A-Z]/', $pass)) return false;

        // Al menos 1 número
        if (!preg_match('/[0-9]/', $pass)) return false;

        // Al menos 1 símbolo permitido
        if (!preg_match('/[.\-*_\@\!]/', $pass)) return false;

        // Detectar secuencias numéricas (123, 234, etc)
        if ($this->hasSequentialNumbers($pass)) return false;

        return true;
    }

    private function hasSequentialNumbers($pass) {
        $digits = preg_replace('/\D/', '', $pass);

        for ($i = 0; $i < strlen($digits) - 2; $i++) {
            $a = intval($digits[$i]);
            $b = intval($digits[$i+1]);
            $c = intval($digits[$i+2]);

            if ($b === $a + 1 && $c === $b + 1) {
                return true; // secuencia detectada
            }
        }

        return false;
    }
}
