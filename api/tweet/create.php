<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

 
// get database connection
include_once '../config/database.php';
 
// instantiate product object
include_once '../object/tweet.php';
 
$database = new Database();
$db = $database->getConnection();
 
$tweet = new Tweet($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if(!empty($data->user_id) && !empty($data->message)) {
 
    // set product property values
    $tweet->user_id = $data->user_id;
    $tweet->message = $data->message;
        
    // create the product
    if($tweet->create()){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("message" => "Tweet a été posté."));
    }
 
    // if unable to create the product, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
        //echo json_encode(array("message" => " Impossible de créer un tweet."));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Impossible de créer un tweet. Les données sont incomplètes."));
}
?>