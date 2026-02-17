<?php
require_once realpath(__DIR__ . "/../../vendor/autoload.php");

use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$dotenv = Dotenv::createImmutable(__DIR__ . "/../../");
$dotenv->load();

class AuthMiddleware
{
  public static function verify()
  {
    try {
      // get stored cookie
      $token = $_COOKIE["token"] ?? null;

      // validate token
      if (!$token) {
        http_response_code(401);
        echo json_encode([
          "success" => false,
          "message" => "No token",
        ]);
        exit();
      }
      // decode jwt
      $payload = JWT::decode($token, new Key($_ENV["JWT_SECRET"], "HS256"));

      // store data to server
      $_SERVER["user_auth"] = $payload;
    } catch (Exception $err) {
      echo json_encode([
        "success" => false,
        "message" => "Token Verification Failed",
        "errorMessage" => $err->getMessage(),
      ]);
      exit();
    }
  }
}
