<?php
require_once realpath(__DIR__ . "/../models/User.php");

define("input", json_decode(file_get_contents("php://input"), true));

class AuthController
{
  public static function register()
  {
    try {
      $email = isset(input["email"]) ? trim(input["email"]) : "";
      $password = isset(input["password"]) ? input["password"] : "";
      
      if (empty($email) || empty($password)) {
        http_response_code(400);
        echo json_encode([
          "success" => false,
          "message" => "All fields are required"
        ]);
        exit();
      }
      $passwordHash = password_hash($password, PASSWORD_DEFAULT);
      $user = User::insertUser($email, $passwordHash);

      echo json_encode([
        "success" => true,
        "message" => "User Registered Successfully",
        "user" => [
          "id" => $user["id"]
        ]
      ]);
    } catch (PDOException $err) {
      echo json_encode([
        "sccuess" => false,
        "message" => "Create user failed",
        "errorMessage" => $err->getMessage()
      ]);
      exit();
    }
  }
}
