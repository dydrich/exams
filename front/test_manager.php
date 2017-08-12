<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 15/07/17
 * Time: 9.34
 */
require_once "../../../lib/start.php";
require_once "../../../lib/RBTime.php";
require_once "../lib/ExamTest.php";
require_once "../lib/NationalTest.php";

ini_set("display_errors", "1");

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_mod_home__'] = "../";
$_SESSION['__area__'] = "teachers";

$year = $_SESSION['__current_year__']->get_ID();

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

switch ($_REQUEST['action']) {
	case "register_grade":
		$test_id = $_SESSION['test'];
		$cls = $_SESSION['__classe__']->get_ID();
		$test = new ExamTest($test_id, $year, new MySQLDataLoader($db), null, $cls);
		$student = $_POST['std'];
		$grade = $_POST['value'];
		$test->registerGrade($student, $grade);
		break;
	case 'register_choice':
		$test_id = $_SESSION['test'];
		$cls = $_SESSION['__classe__']->get_ID();
		$test = new ExamTest($test_id, $year, new MySQLDataLoader($db), null, $cls);
		$student = $_POST['std'];
		$choice = $_POST['value'];
		$test->registerChoice($student, $choice);
		break;
	case 'register_invalsi':
		$test_id = $_SESSION['test'];
		$cls = $_SESSION['__classe__']->get_ID();
		$test = new NationalTest($test_id, $year, new MySQLDataLoader($db), null, $cls);
		$student = $_POST['std'];
		$ita = $_POST['ita'];
		$mat = $_POST['mat'];
		$val = $ita."#".$mat;
		$test->registerScore($student, $ita, $mat);
		break;
	case 'download_judgments':
		$test_id = $_SESSION['test'];
		$cls = $_SESSION['__classe__']->get_ID();
		$test = new ExamTest($test_id, $year, new MySQLDataLoader($db), null, $cls);
		$mat = $db->executeCount("SELECT materie FROM rb_ex_prove_scritte WHERE anno = $year AND id_prova = $test_id");
		$test->setSubjects($mat);
		$test->updateAllJudgmentsFromModel();
		break;
}

$res = json_encode($response);
echo $res;
exit;