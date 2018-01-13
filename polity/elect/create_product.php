<?php

/*
 * Following code will create a new product row
 * All product details are read from HTTP Post Request
 */

// array for JSON response
$response = array();

// check for required fields
if (isset($_POST['name']) && isset($_POST['priority']) && isset($_POST['description'])) {
    
    $name = $_POST['name'];
    $priority = $_POST['priority'];
    $description = $_POST['description'];

    if (empty($_POST['lng'])) {$lng = 3.35;} else {$lng = $_POST['lng'];}
    if (empty($_POST['lat'])) {$lat = 6.63;} else {$lat = $_POST['lat'];}
    if (isset($_POST['fileURL'])) {$fileURL = $_POST['fileURL'];} else {$fileURL = '';}
/*
   $host        = "host=127.0.0.1";
   $port        = "port=5432";
   $dbname      = "dbname=PData";
   $credentials = "user=postgres password=PgAdmin";



// connecting to db
$db = new DB_CONNECT(); 

*/

   
// include db connect class
require_once __DIR__ . '/gdb_constring.php';


   $db = pg_connect(DB_CONNSTRING);     

    // mysql inserting a new row
    $result = pg_query($db, "INSERT INTO electionevent(name, priority, description, \"fileURL\", geom) VALUES('$name', '$priority', '$description', '$fileURL', ST_SetSRID(ST_MakePoint($lng, $lat),4326))");

    // check if row inserted or not
    if ($result) {
        // successfully inserted into database
        $response["success"] = 1;
        $response["message"] = "Incident successfully submitted.";

        // echoing JSON response
        echo json_encode($response);
    } else {
        // failed to insert row
        $response["success"] = 0;
        $response["message"] = "Oops! An error occurred.";
        
        // echoing JSON response
        echo json_encode($response);
    }
} else {
    // required field is missing
    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing";

    // echoing JSON response
    echo json_encode($response);
}
?>