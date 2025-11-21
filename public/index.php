<?php
$uri = explode("/", trim($_SERVER["REQUEST_URI"], "/"));

$controller = $uri[0] ?: "Home";
$method = $uri[1] ?: "index";


if ($method === "login" && $_SERVER["REQUEST_METHOD"] === "POST") {
    $method = "loginPost";
}
if ($method === "register" && $_SERVER["REQUEST_METHOD"] === "POST") {
    $method = "registerPost";
}

$controllerName = $controller . "Controller";

require_once __DIR__ . "/../controllers/" . $controllerName . ".php";

$obj = new $controllerName();
$obj->$method();
