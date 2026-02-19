<?php

require_once realpath(__DIR__ . "/../models/listing.php");
require_once realpath(__DIR__ . "/../models/User.php");

class MarketController
{
  public static function createList()
  {
    try {
      $input = json_decode(file_get_contents("php://input"), true);

      $title = isset($input["title"]) ? trim($input["title"]) : "";
      $location = isset($input["location"]) ? trim($input["location"]) : "";
      $type = isset($input["typeUUID"]) ? trim($input["typeUUID"]) : "";
      $description = $input["description"];

      if (!$title || !$location || !$type) {
        http_response_code(400);
        echo json_encode([
          "success" => false,
          "message" => "Title, location and type are required"
        ]);
      }

      $auth = $_SERVER["user_auth"]->sub ?? "";
      // $hostUUID = $auth->sub;
      $user = User::selectAccountByUuid($auth);
      if (!$user || $user["role"] !== "host" || $user["status"] !== "active") {
        http_response_code(401);
        echo json_encode([
          "success" => false,
          "message" => "Unauthorized"
        ]);
        exit();
      }

      $list = Listing::insertList($user["uuid"], $title, $location, $type, $description);
      echo json_encode([
        "success" => true,
        "message" => "List Created Successfully",
        "listUUID" => $list
      ]);
    } catch (PDOException $err) {
      http_response_code(500);
      echo json_encode([
        "success" => false,
        "message" => "Create List Failed",
        "errorMessage" => $err->getMessage()
      ]);
      exit();
    }
  }

  public static function getLists() {
    try {
      $user = $_SERVER["user_auth"]->sub;

      $lists = Listing::selectListByHost($user);

      echo json_encode([
        "success" => true,
        "message" => "Lists fetched successfully",
        "lists" => $lists
      ]);

    } catch (PDOException $err) {
      http_response_code(500);
      echo json_encode([
        "success" => false,
        "message" => "Get Lists Failed",
        "errorMessage" => $err->getMessage()
      ]);
      exit();
    }
  }
}
