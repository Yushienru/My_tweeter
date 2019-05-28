<?php
class Tweet{

    // database connection and table name
    private $conn;
    private $table_name = "Tweet";

    // object properties
    public $id;
    public $name;
    public $message;
    public $date;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read tweets
    function read() {

        // select all query
        $query ="   SELECT Tweet.id, message, date, User.name
                    FROM " . $this->table_name . "
                    INNER JOIN User ON Tweet.user_id = User.id
                    ORDER BY date DESC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // get record details / values
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
        // assign values to object properties
        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->message = $row['message'];
        $this->date = $row['date'];

        // execute query
        $stmt->execute();

        return $stmt;
    }

// create tweet
function create() {

    // query to insert record
    $query =    "INSERT INTO " .$this->table_name . "
                SET
                user_id = :user_id,
                message = :message";

    // prepare query
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->user_id=htmlspecialchars(strip_tags($this->user_id));
    $this->message=htmlspecialchars(strip_tags($this->message));


    // bind values
    $stmt->bindParam(":user_id", $this->user_id);
    $stmt->bindParam(":message", $this->message);

    // execute query
    if($stmt->execute()) {
        return true;
    }
    echo $query;
//    echo "id: " . $this->user_id . " message: " . $this->message;

    return false;

}
}
