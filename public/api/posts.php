<?php

declare(strict_types=1); // enforces strict types for PHP, like typescript

// add some headers, header function is built into PHP. headers needed for HTTP!
header("Access-Control-Allow-Origin: *"); // completely public, allows CORS
header("Content-Type: application/json; charset=UTF-8"); // content type is JSON

// for POST requests we need to add more headers --
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  // i don't fully get this header but apparently it's for security, cross-scripting attacks
};


//include files, there's a better way to do this w/ autoload but whatever.
include_once '../config/Database.php';
include_once '../objects/BlogPost.php';


// initialise the DB and connect to it with a PDO connection object
$dbPDO = (new Database())->getConnection();


// initialize object using the connector we just created
$post = new BlogPost($dbPDO);



// LOGIC FOR GET REQUESTS
if ($_SERVER['REQUEST_METHOD'] === 'GET') { //this is how you find out the HTTP method used

  // this block of code checks if the client has sent in in an id parameter, if it has, then i get just a single post instead.
  if (isset($_GET['id'])) {
    $post->id = $_GET['id'] + 0;
    $result = $post->getPost($post->id);
    echo json_encode($result);
    return;
  }

  // if not, then i get all the posts and convert them to an array.
  $result = $post->getAllPosts(); // this returns a PDO statement that we need to turn into json
  $rows = $result->rowCount(); // executed PDO statements have a row count like the SQL table

  // this next bit is essentially translating the PDO statement to PHP array then converting to JSON
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
    echo json_encode($posts_array); // encode PHP associative array as JSON and output

  } else {
    //have to encode the PHP array as JSON even for the error message!
    echo json_encode(array('message' => 'Nothing here, go take a hike.'));
  }
};

// LOGIC FOR POST REQUEST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // reading in the JSON data (you MUST stringify the request body as JSON on the frontend for this to work)
  $data = json_decode(file_get_contents("php://input"));

  $post->title = $data->title;
  $post->body = $data->body;
//  $post->created_at = date('Y-m-d H:i:s'); // PHP apparently has a better date function than JS.
  // I don't use the date here since mySQL will automatically set the date

  if ($post->createPost()) {
    echo 'success';
    $post->getAllPosts();
  } else {
    echo json_encode(array('message' => 'Something went wrong and nothing was created.'));
  }
}

// LOGIC FOR DELETE REQUEST
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
  // reading in the JSON data (you MUST stringify the request body as JSON on the frontend for this to work)
  $data = json_decode(file_get_contents("php://input"));

  $post->id = $data->id;

  if ($post->deletePost($post->id)) {
    echo 'success';
  } else {
    echo json_encode(array('message' => 'Something went wrong and nothing was deleted.'));
  }
}

// LOGIC FOR PUT REQUEST


