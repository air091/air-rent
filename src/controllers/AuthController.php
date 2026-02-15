<?php
require_once realpath(__DIR__ . "/../models/User.php");

class AuthController
{
  public static function registerUser()
  {
    try {
      $input = json_decode(file_get_contents("php://input"), true);

      $email = isset($input["email"]) ? trim($input["email"]) : "";
      $password = isset($input["password"]) ? $input["password"] : "";

      if (empty($email) || empty($password)) {
        http_response_code(400);
        echo json_encode([
          "success" => false,
          "message" => "All fields are required"
        ]);
        exit();
      }
      $passwordHash = password_hash($password, PASSWORD_DEFAULT);
      $user = User::insertUser($email, $passwordHash, 1, 1);

      echo json_encode([
        "success" => true,
        "message" => "User Registered Successfully",
        "userId" => $user
      ]);
    } catch (PDOException $err) {
      echo json_encode([
        "sccuess" => false,
        "message" => "Register User Failed",
        "errorMessage" => $err->getMessage()
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
          "message" => "All fields are required"
        ]);
        exit();
      }

      // hash password
      $passwordHash = password_hash($password, PASSWORD_DEFAULT);

      // insert host
      $user = User::insertUser($email, $passwordHash, 3, 1);

      // return
      echo json_encode([
        "success" => true,
        "message" => "Host Registered Successfully",
        "hostId" => $user
      ]);
    } catch (PDOException $err) {
      echo json_encode([
        "success" => false,
        "message" => "Register Host Failed",
        "errorMessage" => $err->getMessage()
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
          "message" => "All fields are required"
        ]);
        exit();
      }

      // hash password
      $passwordHash = password_hash($password, PASSWORD_DEFAULT);

      // insert host
      $user = User::insertUser($email, $passwordHash, 2, 1);

      // return
      echo json_encode([
        "success" => true,
        "message" => "Admin Registered Successfully",
        "hostId" => $user
      ]);
    } catch (PDOException $err) {
      echo json_encode([
        "success" => false,
        "message" => "Register Admin Failed",
        "errorMessage" => $err->getMessage()
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
          "message" => "All fields are required"
        ]);
        exit();
      }

      // check user
      $user = User::selectEmailForLogin($email);
      // compare password
      if (!$user && !password_verify($password, $user["password"])) {
        http_response_code(401);
        echo json_encode([
          "success" => false,
          "message" => "Invalid email or password"
        ]);
        exit();
      }

      echo json_encode([
        "success" => true,
        "message" => "Logged in successfully",
        "userId" => $user["id"]
      ]);
    } catch (PDOException $err) {
      http_response_code(500);
      echo json_encode([
        "success" => false,
        "message" => "Login Failed",
        "errorMessage" => $err->getMessage()
      ]);
      exit();
    }
  }
}
