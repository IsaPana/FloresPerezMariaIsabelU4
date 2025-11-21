<?php
class HomeController
{
    public function index()
    {
        header("Location: /User/login");
    }

    public function dashboard()
    {
        session_start();

        if (!isset($_SESSION["user"])) {
            header("Location: /User/login");
            exit;
        }

        $user = $_SESSION["user"];

        require_once __DIR__ . "/../views/dashboard.php";
    }
}
