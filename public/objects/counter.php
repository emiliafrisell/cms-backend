<?php

declare(strict_types=1);




class Counter {

  private $conn;


  //counter model
  public int $id;
  public int $value;
  public ?string $name;

  public function __construct($db_connection) {
      $this->conn = $db_connection;
  }

  public function getAllCounters () {
    $query = "select * from counters";
    $statement = $this->conn->prepare($query);
    $statement->execute();
    return $statement;
  }

}