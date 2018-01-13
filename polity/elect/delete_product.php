<?php

/*
 * Following code will delete a product from table
 * A product is identified by product id (pid)
 */

// array for JSON response
$response = array();

// check for required fields
if (isset($_POST['pid'])) {
    $gid = $_POST['pid'];

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
    $result = pg_query($db,"DELETE FROM electionevent WHERE gid = $gid");
    
    // check if row deleted or not
    if (pg_affected_rows() > 0) {
        // successfully updated
        $response["success"] = 1;
        $response["message"] = "Incident successfully deleted";

        // echoing JSON response
        echo json_encode($response);
    } else {
        // no product found
        $response["success"] = 0;
        $response["message"] = "No incident found";

        // echo no users JSON
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