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
   


if (isset($_GET["state"])) {
    $state = $_GET['state'];
    } else {
    $state = 'Lagos';
    // required field is missing
   // $response["success"] = 0;
   // $response["message"] = "Required field(s) is missing";

    // echoing JSON response
   // echo json_encode($response);
}
    // get a product from products table
    $result = pg_query($db, "SELECT * FROM statevote WHERE state = '$state'");

    if (!empty($result)) {
        // check for empty result
        if (pg_num_rows($result) > 0) {

            $result = pg_fetch_array($result);
              $res2 = array();
              
      foreach ($result as $key => $val) {
               if (!is_int($key) && $key != 'gid' && $key != 'state' && $key != 'leading_party') {
                  $res2[$key] = $val;
                }
        }   
              
             if (isset($_GET['np']) && !empty($_GET['np'])){
               $np = $_GET['np'];  
               asort($res2);
               $res2 = array_slice($res2, -$np);
               ksort($res2);
              //echo json_encode($result);
             } 
            
            // user node
            $response["data"] = array();
            
         foreach ($res2 as $key => $val) {
          
            $data = array();
            $data["name"] = $key;
            $data["data1"] = $val;
            
            if ($val != null) {            
                array_push($response["data"], $data);
            } else {
              if (isset($_GET['all']) && !empty($_GET['all'])) {array_push($response["data"], $data);}
              } 
         }   

          // echoing JSON response
          echo json_encode($response);
        } else {
            // no product found
            $response["success"] = 0;
            $response["message"] = "State not found";

            // echo no users JSON
            echo json_encode($response);
        }
    } else {
        // no product found
        $response["success"] = 0;
        $response["message"] = "No result found";

        // echo no users JSON
        echo json_encode($response);
    }

?>