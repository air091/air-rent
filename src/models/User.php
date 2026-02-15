<?php
require_once realpath(__DIR__ . "/../configs/AuthDatabase.php");


class User
{
  // insert user
  public static function insertUser(string $email, string $password, int $roleId, int $statusId)
  {
    $query = "INSERT INTO account (email, password, role_id, status_id)
              VALUES (:email, :password, :role_id, :status_id)";
    $pdo = AuthDatabase::connect();
    $statement = $pdo->prepare($query);
    $statement->execute(["email" => $email, "password" => $password, "role_id" => $roleId, "status_id" => $statusId]);
    return (int)$pdo->lastInsertId();
  }

  // find user by email
  public static function selectEmailForLogin(string $email) {
    $query = "SELECT id, password FROM account WHERE email = :email";
    $pdo = AuthDatabase::connect();
    $statement = $pdo->prepare($query);
    $statement->execute(["email" => $email]);
    return $statement->fetch(PDO::FETCH_ASSOC);
  }
}
