<?php

declare(strict_types=1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/counter.php';



$database = new Database();
$db_connection = $database->getConnection();


// initialize object
$counters = new Counter($db_connection);

$counters_statement = $counters->getAllCounters();

$counters_array = array();
$counters_array["counters"] = array();

while ($row = $counters_statement->fetch(PDO::FETCH_ASSOC)){
  extract($row);
  $e = array(
    "id" => $id,
    "value" => $value,
    "name" => $name
  );

  array_push($counters_array["counters"], $e);
}
echo json_encode($counters_array);