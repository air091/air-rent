<?php
require_once realpath(__DIR__ . "/../configs/AuthDatabase.php");

class User
{
  // insert user
  public static function insertUser(
    string $email,
    string $password,
    int $roleUid,
    int $statusUid,
  ) {
    $query = "INSERT INTO account (email, password, role_uid, status_uid)
              VALUES (:email, :password, :role_uid, :status_uid)";
    $pdo = AuthDatabase::connect();
    $statement = $pdo->prepare($query);
    $statement->execute([
      "email" => $email,
      "password" => $password,
      "role_uid" => $roleId,
      "status_uid" => $statusId,
    ]);
    return (int) $pdo->lastInsertId();
  }

  // find user by email
  public static function selectEmailForLogin(string $email)
  {
    $query = "SELECT id, password FROM account WHERE email = :email";
    $pdo = AuthDatabase::connect();
    $statement = $pdo->prepare($query);
    $statement->execute(["email" => $email]);
    return $statement->fetch(PDO::FETCH_ASSOC);
  }
}
