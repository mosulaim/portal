<?php
include("connection.php");
 
$params = $_REQUEST;
$action = isset($params['action']) && $params['action'] !='' ? $params['action'] : 'list';
$empCls = new business();
 
switch($action) {
 case 'list':
  $empCls->getbusinesses();
 break;
 default:
 
 case 'get_one':
 	$gid = isset($params['gid']) && $params['gid'] !='' ? $params['gid'] : 0;
	$empCls->getbusiness($gid);
 break;
 
 
 case 'add':
	$empCls->insertbusiness();
 break;
   
 case 'edit':
	$empCls->updatebusiness();
 break;
 
 case 'delete':
 	$gid = isset($params['gid']) && $params['gid'] !='' ? $params['gid'] : 0;
	$empCls->deletebusiness($gid);
 break;
 return;
}
 
 
class business {
  protected $conn;
  protected $data = array();
  function __construct() {

	$db = new dbObj();
	$connString =  $db->getConnstring();
    $this->conn = $connString;
  }
  
  function getbusinesses() {
    $sql = "SELECT * FROM business";
	$queryRecords = pg_query($this->conn, $sql) or die("error fetching businesses data");
	$data = pg_fetch_all($queryRecords);
	echo json_encode($data);
  }
  
  function getbusiness($gid) {
    $sql = "SELECT * FROM business Where gid=".$gid;
	$queryRecords = pg_query($this->conn, $sql) or die("error fetching business data");
	$data = pg_fetch_object($queryRecords);
	echo json_encode($data);
  }
	
 function insertbusiness() {
 $data = $resp = array();
 $resp['status'] = false;
 $data['bld_id'] = $_POST["bld_id"];
 $data['ppty_use'] = $_POST["ppty_use"];
 $data['ppty_id'] = $_POST["ppty_id"];
 $data['businame'] = $_POST["businame"];
 $data['busitype'] = $_POST["busitype"];
 $data['busiprod'] = $_POST["busiprod"];
 $data['ppty_plt_n'] = $_POST["ppty_plt_n"];
 $data['ppty_strn'] = $_POST["ppty_strn"];
 $data['ppty_addy'] = $_POST["ppty_addy"];
 $data['busireg_st'] = $_POST["busireg_st"];
 $data['busi_regno'] = $_POST["busi_regno"];
 $data['busi_inc_y'] = $_POST["busi_inc_y"];
 $data['staf_str'] = $_POST["staf_str"];
 $data['contact'] = $_POST["contact"];
 $data['tax_appli'] = $_POST["tax_appli"];
 $data['tin_status'] = $_POST["tin_status"];
 $data['tin_no'] = $_POST["tin_no"];
 $data['lt_pd_year'] = $_POST["lt_pd_year"];
 $data['lt_amt_pd'] = $_POST["lt_amt_pd"];
 
 $result = pg_insert($this->conn, 'business' , $data) or die("Error inserting business data");
 
 
 $resp['status'] = true;
 $resp['Record'] = $data;
 echo json_encode($resp);  // send data as json format*/
 
}
  
  function updatebusiness() {
		$data = $resp = array();
		$resp['status'] = false;
		$data['bld_id'] = $_POST["bld_id"];
		$data['ppty_use'] = $_POST["ppty_use"];
		$data['ppty_id'] = $_POST["ppty_id"];
		$data['businame'] = $_POST["businame"];
		$data['busitype'] = $_POST["busitype"];
		$data['busiprod'] = $_POST["busiprod"];
		$data['ppty_plt_n'] = $_POST["ppty_plt_n"];
		$data['ppty_strn'] = $_POST["ppty_strn"];
		$data['ppty_addy'] = $_POST["ppty_addy"];
		$data['busireg_st'] = $_POST["busireg_st"];
		$data['busi_regno'] = $_POST["busi_regno"];
		$data['busi_inc_y'] = $_POST["busi_inc_y"];
		$data['staf_str'] = $_POST["staf_str"];
		$data['contact'] = $_POST["contact"];
		$data['tax_appli'] = $_POST["tax_appli"];
		$data['tin_status'] = $_POST["tin_status"];
		$data['tin_no'] = $_POST["tin_no"];
		$data['lt_pd_year'] = $_POST["lt_pd_year"];
		$data['lt_amt_pd'] = $_POST["lt_amt_pd"];
		$data['gid'] = $_POST["gid"];
		
		$result = pg_update($this->conn, 'business' , $data, array('gid' => $data['gid'])) or die("Error updating business data");
		
        $resp['status'] = true;
        $resp['Record'] = $data;
        echo json_encode($resp);  // send data as json format*/
		
	}
  
  function deletebusiness($gid) {
	$sql = "Delete FROM business Where gid=".$gid;
	$queryRecords = pg_query($this->conn, $sql) or die("Error in delete operation");
	if($queryRecords) {
		echo true;
	} else {
		echo false;
	}
}
}
?>