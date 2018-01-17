<?php

/*
 * Following code will update a product information
 * A product is identified by product id (pid)
 */

// array for JSON response
$response = array();

// check for required fields
if (isset($_POST['gid']) && isset($_POST['name']) && isset($_POST['priority']) && isset($_POST['description'])) {
    
    $gid = $_POST['gid'];
    $name = $_POST['name'];
    $priority = $_POST['priority'];
    $description = $_POST['description'];

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

    // mysql update row with matched pid
    $result = pg_query($db, "UPDATE assets SET name = '$name', priority = '$priority', description = '$description' WHERE gid = $gid");

    // check if row inserted or not
    if ($result) {
        // successfully updated
        $response["success"] = 1;
        $response["message"] = "Incident successfully updated.";
        
        // echoing JSON response
        echo json_encode($response);
    } else {
        
    }
} else {
    // required field is missing
    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing";

    // echoing JSON response
    echo json_encode($response);
}
?>
