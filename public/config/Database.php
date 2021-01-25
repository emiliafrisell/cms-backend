<?php

declare(strict_types=1);


class Database {

  // database parameters (private vars can only be accessed within class)
  private array $options;
  private $username;
  private $password;
  private $host;
  private $db;

  // database connection object
  private PDO $conn;

  // init the database with our env variables (you can't set functions as properties by default so I used a constructor.
  public function __construct() {
    $this->options = array(
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // this is
      PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
      PDO::MYSQL_ATTR_SSL_CA => getenv('MYSQL_SSL_CA'),
      PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
      PDO::MYSQL_ATTR_SSL_KEY => getenv('MYSQL_SSL_KEY'),
      PDO::MYSQL_ATTR_SSL_CERT => getenv('MYSQL_SSL_CERT'),
    );

    $this->username = getenv('MYSQL_USERNAME');
    $this->password = getenv('MYSQL_PASSWORD');
    $this->host = getenv('MYSQL_HOST');
    $this->db = getenv('MYSQL_DATABASE');
  }

  public function getConnection(): PDO {

    try { // create a connection, passing in the database parameters
      $this->conn = new PDO(
        "mysql:host=$this->host;dbname=$this->db", $this->username, $this->password, $this->options);
    } catch (PDOException $exception) {
      echo "Something went wrong. Here's what: " . $exception->getMessage();
    }

    return $this->conn; // returns the PDO connection object
  }
}