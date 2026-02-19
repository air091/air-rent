<?php
require_once realpath(__DIR__ . "/../../vendor/autoload.php");
require_once realpath(__DIR__ . "/../models/User.php");

use Dotenv\Dotenv;
use Firebase\JWT\JWT;

$dotenv = Dotenv::createImmutable(__DIR__ . "/../../");
$dotenv->load();

class AuthController
{
  public static function registerClient()
  {
    try {
      $input = json_decode(file_get_contents("php://input"), true);

      $email = isset($input["email"]) ? trim($input["email"]) : "";
      $password = isset($input["password"]) ? $input["password"] : "";

      if (empty($email) || empty($password)) {
        http_response_code(400);
        echo json_encode([
          "success" => false,
          "message" => "All fields are required",
        ]);
        exit();
      }
      $passwordHash = password_hash($password, PASSWORD_DEFAULT);
      $user = User::insertUser(
        $email,
        $passwordHash,
        "2b492ec3-0bb9-11f1-a1be-34298f7857e5",
        "30a2d70b-0bb9-11f1-a1be-34298f7857e5",
      );

      echo json_encode([
        "success" => true,
        "message" => "User Registered Successfully",
        "userId" => $user,
      ]);
    } catch (PDOException $err) {
      echo json_encode([
        "sccuess" => false,
        "message" => "Register User Failed",
        "errorMessage" => $err->getMessage(),
      ]);
      exit();
    }
  }

  public static function registerHost()
  {
    try {
      $input = json_decode(file_get_contents("php://input"), true);

      $email = isset($input["email"]) ? trim($input["email"]) : "";
      $password = isset($input["password"]) ? $input["password"] : "";

      // validate all fields
      if (!$email || !$password) {
        http_response_code(400);
        echo json_encode([
          "success" => false,
          "message" => "All fields are required",
        ]);
        exit();
      }

      // hash password
      $passwordHash = password_hash($password, PASSWORD_DEFAULT);

      // insert host
      $user = User::insertUser(
        $email,
        $passwordHash,
        "28519da5-0bb9-11f1-a1be-34298f7857e5",
        "30a2d70b-0bb9-11f1-a1be-34298f7857e5",
      );

      // return
      echo json_encode([
        "success" => true,
        "message" => "Host Registered Successfully",
        "hostId" => $user,
      ]);
    } catch (PDOException $err) {
      echo json_encode([
        "success" => false,
        "message" => "Register Host Failed",
        "errorMessage" => $err->getMessage(),
      ]);
      exit();
    }
  }

  public static function registerAdmin()
  {
    try {
      $input = json_decode(file_get_contents("php://input"), true);

      $email = isset($input["email"]) ? trim($input["email"]) : "";
      $password = isset($input["password"]) ? $input["password"] : "";

      // validate all fields
      if (!$email || !$password) {
        http_response_code(400);
        echo json_encode([
          "success" => false,
          "message" => "All fields are required",
        ]);
        exit();
      }

      // hash password
      $passwordHash = password_hash($password, PASSWORD_DEFAULT);

      // insert host
      $user = User::insertUser(
        $email,
        $passwordHash,
        "2263f1f8-0bb9-11f1-a1be-34298f7857e5",
        "30a2d70b-0bb9-11f1-a1be-34298f7857e5",
      );

      // return
      echo json_encode([
        "success" => true,
        "message" => "Admin Registered Successfully",
        "hostId" => $user,
      ]);
    } catch (PDOException $err) {
      echo json_encode([
        "success" => false,
        "message" => "Register Admin Failed",
        "errorMessage" => $err->getMessage(),
      ]);
      exit();
    }
  }

  public static function login()
  {
    try {
      // input
      $input = json_decode(file_get_contents("php://input"), true);
      // credentials
      $email = isset($input["email"]) ? trim($input["email"]) : "";
      $password = isset($input["password"]) ? $input["password"] : "";

      // check requirements
      if (!$email || !$password) {
        http_response_code(400);
        echo json_encode([
          "success" => false,
          "message" => "All fields are required",
        ]);
        exit();
      }

      // check user
      $user = User::selectEmailForLogin($email);
      // compare password
      if (!$user || !password_verify($password, $user["password"])) {
        http_response_code(401);
        echo json_encode([
          "success" => false,
          "message" => "Invalid email or password",
        ]);
        exit();
      }

      // generate token
      $jwtSecret = $_ENV["JWT_SECRET"];
      $payload = [
        "iss" => $_ENV["JWT_ISSUER"],
        "exp" => time() + (int) $_ENV["JWT_EXPIRES_AT"],
        "sub" => $user["uuid"],
      ];
      $token = JWT::encode($payload, $jwtSecret, "HS256");

      // set cookie
      setCookie("token", $token, [
        "expires" => time() + 60,
        "path" => "/",
        "secure" => false,
        "httponly" => true,
        "samesite" => "strict",
      ]);

      echo json_encode([
        "success" => true,
        "message" => "Logged in successfully",
        "userId" => $user["uuid"],
        "token" => $token,
      ]);
    } catch (PDOException $err) {
      http_response_code(500);
      echo json_encode([
        "success" => false,
        "message" => "Login Failed",
        "errorMessage" => $err->getMessage(),
      ]);
      exit();
    }
  }

  public static function me()
  {
    echo json_encode([
      "success" => true,
      "user" => $_SERVER["user_auth"],
    ]);
    return;
  }

  public static function logout()
  {
    try {
      setCookie("token", "", [
        "expires" => time() - 3600,
        "path" => "/",
        "secure" => false,
        "httponly" => true,
        "samesite" => "strict",
      ]);
      unset($_COOKIE["token"]);
      echo json_encode([
        "success" => true,
        "message" => "User logged out successfully",
      ]);
    } catch (PDOException $err) {
      http_response_code(500);
      echo json_encode([
        "success" => false,
        "message" => "Logout Failed",
        "errorMessage" => $err->getMessage(),
      ]);
      exit();
    }
  }
}
