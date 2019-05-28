<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../object/tweet.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$tweet = new Tweet($db);
 
// query products
$stmt = $tweet->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // products array
    $tweet_arr=array();
    $tweet_arr["records"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $tweet_item=array(
            "id" => $id,
            "name" => $name,
            "message" => $message,
            "date" => $date,
        );
 
        array_push($tweet_arr["records"], $tweet_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show products data in json format
    exit(json_encode($tweet_arr));
}
 
else{
 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no tweet found
    echo json_encode(
        array("message" => "Aucun tweet trouvé.")
    );
}

