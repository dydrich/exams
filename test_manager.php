<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 4/26/17
 * Time: 6:21 PM
 */
require_once "../../lib/start.php";
require_once "../../lib/RBTime.php";
require_once "lib/WrittenTest.php";

ini_set("display_errors", "1");

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";
$_SESSION['__area__'] = "manager";

$anno = $_SESSION['__current_year__']->get_ID();

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

switch ($_REQUEST['action']) {
	case "insert":
		$test = $_POST['test'];
		$materie = implode(",", $_POST['materie']);
		$date = format_date($_POST['test_date'], IT_DATE_STYLE, SQL_DATE_STYLE, "-");
		$time = $_POST['test_time'];
		$_duration = $_POST['duration'];
		$duration = new RBTime(0, 0, 0);
		$duration->setTime(60*$_duration);
		try {
			$wt = new WrittenTest(0, $test, $materie, $date." ".$time, $anno, $duration, new MySQLDataLoader($db));
		} catch (MySQLException $ex) {
			$response['status'] = "kosql";
			$response['message'] = $ex->getMessage();
			$response['query'] = $ex->getQuery();
			echo json_encode($response);
			exit;
		}
		$testID = $wt->insert();
		break;
	case "add_teacher":
		$teacher = $_POST['teacher'];
		$test = $_POST['test'];
		$comm = $_POST['comm'];
		$name = $_POST['name'];
		$wt = WrittenTest::getInstance($test, new MySQLDataLoader($db));
		$wt->loadWorkshift();
		$wt->addWorkshift($teacher, $comm, $name);
		$_SESSION['test'] = $wt;
		break;
}

$res = json_encode($response);
echo $res;
exit;