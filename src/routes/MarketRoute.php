<?php
  require_once realpath(__DIR__ . "/../controllers/MarketController.php");
  require_once realpath(__DIR__ . "/../middlewares/AuthMiddleware.php");

  header("Content-Type: application/json");

  $URI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
  $METHOD = $_SERVER["REQUEST_METHOD"];

  if ($URI === "/api/markets/add" && $METHOD === "POST") {
    http_response_code(201);
    AuthMiddleware::verify();
    MarketController::createList();
    exit();
  }

  if ($URI === "/api/markets" && $METHOD === "GET") {
    http_response_code(200);
    AuthMiddleware::verify();
    MarketController::getLists();
    exit();
  }

