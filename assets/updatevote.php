<?php

function escapeJsonString($value) { # list from www.json.org: (\b backspace, \f formfeed)
$escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
$replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
$result = str_replace($escapers, $replacements, $value);
return $result;
}

// array for JSON response
$response = array();

# Retrive URL variables
if (isset($_GET['votetable']) && isset($_GET['fields']) && isset($_GET['parameter']) && isset($_GET['values']) && isset($_GET['parvalue'])) {
   $votetable = $_GET['votetable'];
   $fields = $_GET['fields'];
   $parameter = $_GET['parameter'];
   $values = $_GET['values'];
   $parvalue = $_GET['parvalue'];

  //echo pg_escape_string($fields);

 
// include db connect class
require_once __DIR__ . '/gdb_constring.php';

   $conn = pg_connect(DB_CONNSTRING); 
   
if (!$conn) {
echo "Not connected : " . pg_error();
exit;
}
 
# Build SQL SELECT statement and return the geometry as a GeoJSON element in EPSG: 4326
$sql = "UPDATE " . pg_escape_string($votetable) . " SET " . pg_escape_string($fields) . " = " . pg_escape_string($values) . " WHERE $parameter = '$parvalue'";
// if (strlen(trim($parameters)) > 0) {
// $sql .= " WHERE " . pg_escape_string($parameters);
// }

echo $sql;
 
# Try query or error
$rs = pg_query($conn, $sql);
if (!$rs) {
echo "An SQL error occured.\n";
exit;
}

// successfully updated
        $response["success"] = 1;
        $response["message"] = "Votes successfully updated.";
        
        // echoing JSON response
        echo json_encode($response);

} else {
    // required field is missing
    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing";

    // echoing JSON response
    echo json_encode($response);
}

?>