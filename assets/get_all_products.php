<?php

/*
 * Following code will list all the products
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

// get all products from products table
$result = pg_query($db,"SELECT * FROM assets") or die(pg_last_error());

// check for empty result
if (pg_num_rows($result) > 0) {
    // looping through all results
    // products node
    $response["assets"] = array();
    
    while ($row = pg_fetch_array($result)) {
        // temp user array
        $product = array();
        $product["gid"] = $row["gid"];
        $product["name"] = $row["name"];
        $product["priority"] = $row["priority"];
        $product["description"] = $row["description"];
        $product["created_at"] = $row["created_at"];
        $product["updated_at"] = $row["updated_at"];


        // push single product into final response array
        array_push($response["assets"], $product);
    }
    // success
    $response["success"] = 1;

    // echoing JSON response
    echo json_encode($response);
} else {
    // no products found
    $response["success"] = 0;
    $response["message"] = "No products found";

    // echo no users JSON
    echo json_encode($response);
}
?>
