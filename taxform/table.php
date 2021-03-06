<?php

# Retrive URL variables
if (empty($_GET['geotable'])) {
$geotable = 'assets';
} else
$geotable = $_GET['geotable'];
 
if (empty($_GET['geomfield'])) {
$geomfield = 'geom';
} else
$geomfield = $_GET['geomfield'];
 
if (empty($_GET['srid'])) {
$srid = '4326';
} else
$srid = $_GET['srid'];
 
if (empty($_GET['fields'])) {
$fields = '*';
} else
$fields = $_GET['fields'];
 
$parameters = $_GET['parameters'];
 
if (empty($_GET['orderby'])) {
$orderby = 'gid';
} else
$orderby = $_GET['orderby'];
 
if (empty($_GET['sort'])) {
$sort = 'ASC';
} else
$sort = $_GET['sort'];
 
// include db connect class
require_once __DIR__ . '/gdb_constring.php';


  $conn = pg_connect(getenv("DATABASE_URL"));
if (!$conn) {
echo "Not connected : " . pg_error();
exit;
}
 
# Build SQL SELECT statement and return the geometry as a GeoJSON element in EPSG: 4326
$sql = "SELECT " . pg_escape_string($fields) . " FROM " . pg_escape_string($geotable);
if (strlen(trim($parameters)) > 0) {
$sql .= " WHERE " . $parameters;
}
if (strlen(trim($orderby)) > 0) {
$sql .= " ORDER BY " . pg_escape_string($orderby) . " " . $sort;
}
//echo $sql;

# Try query or error
$rs = pg_query($conn, $sql);
if (!$rs) {
echo "An SQL error occured.\n";
exit;
}
// echo "connected.\n";
# Build GeoJSON
$output = '';
$rowOutput = '';
$ralt = 'class="alt"';
 
while ($row = pg_fetch_assoc($rs)) {
$props = '';
$thead = '';
foreach ($row as $key => $val) {

 $thead .='<th><b>' . strtoupper($key) . '</b></th>';
   if ($key == "fileURL" && $val !='')  $val = "<a href= $val target='_blank'>Picture, File, Audio or Video</a>"; 
 $props .='<td>' . $val . '</td>';

}
 $thead .='<th><b>ACTION</b></th>'; 
 $icon = "<a href='read.php?id=". $row['gid'] ."' title='View Record' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>
        <a href='javascript:updatebusi(". $row['gid'] .");' title='Update Record' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>
        <a href='#' title='Delete Record' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                                      
  $props .='<td>' . $icon . '</td>';
 
 
$rowOutput = "<tr $ralt>" . $props . '</tr>';
$output .= $rowOutput;
if ($ralt == '') $ralt =  'class="alt"'; else $ralt = '';
}
//echo $props;
$thead = '<thead><tr>' . $thead . '</tr></thead>';
$output = '<table class="table table-bordered table-striped" cellspacing="0" id="hor-zebra" >' . $thead. '<tbody>'. $output . ' </tbody></table>';
echo $output;
?>