<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 29/07/17
 * Time: 15.18
 */
require_once "../../../lib/start.php";

ini_set("display_errors", "1");

check_session();
check_permission(SEG_GROUP|DSG_PERM|DSG_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_mod_home__'] = "../";
$_SESSION['__area__'] = "manager";

$action = "update";
if(isset($_REQUEST['action'])) {
	$action = $_REQUEST['action'];
}

if($action === 'insert') {
	$value = $db->real_escape_string($_REQUEST['value']);
	$upd = "INSERT INTO rb_ex_consigli_orientativi (giudizio) VALUES ('$value')";
	$update_var = $db->executeUpdate($upd);
	header("Content-type: application/json");
	$response = array("status" => "ok", "message" => "Operazione completata");
	$res = json_encode($response);
	echo $res;
	exit;
}
else if($action == 'delete') {
	$id = $_REQUEST['id'];
	$upd = "DELETE FROM rb_ex_consigli_orientativi WHERE id_giudizio = $id";
	$update_var = $db->executeUpdate($upd);
	header("Content-type: application/json");
	$response = array("status" => "ok", "message" => "Operazione completata");
	$res = json_encode($response);
	echo $res;
	exit;
}
else {
	$field = $_REQUEST['id'];
	$value = $_REQUEST['value'];
	$upd = "REPLACE INTO rb_ex_consigli_orientativi (id_giudizio, giudizio) VALUES ($field, '$value')";
	$update_var = $db->executeUpdate($upd);
	header("Content-type: text/plain");
	print $value;
	exit;
}
