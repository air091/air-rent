<?php
require_once realpath(__DIR__ . "/../controllers/AuthController.php");

header("Content-Type: application/json");

$URI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$method = $_SERVER["REQUEST_METHOD"];

if ($URI === "/auth/login" && $method === "POST") {
  http_response_code(201);
  AuthController::register();
  exit();
}
