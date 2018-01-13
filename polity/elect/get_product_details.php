<?php

/*
 * Following code will get single product details
 * A product is identified by product id (pid)
 */

// array for JSON response
$response = array();

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
   
// check for post data
if (isset($_GET["gid"])) {
    $gid = $_GET['gid'];

    // get a product from products table
    $result = pg_query($db, "SELECT * FROM electionevent WHERE gid = $gid");

    if (!empty($result)) {
        // check for empty result
        if (pg_num_rows($result) > 0) {

            $result = pg_fetch_array($result);

            $product = array();
            $product["gid"] = $result["gid"];
            $product["name"] = $result["name"];
            $product["priority"] = $result["priority"];
            $product["description"] = $result["description"];
            $product["created_at"] = $result["created_at"];
            $product["updated_at"] = $result["updated_at"];
            // success
            $response["success"] = 1;

            // user node
            $response["product"] = array();

            array_push($response["product"], $product);

            // echoing JSON response
            echo json_encode($response);
        } else {
            // no product found
            $response["success"] = 0;
            $response["message"] = "No product found";

            // echo no users JSON
            echo json_encode($response);
        }
    } else {
        // no product found
        $response["success"] = 0;
        $response["message"] = "No product found";

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