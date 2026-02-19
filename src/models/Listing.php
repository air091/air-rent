<?php

require_once realpath(__DIR__ . "/../configs/MarketDatabase.php");
require_once realpath(__DIR__ . "/User.php");
require_once realpath(__DIR__ . "/../utils/GenerateUUID.php");

class Listing
{
  public static function insertList(string $hostUUID, string $title, string $location, string $typeUUID, string $description)
  {
    $uuid = GenerateUUID::generate();
    $query = "INSERT INTO listing (uuid, host_uuid, title, location, type_uuid, status_uuid, description)
              VALUES (:uuid, :host_uuid, :title, :location, :type_uuid, :status_uuid, :description)";
    $pdo = MarketDatabase::connect();
    $statement = $pdo->prepare($query);
    $statement->execute([
      "uuid" => $uuid,
      "host_uuid" => $hostUUID,
      "title" => $title,
      "location" => $location,
      "type_uuid" => $typeUUID,
      "status_uuid" => "486514af-0cc8-11f1-94fb-34298f7857e5",
      "description" => $description
    ]);
    return $uuid;
  }

  public static function selectListByHost(string $hostUUID)
  {
    $query = "SELECT listing.uuid, 
                    airrent_auth_db.account.email, 
                    title, location, 
                    type.name AS type, 
                    status.name AS status,
                    description 
              FROM listing
              INNER JOIN airrent_auth_db.account 
                    ON listing.host_uuid = airrent_auth_db.account.uuid
              INNER JOIN type 
                    ON listing.type_uuid = type.uuid
              INNER JOIN status 
                    ON listing.status_uuid = status.uuid
              WHERE listing.host_uuid = :host_uuid";
    AuthDatabase::connect();
    $marketPDO = MarketDatabase::connect();
    $statement = $marketPDO->prepare($query);
    $statement->execute([
      "host_uuid" => $hostUUID
    ]);
    return $statement->fetchAll(PDO::FETCH_ASSOC);
  }
}
