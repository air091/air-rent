<?php
require_once realpath(__DIR__ . "/../controllers/AuthController.php");

header("Content-Type: application/json");

$URI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$method = $_SERVER["REQUEST_METHOD"];

// REGISTER AS USER
if ($URI === "/api/auth/user/register" && $method === "POST") {
  http_response_code(201);
  AuthController::registerUser();
  exit();
}

// REGISTER AS HOST
if ($URI === "/api/auth/host/register" && $method === "POST") {
  http_response_code(201);
  AuthController::registerHost();
  exit();
}

// REGISTER AS ADMIN
if ($URI === "/api/auth/admin/register" && $method === "POST") {
  http_response_code(201);
  AuthController::registerAdmin();
  exit();
}

// LOGIN
if ($URI === "/api/auth/login" && $method === "POST") {
  http_response_code(200);
  AuthController::login();
  exit();
}

if ($URI === "/api/auth/me" && $method === "GET") {
  http_response_code(200);
  AuthController::me();
  exit();
}
