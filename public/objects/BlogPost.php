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

  // QUERY METHODS //

  // get all the posts
  public function getAllPosts(): PDOStatement {
    //write the SQL query
    $query = "select * from blog_posts order by created_at desc";

    //prep the PDO statement object and execute it to the SQL database
    $statement = $this->conn->prepare($query);
    $statement->execute();
    return $statement;
  }

  // get a single post
  public function getPost($id) {
    $query = "select * from blog_posts where id=:id"; // don't use the $symbol for security reasons.
    $statement = $this->conn->prepare($query);
    $statement->execute(compact('id')); //passing in the id to the SQL statement
    $record = $statement->fetch(PDO::FETCH_ASSOC);

    if ($record) { // if the record exists
      extract($record);
      $post = array(
        "id" => $id,
        "title" => $title,
        "body" => $body,
        "created_at" => $created_at
      );
      //directly returning PHP array here, instead of the PDO statement, it's easier
      return $post;
    } else {
      return null;
    }
  }
}