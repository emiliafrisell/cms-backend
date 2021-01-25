<?php

declare(strict_types=1); // enforces strict types for PHP, like typescript

// add some headers, header function is built into PHP. headers needed for HTTP!
header("Access-Control-Allow-Origin: *"); // completely public, allows CORS
header("Content-Type: application/json; charset=UTF-8"); // content type is JSON

//include files, there's a better way to do this w/ autoload but whatever.
include_once '../config/Database.php';
include_once '../objects/BlogPost.php';


// initialise the DB and connect to it with a PDO connection object
$dbPDO = (new Database())->getConnection();


// initialize object using the connector we just created
$post = new BlogPost($dbPDO);


if ($_SERVER['REQUEST_METHOD'] === 'GET') { //this is how you find out the HTTP method used

  $result = $post->getAllPosts(); // this returns a PDO statement that we need to turn into json
  $rows = $result->rowCount(); // executed PDO statements have a row count like the SQL table

  // this next bit is essentially translating the PDO statement to PHP object then converting to JSON
  if ($rows > 0) {
    $posts_array = array();
    $posts_array['posts'] = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) { // fetch the result as an associative array with columns as keys
      extract($row); // extract seems to be kind of like destructuring in JS
      $post = array(
        "id" => $id,
        "title" => $title,
        "body" => $body,
        "created_at" => $created_at
      );

      array_push($posts_array["posts"], $post);
    }
    echo json_encode($posts_array); // encode PHP object as JSON and output

  } else {
    echo json_encode(array('message' => 'Nothing here, go take a hike.'));
  }
};

