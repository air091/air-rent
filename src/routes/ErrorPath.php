<?php
  
  http_response_code(404);
  echo json_encode([
    "success" => false,
    "message" => "404 Not Found"
  ]);