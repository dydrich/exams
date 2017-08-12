<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 05/08/17
 * Time: 19.43
 */
require_once "../../../lib/start.php";

ini_set("display_errors", "1");

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_mod_home__'] = "../";
$_SESSION['__area__'] = "teachers";

$year = $_SESSION['__current_year__']->get_ID();
$action = $_POST['action'];
$trace = $db->real_escape_string($_POST['trace']);
$grade = $_POST['grade'];
$stid = $_POST['stid'];

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

switch($action) {
	case 'insert':
		$sql = "INSERT INTO rb_ex_voti_esame (anno, classe, alunno, voto, id_prova, `traccia-quesiti`, giudizio) 
				VALUES ($year, {$_SESSION['__classe__']->get_ID()}, $stid, $grade, 0, NULL, '$trace')";
		break;
	case 'update':
		$sql = "UPDATE rb_ex_voti_esame SET voto = $grade, giudizio = '$trace' WHERE anno = $year AND alunno = $stid AND id_prova = 0";
		break;
	case 'delete':
		break;
}

try{
	$res = $db->executeUpdate($sql);
} catch (MySQLException $ex){
	$response['status'] = "kosql";
	$response['message'] = $ex->getMessage();
	$response['query'] = $ex->getQuery();
	echo json_encode($response);
	exit;
}

$_SESSION['students'][$stid]['voti']['orale'] = $grade;
$_SESSION['students'][$stid]['tracce']['orale'] = $trace;

echo json_encode($response);
exit;

