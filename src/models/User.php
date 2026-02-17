<?php
require_once realpath(__DIR__ . "/../configs/AuthDatabase.php");

class User
{
  private static function generateUUID(): string
  {
    $uuid = random_bytes(16);
    $uuid[6] = chr((ord($uuid[6]) & 0x0f) | 0x40);
    $uuid[8] = chr((ord($uuid[8]) & 0x3f) | 0x80);
    return vsprintf("%s%s-%s-%s-%s-%s%s%s", str_split(bin2hex($uuid), 4));
  }

  // insert user
  public static function insertUser(
    string $email,
    string $password,
    string $roleUUID,
    string $statusUUID,
  ) {
    $uuid = self::generateUUID();
    $query = "INSERT INTO account (uuid, email, password, role_uuid, status_uuid)
              VALUES (:uuid, :email, :password, :role_uuid, :status_uuid)";
    $pdo = AuthDatabase::connect();
    $statement = $pdo->prepare($query);
    $statement->execute([
      "uuid" => $uuid,
      "email" => $email,
      "password" => $password,
      "role_uuid" => $roleUUID,
      "status_uuid" => $statusUUID,
    ]);
    return $uuid;
  }

  // find user by email
  public static function selectEmailForLogin(string $email)
  {
    $query = "SELECT uuid, password FROM account WHERE email = :email";
    $pdo = AuthDatabase::connect();
    $statement = $pdo->prepare($query);
    $statement->execute(["email" => $email]);
    return $statement->fetch(PDO::FETCH_ASSOC);
  }
}
