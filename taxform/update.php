<?php

// array for JSON response
$response = array();

// include db connect class
require_once __DIR__ . '/gdb_constring.php';


$db = pg_connect(getenv("DATABASE_URL"));     
   
// check for post data
if (isset($_GET["gid"])) {
    $gid = $_GET['gid'];

    // get a product from products table
    $result = pg_query($db, "SELECT * FROM business WHERE gid = $gid");

    if (!empty($result)) {
        // check for empty result
        if (pg_num_rows($result) > 0) {

            $response = pg_fetch_object($result);

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