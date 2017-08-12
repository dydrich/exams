<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 06/08/17
 * Time: 19.53
 */
require_once "../../../lib/start.php";

ini_set("display_errors", "1");

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_mod_home__'] = "../";
$_SESSION['__area__'] = "teachers";

$year = $_SESSION['__current_year__']->get_ID();
$cls = $_SESSION['__classe__']->get_ID();

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

if(isset($_POST['action']) && $_POST['action'] == 'laude') {
	$stid = $_POST['stid'];
	$laude = $_POST['laude'];
	$sql = $db->executeUpdate("UPDATE rb_ex_esami_licenza SET lode = $laude WHERE alunno = $stid AND anno = $year");
	$_SESSION['students'][$_POST['stid']]['voti']['lode'] = $laude;
	echo json_encode($response);
	exit;
}

foreach ($_SESSION['students'] as $k => $student) {
	$grade = round($student['voti']['avg']);
	$esito = 0;
	if($grade > 5) {
		if($student['sesso'] == 'M'){
			$esito = 1;
		}
		else {
			$esito = 2;
		}
	}
	$sql = $db->executeUpdate("REPLACE INTO rb_ex_esami_licenza (anno, classe, alunno, esito, voto) 
									VALUES ($year, $cls, $k, $esito, $grade)");
	$student['finale']['esito'] = $esito;
	$student['finale']['voto'] = $grade;
	$_SESSION['students'][$k] = $student;
}

echo json_encode($response);
exit;