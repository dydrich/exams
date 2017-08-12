<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 29/07/17
 * Time: 17.54
 */
require_once "../../../lib/start.php";

ini_set("display_errors", "1");

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_mod_home__'] = "../";
$_SESSION['__area__'] = "teachers";

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

$stid = $_POST['stid'];
$cons = $_POST['value'];

$year = $_SESSION['__current_year__']->get_ID();

$student = $_SESSION['students'][$stid];
$cls = $student['classe'];

$upd = "REPLACE INTO rb_ex_consigli_orientativi_alunni (id_alunno, anno, classe, consiglio) 
		VALUES ($stid, $year, $cls, $cons)";
$update_var = $db->executeUpdate($upd);

$res = json_encode($response);
echo $res;
exit;

