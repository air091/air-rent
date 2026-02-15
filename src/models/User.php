<?php
require_once realpath(__DIR__ . "/../configs/AuthDatabase.php");


class User
{
  // insert user
  public static function insertUser(string $email, string $password) {
    $query = "INSERT INTO account (email, password, role_id, status_id)
              VALUES (:email, :password, 1, 1)";
    $pdo = AuthDatabase::connect();
    $statement = $pdo->prepare($query);
    $statement->execute(["email" => $email, "password" => $password]);
    return $pdo;
  }
}
