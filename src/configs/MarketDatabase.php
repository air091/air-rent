<?php
class MarketDatabase
{
  private static string $host = "localhost";
  private static string $username = "root";
  private static string $password = "root";
  private static string $databaseName = "airrent_market_db";

  public static function connect()
  {
    try {
      $connect = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$databaseName, self::$username, self::$password);
      $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $connect;
    } catch (PDOException $err) {
      echo json_encode([
        "success" => false,
        "message" => "Market Database Failed",
        "errorMessage" => $err->getMessage()
      ]);
      exit();
    }
  }
}
