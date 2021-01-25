<?php

declare(strict_types=1);


class BlogPost {
  //db connection (it's not set until we construct it with our connection)
  private PDO $conn;

  // model properties, set to public so we can set them from outside the class
  public int $id;
  public string $title;
  public string $body;
  public string $created_at;


  // init this connection by passing in a database object (which has the parameters)
  public function __construct(PDO $dbPDO) {
    $this->conn = $dbPDO;
  }

  // QUERY METHODS

  // get all the posts
  public function getAllPosts () {
    //write the SQL query
    $query = "select * from blog_posts order by created_at desc";

    //prep the PDO statement object and execute it to the SQL database
    $statement = $this->conn->prepare($query);
    $statement->execute();
    return $statement;
  }

}