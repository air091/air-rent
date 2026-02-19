<?php
require_once realpath(__DIR__ . "/../configs/AuthDatabase.php");
require_once realpath(__DIR__ . "/../utils/GenerateUUID.php");

class User
{

  // insert user
  public static function insertUser(
    string $email,
    string $password,
    string $roleUUID,
    string $statusUUID,
  ) {
    $uuid = GenerateUUID::generate();
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

  // find account by uuid and host
  public static function selectAccountByUuid(string $accountUUID)
  {
    $query = "SELECT account.uuid, account.email, role.name AS role, status.name AS status
              FROM account
              INNER JOIN role ON account.role_uuid = role.uuid
              INNER JOIN status ON account.status_uuid = status.uuid
              WHERE account.uuid = :account_uuid";
    $pdo = AuthDatabase::connect();
    $statement = $pdo->prepare($query);
    $statement->execute(["account_uuid" => $accountUUID]);
    return $statement->fetch(PDO::FETCH_ASSOC);
  }

  // find account by email for login
  public static function selectEmailForLogin(string $email)
  {
    $query = "SELECT uuid, password FROM account WHERE email = :email";
    $pdo = AuthDatabase::connect();
    $statement = $pdo->prepare($query);
    $statement->execute(["email" => $email]);
    return $statement->fetch(PDO::FETCH_ASSOC);
  }
}
