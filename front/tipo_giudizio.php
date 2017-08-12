<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 16/07/17
 * Time: 11.00
 */

require_once "../../../lib/start.php";

ini_set("display_errors", "1");

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_mod_home__'] = "../";
$_SESSION['__area__'] = "teachers";

$year = $_SESSION['__current_year__']->get_ID();

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

$type = $_POST['type'];
$subject = $_POST['mat'];

try {
	$db->executeUpdate("REPLACE INTO rb_ex_tipogiudizio_anno (id_tipo, materia, anno) 
						VALUES ({$type}, {$subject}, {$year})");
} catch (MySQLException $ex) {
	$response['status'] = "kosql";
	$response['message'] = "Si è verificato un errore. Si prega di segnalare il problema al responsabile del software";
	$response['dbg_message'] = $ex->getQuery()."----".$ex->getMessage();
	$res = json_encode($response);
	echo $res;
	exit;
}


$res = json_encode($response);
echo $res;
exit;