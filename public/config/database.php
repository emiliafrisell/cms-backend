<?php

declare(strict_types=1);

use PDO;

class Database {

  private $conn;
  private $options;
  private $username;
  private $password;
  private $host;
  private $db;



  public function __construct() {
    $this->options = array(
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
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

  public function getConnection() {
    $this->conn = new PDO("mysql:host=$this->host;dbname=$this->db", $this->username, $this->password, $this->options);
    return $this->conn;
  }
}